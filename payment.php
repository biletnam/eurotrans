<?php
require __DIR__ . '/vendor/autoload.php';
use YandexCheckout\Client;

if (isset($_POST)) {
  $stripGet = array_map('strip_tags', $_POST);
}

$paymentMessage = 'Ваш платеж получен. Ваш билет отправлен Вам на почту.';
$payButton = isset($stripGet['pay']) ? intval($stripGet['pay']) : 0;



if (isset($payment->object->status)) {
  $payment = $notification->getObject();
  $client->capturePayment(
    array(
      'amount' => $payment->amount,
    ),
    $payment->id,
    uniqid('', true)
  );}



// Данные, которые пришли в теле сообщения
$content = file_get_contents('php://input');

// Преобразуем данные в массив
$data = json_decode($content, TRUE);

if ($data['object']['status'] === 'waiting_for_capture') {
  $payment = $notification->getObject();
  $client->capturePayment(
    array(
      'amount' => $payment->amount,
    ),
    $payment->id,
    uniqid('', true)
  );

  $paymentMessage = "Платеж выполнен успешно. \n Билет отправлен Вам на электронную почту";
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>EuroTrans | Бронирование и покупка билета</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.min.css">
</head>

<body class="page page_inner">
<header class="main-header main-header_booking">
  <div class="main-header__top"><a class="logo main-header__logo" href="/"><img class="logo__image" src="img/logo.png"></a>
    <ul class="breadcrumbs">
      <li class="breadcrumbs__item text text_regular">Выбор</li>
      <li class="breadcrumbs__item text text_regular">Оформление</li>
      <li class="breadcrumbs__item breadcrumbs__item_active text text_regular">Оплата</li>
    </ul>
  </div>
</header>
<main class="page__main">

  <section class="execution" id="app">
    <h1 class="execution__title execution__title_main text text_regular">
      <?= $paymentMessage ?>
    </h1>
  </section>

</main>
<footer class="main-footer page__main-footer">
  <section class="main-footer__top">
    <h2 class="visually-hidden">Верхняя секция основного подвала страницы</h2><a href="/"
                                                                                 class="logo main-footer__logo">
      <img src="img/logo.png"></a>
    <section class="additional-menu main-footer__company">
      <h3 class="additional-menu__title text text_semibold">O компании</h3>
      <ul class="additional-menu__list">
        <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">О нас</a></li>
        <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Договор оферты</a>
        </li>
        <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Политика
            конфиденциальности</a></li>
        <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Контакты</a></li>
      </ul>
    </section>
    <section class="additional-menu main-footer__company">
      <h3 class="additional-menu__title text text_semibold">Пользователям</h3>
      <ul class="additional-menu__list">
        <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Вопросы и
            ответы</a></li>
        <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Купить билеты</a>
        </li>
      </ul>
    </section>
    <a class="contacts contacts_footer main-footer__contacts" href="tel:8800121212"><span
        class="contacts__content text text_regular">Наш номер телефона</span><span
        class="contacts__phone text text_semibold">8-800-123-12-12</span></a>
  </section>
  <section class="main-footer__copyright">
    <h2 class="visually-hidden">Секция с копирайтами</h2>
    <p class="main-footer__copy text text_regular">ИП Яцунов М.С.</p><a class="main-footer__copy-link text text_regular"
                                                                        href="http://www.mindsell.ru">MindSell -
      разработка сайта</a>
  </section>
</footer>
</body>

</html>

