<?php

require __DIR__ . '/vendor/autoload.php';
//require_once 'utils/db-helper.php';
require_once 'utils/main-functions.php';

use YandexCheckout\Client;

if (isset($_GET)) {
  $stripGet = array_map('strip_tags', $_GET);
}

//$db = connect();

if (isset($_POST)) {
  $postData = file_get_contents('php://input');
  $data = json_decode($postData, true);
  echo '<pre>';print_r($postData);echo '</pre>';
}
echo '<pre>';print_r($data);echo '</pre>';
echo '<pre>';print_r($_POST);echo '</pre>';

$typeResponse = isset($data['type']) ? $data['type'] : '';
$eventResponse = isset($data['event']) ? $data['event'] : '';
$statusPayment = isset($data['status']) ? $data['status'] : '';

$payButton = isset($stripGet['pay']) ? intval($stripGet['pay']) : 0;

$createPayment = "UPDATE `transaction` SET `status` = 'pending', `date_changed` = NOW(), transaction_id = ? WHERE `id` = ?";
$updatePayment = "UPDATE `transaction` SET `status` = 'waiting_for_capture', `date_changed` = NOW(), transaction_id = ? WHERE `id` = ?";
$capturePayment = "UPDATE `transaction` SET `status` = 'succeeded', `date_changed` = NOW(), transaction_id = ? WHERE `id` = ?";

$getTransactionById = "SELECT * FROM `transaction` WHERE id = ?";
$getTransactionByTransactionID = "SELECT * FROM `transaction` WHERE transaction_id = ?";

$client = new Client();
$client->setAuth('523059', 'test_ERTQq8DR3L3PKNonhtTlqT24lg8CsDtoK88SiucCQYA');

if ($payButton === 1) {
  $payment = $client->createPayment(
    array(
      'amount' => array(
        'value' => 40.0,
        'currency' => 'RUB'
      ),
      'confirmation' => array(
        'type' => 'redirect',
        'return_url' => 'https://test.evrotrans.net/index.html',
      ),
      'description' => 'Заказ №72',
    ),
    uniqid('', true)
  );

  if (isset($payment->confirmation->confirmation_url)) {
    header("Location: " . $payment->confirmation->confirmation_url);
  }
  $paymentMessage = 'Ваш платеж Отправлен';

}
if ($typeResponse === 'notification' && $eventResponse === "payment.waiting_for_capture") {
  $paymentId = $data['object']['id'];
  $amount = $data['object']['amount']['value'];
  $string = "Подтверждаю платеж: " . $paymentId;

  $db->query("INSERT INTO `transaction` (`data`) VALUES ('$paymentId')");

  $idempotenceKey = uniqid('', true);
  $response = $client->capturePayment(
    array(
      'amount' => array(
        'value' => "$amount",
        'currency' => 'RUB',
      ),
    ),
    $paymentId,
    $idempotenceKey
  );
}

$paymentMessage = 'Платеж сформирован';

?>

<!DOCTYPE html>
<html>

<head>
  <title>EuroTrans | Бронирование и покупка билета</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.min.css">
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

</head>

<body class="page page_inner">
<header class="main-header main-header_booking">
  <div class="main-header__top"><a class="logo main-header__logo" href="/"><img class="logo__image" src="img/logo.png"></a>
    <ul class="breadcrumbs">
      <li class="breadcrumbs__item text text_regular">Выбор</li>
      <li class="breadcrumbs__item breadcrumbs__item_active text text_regular">Оформление</li>
      <li class="breadcrumbs__item text text_regular">Оплата</li>
    </ul>
  </div>
</header>
<main class="page__main">

  <section class="execution" id="app">
    <h1
      class="execution__title execution__title_main text text_regular"><? isset($paymentMessage) ? $paymentMessage : 'Спасибо за покупку' ?></h1>

    <div class="my-selector"></div>
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

