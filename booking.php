<?
require_once $_SERVER['DOCUMENT_ROOT']."/utils/make_cityes.php";
require_once $_SERVER['DOCUMENT_ROOT']."/utils/make_tikets.php";
//echo '<pre>';print_r($arTikets);echo '</pre>';
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
  </div>
  <div class="main-header__booking-form">
    <h1 class="main-header__title text text_regular">
      <?= $fromCity ?> — <?= $toCity ?></h1>
    <form class="booking-form booking-form_page-booking" action="/booking.php" id="booking-form_page-booking"
          v-on:click.capture="removeList">
      <div class="booking-form__container" @click="removeList" id="cityFromFooter">
        <label class="booking-form__label text text_regular" for="from">Откуда</label>
        <input class="booking-form__input booking-form__input_select" id="from" autocomplete="off" name="from"
               v-model="city" v-on:click="showList(1)" required placeholder="<?= $fromCity ?>">
        <ul class="booking-form__cities-list" v-if="isShowList">
            <?foreach ($arCityesFrom as $value):?>
                <li class="booking-form__option booking-form__option_cities text text_regular" v-on:click="setCity"><?=$value->name?></li>
            <?endforeach;?>
        </ul>
      </div>
      <div class="booking-form__container" @click="removeList" id="cityToFooter">
        <label class="booking-form__label text text_regular" for="to">Куда</label>
        <input class="booking-form__input booking-form__input_select" id="to" autocomplete="off" name="to"
               v-model="city" v-on:click="showList(2)" required placeholder="<?= $toCity ?>">
        <ul class="booking-form__cities-list" v-if="isShowList">
            <?foreach ($arCityesTo as $value):?>
                <li class="booking-form__option booking-form__option_cities text text_regular" v-on:click="setCity"><?=$value->name?></li>
            <?endforeach;?>
        </ul>
      </div>
      <div class="booking-form__container">
        <label class="booking-form__label text text_regular" for="dateHeader">Когда</label>
        <input class="booking-form__input booking-form__input_calendar text text_regular" type="text" id="dateHeader" name="date" placeholder="<?= $dateRoutes ?>" autocomplete="off" required>
      </div>
      <div class="booking-form__container" id="passengerFooter">
        <label class="booking-form__label text text_regular" for="passengers">Пассажиры</label>
        <input class="booking-form__input booking-form__input_passengers text text_regular" type="text" id="passengers"
               value="Пассажиры" v-model="getPassenger" v-on:click="showPassengerList" autocomplete="off" required>
        <ul class="booking-form__cities-list booking-form__cities-list_passenger" v-if="isShowList">
          <li
            class="booking-form__option booking-form__option_passengers text text_regular booking-form__option_passenger">
            <p class="booking-form__passenger text text_regular">Взрослые<span
                class="booking-form__container-passenger">
              <button class="booking-form__count-passenger booking-form__count-passenger_minus"
                      v-on:click.prevent="adult -= 1"><span
                  class="visually-hidden">Минус</span></button>
              <input class="booking-form__counter text text_regular" v-model="adult" name="adult">
              <button
                class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active"
                v-on:click.prevent="adult += 1"><span
                  class="visually-hidden">Плюс</span></button></span>
            </p>
          </li>
          <li class="booking-form__option booking-form__option_passengers booking-form__option_passenger">
            <p class="booking-form__passenger text text_regular">Дети<span class="booking-form__container-passenger">
            <button class="booking-form__count-passenger booking-form__count-passenger_minus"
                    v-on:click.prevent="children -= 1"><span
                class="visually-hidden">Минус</span></button>
            <input class="booking-form__counter text text_regular" v-model="children"
                   name="children">
            <button
              class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active"
              v-on:click.prevent="children += 1"><span class="visually-hidden">Плюс</span></button></span>
            </p>
          </li>
        </ul>
      </div>
      <p class="booking-form__container">
        <button class="booking-form__button button button_theme_red text text_regular">Найти билеты</button>
      </p>
    </form>
  </div>
