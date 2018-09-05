<?
error_reporting(E_ALL);

ini_set('display_errors', 'on');

require_once 'utils/db-helper.php';
require_once 'utils/main-functions.php';

if (isset($_GET)) {
  $stripGet = array_map('strip_tags', $_GET);
}

$db = connect();

$adult = isset($stripGet['adult']) ? intval($stripGet['adult']) : 0;
$children = isset($stripGet['children']) ? intval($stripGet['children']) : 0;
$baby = isset($stripGet['baby']) ? intval($stripGet['baby']) : 0;
$idRoute = isset($stripGet['id']) ? intval($stripGet['id']) : 0;

$passenger = $adult + $children;

$getRoute = "SELECT `price_do` FROM `ways` WHERE id = ?";
$createTransaction = "INSERT INTO `transaction` (`status`, `amount`, `date_created`,`date_changed`,`transaction_id`,`count_adult`,`count_children`,`count_baby`) VALUES ('created', ?, NOW(), NOW(), NULL, ?, ?, ?)";

$data = getData($db, $getRoute, [$idRoute]);
$data = explode(":", $data[0]['price_do']);

$mainPrice = $data[0];
$minPrice = $data[1];

$price = ($mainPrice * $adult) + ($minPrice * $children);

executeQuery($db, $createTransaction, [$price, $adult, $children, $baby])
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
    <h1 class="execution__title execution__title_main text text_regular">Оформление билета</h1>
    <form class="execution__ticket formation" action="transaction.php" method="POST">
      <? for ($i = 1; $i <= $passenger; $i++): ?>
        <h2 class="formation__title text text_regular">Пассажир — <?= $i ?></h2>
        <div class="formation__wrapper">
          <p class="formation__container">
            <label for="lastName-<?= $i ?>" class="formation__label text text_regular">Фамилия</label>
            <input type="text" id="lastName-<?= $i ?>" name="lastName-<?= $i ?>" placeholder="Иванов"
                   class="formation__input text text_regular" required autocomplete="off">
          </p>

          <p class="formation__container">
            <label for="firstName-<?= $i ?>" class="formation__label text text_regular">Имя</label>
            <input type="text" id="firstName-<?= $i ?>" name="firstName-<?= $i ?>" placeholder="Иван"
                   class="formation__input text text_regular" required autocomplete="off">
          </p>

          <p class="formation__container">
            <label for="middleName-<?= $i ?>" class="formation__label text text_regular">Отчество</label>
            <input type="text" id="middleName-<?= $i ?>" name="middleName-<?= $i ?>" placeholder="Иванович"
                   class="formation__input text text_regular" required autocomplete="off">
          </p>

          <p class="formation__container">
            <label for="birthday-<?= $i ?>" class="formation__label text text_regular">Дата рождения</label>
            <input type="text" id="birthday-<?= $i ?>" name="birthday-<?= $i ?>" placeholder="дд.мм.гггг"
                   class="formation__input formation__input_date text text_regular" required autocomplete="off">
          </p>

          <p class="formation__container formation__container_sex">
            <span class="formation__label_sex">Пол</span>

            <input type="radio" checked id="male-<?= $i ?>" name="sex-<?= $i ?>"
                   class="formation__input formation__input_radio text text_regular">

            <label for="male-<?= $i ?>" class="formation__label formation__label_sex_male text text_regular">Муж</label>
            <input type="radio" id="women-<?= $i ?>" name="sex-<?= $i ?>" class="formation__input formation__input_radio text text_regular">

            <label for="women-<?= $i ?>" class="formation__label formation__label_sex_female text text_regular">Жен</label>

          </p>

          <div class="formation__container formation__container_list" id="nationalityComponent-<?= $i ?>">
            <label for="nationality-<?= $i ?>" class="formation__label text text_regular">Гражданство</label>
            <input id="nationality-<?= $i ?>" name="nationality-<?= $i ?>" placeholder="Выберите страну"
                   class="formation__input text text_regular"
                   v-on:click="showCountry" v-model="country" required autocomplete="off">
            <ul class="formation__list" v-if="isCountryList">
              <li class="formation__item text text_regular" v-for="country in nationalityList" v-on:click="selectCountry">
                {{country}}
              </li>
            </ul>
          </div>

          <div class="formation__container formation__container_list" id="documentsComponent-<?= $i ?>">
            <label for="document-<?= $i ?>" class="formation__label text text_regular">Документ</label>
            <input id="document-<?= $i ?>" name="document-<?= $i ?>" placeholder="Выберите документ"
                   class="formation__input text text_regular"
                   v-on:click="showDocuments" v-model="document" required autocomplete="off">

            <ul class="formation__list" v-if="showDocumentList">
              <li class="formation__item text text_regular" v-for="document in documentsList"
                  v-on:click="selectDocuments">
                {{document}}
              </li>
            </ul>

          </div>

          <p class="formation__container">
            <label for="numberDocument-<?= $i ?>" class="formation__label text text_regular">Номер документа</label>
            <input type="text" id="numberDocument-<?= $i ?>" name="numberDocument-<?= $i ?>" placeholder="Номер документа"
                   class="formation__input text text_regular" required autocomplete="off">
          </p>
        </div>


        <h2 class="formation__title text text_regular">Контактные данные</h2>

        <div class="formation__wrapper formation__wrapper_contacts">

          <p class="formation__container">
            <label for="emailUser-<?= $i ?>" class="formation__label text text_regular">E-mail</label>
            <input type="email" id="emailUser-<?= $i ?>" name="emailUser-<?= $i ?>" placeholder="ivanov@mail.ru"
                   class="formation__input text text_regular" required autocomplete="off">
          </p>

          <p class="formation__container">
            <label for="phoneUser-<?= $i ?>" class="formation__label text text_regular">Телефон</label>
            <input type="tel" id="phoneUser-<?= $i ?>" name="phoneUser-<?= $i ?>" placeholder="ivanov@mail.ru"
                   class="formation__input text text_regular" required autocomplete="off">
          </p>
        </div>

      <? endfor; ?>
      <div class="formation__wrapper formation__wrapper_submit">

        <p class="formation__container formation__container_checkbox">
          <label for="agreement" class="formation__label formation__label_checkbox feedback-popup__label_checkbox text text_semibold">Согласие на обработку персональных
            данных</label>
          <input type="checkbox" id="agreement" name="emailUser" placeholder="ivanov@mail.ru"
                 class="formation__input feedback-popup__input_checkbox text text_regular">
          <span class="formation__content formation__content_agreement feedback-popup__content text text_regular">
            Я даю свое согласие ООО “ЕВРОТРАНС” на обработку
            моих персональных данных предоставленных мной при регистрации
            на сайте/оформлении на сайте www...ru, для их использования (в т.ч.
            передачу третьим лицам) всоответствии с Федеральным законом от
            27. 07. 2006 ФЗ-152“О защиет персональных данных” в рамках
            и целях, опреде-ленных Политикой конфиденциальности
            и пользовательским соглашением.
          </span>
        </p>

        <p class="formation__container">
          <span class="formation__additional formation__additional_title text text_semibold">Дополнительно</span>
          <span class="formation__additional text text_regular">Для посадки необходим паспорт</span>
          <span class="formation__additional text text_regular">Для посадки необходим распечатанный билет</span>
          <span class="formation__additional text text_regular">Регулярный рейс</span>
        </p>

        <p class="formation__container formation__container_button">
          <span class="formation__route text text_regular">Ставрополь - Москва</span>
          <span class="formation__price text text_regular"><?= isset($price) ? $price : 0 ?> р.</span>
          <button class="formation__button text text_regular button button_theme_red text text_regular" name="pay" value="1" id="payButtom" v-on:click.prevent="sendData">Оплатить билет</button>
        </p>
      </div>
    </form>
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

<script src="js/flatpickr.min.js"></script>
<script>
  flatpickr('#birthday', {
    enableTime: false,
    dateFormat: 'd-m-Y',
    time_24hr: true,
    locale: 'ru'
  })
</script>
<script src="js/formation/formation.js"></script>
</body>

</html>