</header>
<main class="page__main">
  <section class="tickets">
    <? if(isset($message)): ?>
      <h2 class="tickets__title text text_regular"><?= $message ?></h2>
    <? else: ?>
      <h2 class="tickets__title text text_regular">Все рейсы</h2>
    <? endif ?>
    <ul class="tickets__list">
      <?foreach ($arTikets->routes as $routes)
          {?>
            <li class="tickets__item ticket">
                <div class="ticket__content">
                    <p class="ticket__time text text_regular"><span class="ticket__time-from"><?= $routes->time_start ?></span>
                        <span class="ticket__date"><?= $routes->date ?></span>
                        <span class="ticket__time-full"><?= $routes->time_in_route ?></span>
                        <span class="ticket__time-to"><?= $routes->time_end ?></span>
                        <span class="ticket__date"><?= $routes->date_end ?></span>
                    </p>
                    <p class="ticket__routes text text_regular">
                        <?foreach ($routes->route as $key => $point)
                        {
                            if ($key == 0):?>
                                <span class="ticket__station-from"><?= $routes->from  ?></span>
                            <?else:?>
                                <span class="ticket__station-from"><?= $point->locality?> Время прибытия:
                                    <?=$point->time_to_station; ?>
                                </span>
                            <?endif;?>
                        <?}?>
                                <span class="ticket__station-from"><?= $routes->to  ?></span>
                    </p>
                </div>
                <div class="ticket__price">
                    <p class="ticket__order text text_regular">
                        <?$price = ($routes->route[0]->price_from->adult * $adult)+($routes->route[0]->price_from->children * $children);?>
                        <?=$price?> р.
                    </p>
                    <a class="ticket__link button button_theme_red text text_regular" href="formation.php?id=<?= $routes->id ?>&adult=<?= $adult ?>&children=<?= $children ?>">
                        Купить билет
                    </a>
                </div>
            </li>
        <?}?>
    </ul>
  </section>
</main>
<footer class="main-footer page__main-footer">
  <section class="main-footer__top">
    <h2 class="visually-hidden">Верхняя секция основного подвала страницы</h2><a class="logo main-footer__logo"
                                                                                 href="/"><img class="logo__image"
                                                                                               src="img/logo.png"></a>
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
<template>
  <section class="feedback-popup">
    <div class="feedback-popup__main-wrapper">
      <h2 class="feedback-popup__title text text_semibold">Оставить отзыв</h2>
      <form class="feedback-popup__form">
        <div class="feedback-popup__wrapper feedback-popup__wrapper_input">
          <p class="feedback-popup__container">
            <input class="feedback-popup__input text text_regular" id="userName" type="text" required>
            <label class="feedback-popup__label text text_regular" for="userName">Ваше имя</label>
          </p>
          <p class="feedback-popup__container">
            <input class="feedback-popup__input text text_regular" id="rideNumber" type="text" required>
            <label class="feedback-popup__label text text_regular" for="rideNumber">Номер рейса</label>
          </p>
          <p class="feedback-popup__container">
            <input class="feedback-popup__input text text_regular" id="phone" type="tel" required>
            <label class="feedback-popup__label text text_regular" for="phone">Номер телефона</label>
          </p>
        </div>
        <div class="feedback-popup__wrapper feedback-popup__wrapper_textarea">
          <textarea class="feedback-popup__input feedback-popup__input_textarea" id="feedback" required></textarea>
          <label class="feedback-popup__label text text_regular" for="feedback">Ваш отзыв</label>
        </div>
        <div class="feedback-popup__wrapper feedback-popup__wrapper_submit">
          <button class="feedback-popup__button button button_theme_red">Оставить отзыв</button>
        </div>
        <div class="feedback-popup__wrapper feedback-popup__wrapper_checkbox">
          <input class="feedback-popup__input feedback-popup__input_checkbox" id="agreement" type="checkbox">
          <label class="feedback-popup__label feedback-popup__label_checkbox text text_semibold" for="agreement">Согласие
            на обработку персональных данных</label>
          <p class="feedback-popup__content text text_regular">
            Я даю свое согласие ООО “ЕВРОТРАНС” на
            обработку моих персональных данных предоставленных
            мной при регистрации на сайте/ оформлении на сайте www.
            ..ru, для их использования (в т.ч. передачу третьим лицам) в
            соответствии с Федеральным законом от 27. 07. 2006 ФЗ-152
            “О защиет персональных данных” в рамках и целях, опреде-
            ленных<a class="feedback__link"> Политикой конфиденциальности</a>и<a class="feedback__link">
              пользовательским соглашением.</a>
          </p>
        </div>
      </form>
    </div>
  </section>
</template>

<script src="js/flatpickr.min.js"></script>
<script>
  flatpickr('#dateHeader', {
    enableTime: false,
    dateFormat: 'd-m-Y',
    time_24hr: true,
    locale: 'ru'
  });
</script>
<script src="js/main.js"></script>
</body>

</html>
