<?require_once $_SERVER['DOCUMENT_ROOT']."/utils/make_cityes.php";?>
<!DOCTYPE html>
<html>
<head>
    <title>EuroTrans - заказ и бронирование автобсу</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta charset="utf-8">
		<link rel="stylesheet" href="css/style.css">
		<!-- <link rel="stylesheet" href="libs/swiper/swiper.min.css"> -->
		
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>

<body class="page">
    <header class="main-header">
        <div class="main-header__top"><a class="logo main-header__logo" href="/"><img class="logo__image" src="img/header-logo.png"></a><a class="contacts main-header__contacts" href="tel:8800121212"><span class="contacts__content text text_regular contacts__content_header">Звонок по России бесплатный</span><span class="contacts__phone text text_semibold contacts__content_header">8-800-123-12-12</span></a>
        </div>
        <section class="promo main-header__promo">
            <h1 class="visually-hidden">Билетb на автобусы</h1>
            <h2 class="promo__title text text_extrabold">Дешевые билеты<span class="promo__full-stroke"> на автобус от перевозчика</span></h2>
            <form class="booking-form main-header__form" action="/booking.php" id="main-header__form" v-on:click.capture="removeList">
                <div class="booking-form__container" @click="removeList" id="cityFromHeader">
                    <label class="booking-form__label text text_regular" for="from">Откуда</label>
                    <input class="booking-form__input booking-form__input_select" id="from" autocomplete="off" name="from" v-model="city" v-on:click="showList(1)" placeholder="Город отправления" required>
                    <ul class="booking-form__cities-list" v-if="isShowList">
                        <?foreach ($arCityesFrom as $value):?>
                            <li class="booking-form__option booking-form__option_cities text text_regular" v-on:click="setCity"><?=$value->name?></li>
                        <?endforeach;?>
                    </ul>
                </div>
                <div class="booking-form__container" @click="removeList" id="cityToHeader">
                    <label class="booking-form__label text text_regular" for="to">Куда</label>
                    <input class="booking-form__input booking-form__input_select" id="to" autocomplete="off" name="to" v-model="city" v-on:click="showList(2)" required placeholder="Город прибытия">
                    <ul class="booking-form__cities-list" v-if="isShowList">
                        <?foreach ($arCityesTo as $value):?>
                            <li class="booking-form__option booking-form__option_cities text text_regular" v-on:click="setCity"><?=$value->name?></li>
                        <?endforeach;?>
                    </ul>
                </div>
                <div class="booking-form__container">
                    <label class="booking-form__label text text_regular" for="date">Когда</label>
                    <input class="booking-form__input booking-form__input_calendar text text_regular" type="text" id="dateHeader" name="date" placeholder="дд.мм.гггг" autocomplete="off" required>
                </div>
                <div class="booking-form__container" id="passengerHeader">
                    <label class="booking-form__label text text_regular" for="passengers">Пассажиры</label>
                    <input class="booking-form__input booking-form__input_passengers text text_regular" type="text" id="passengers" v-model="getPassenger" v-on:click="showPassengerList" autocomplete="off" required>
                    <ul class="booking-form__cities-list booking-form__cities-list_passenger" v-if="isShowList">
                        <li class="booking-form__option booking-form__option_passengers text text_regular booking-form__option_passenger">
                            <p class="booking-form__passenger text text_regular">Взрослые<span class="booking-form__container-passenger">
                                      <button class="booking-form__count-passenger booking-form__count-passenger_minus" v-on:click.prevent="adult -= 1"><span class="visually-hidden">Минус</span></button>
                                <input class="booking-form__counter text text_regular" v-model="adult" id="adult" name="adult" required autocomplete="off" value="0">
                                <button class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active" v-on:click.prevent="adult += 1"><span class="visually-hidden">Плюс</span></button></span>
                            </p>
                        </li>
                        <li class="booking-form__option booking-form__option_passengers booking-form__option_passenger">
                            <p class="booking-form__passenger text text_regular">Дети<span class="booking-form__container-passenger">
                                      <button class="booking-form__count-passenger booking-form__count-passenger_minus" v-on:click.prevent="children -= 1"><span class="visually-hidden">Минус</span></button>
                                <input class="booking-form__counter text text_regular" v-model="children" name="children" required autocomplete="off" value="0">
                                <button class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active" v-on:click.prevent="children += 1"><span class="visually-hidden">Плюс</span></button></span>
                            </p>
                        </li>
                    </ul>
                </div>
                <p class="booking-form__container">
                    <button class="booking-form__button button button_theme_red text text_regular">Найти билеты</button>
                </p>
            </form>
        </section>
    </header>
    <main class="page__main">
        <section class="routes page__routes">
            <h1 class="routes__title text text_semibold">Популярные направления</h1>
            <ul class="routes__list">
                <li class="routes__item">
                    <article class="route routes__article" style='background-image: url("../img/routes-bg.jpg");'>
                        <h2 class="route__title text text_semibold">Ставрополь - Москва</h2>
                        <p class="route__sending text text_regular">Отправление</p>
                        <ul class="route__list">
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">09:00</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">12:30</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">17:00</span></li>
                        </ul>
                        <p class="route__price text text_semibold">От 2260р.</p><a class="route__booking button button_theme_blue text text_semibold" href="booking.html">Забронировать</a>
                    </article>
                </li>
                <li class="routes__item">
                    <article class="route routes__article" style='background-image: url("../img/stavropol.jpg");'>
                        <h2 class="route__title text text_semibold">Москва - Ставрополь</h2>
                        <p class="route__sending text text_regular">Отправление</p>
                        <ul class="route__list">
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">09:00</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">13:30</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">20:00</span></li>
                        </ul>
                        <p class="route__price text text_semibold">От 2260р.</p><a class="route__booking button button_theme_blue text text_semibold" href="booking.html">Забронировать</a>
                    </article>
                </li>
                <li class="routes__item">
                    <article class="route routes__article" style='background-image: url("../img/routes-bg.jpg");'>
                        <h2 class="route__title text text_semibold">Нефтекумск - Москва</h2>
                        <p class="route__sending text text_regular">Отправление</p>
                        <ul class="route__list">
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">09:00</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">16:30</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">18:00</span></li>
                        </ul>
                        <p class="route__price text text_semibold">От 2260р.</p><a class="route__booking button button_theme_blue text text_semibold" href="booking.html">Забронировать</a>
                    </article>
                </li>
                <li class="routes__item">
                    <article class="route routes__article" style='background-image: url("../img/Neftekumsk.jpg");'>
                        <h2 class="route__title text text_semibold">Москва - Нефтекумск</h2>
                        <p class="route__sending text text_regular">Отправление</p>
                        <ul class="route__list">
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">09:00</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">12:30</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">17:00</span></li>
                        </ul>
                        <p class="route__price text text_semibold">От 2260р.</p><a class="route__booking button button_theme_blue text text_semibold" href="booking.html">Забронировать</a>
                    </article>
                </li>
                <li class="routes__item">
                    <article class="route routes__article" style='background-image: url("../img/Budenovsk.jpg");'>
                        <h2 class="route__title text text_semibold">Будденовск - Москва</h2>
                        <p class="route__sending text text_regular">Отправление</p>
                        <ul class="route__list">
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">09:00</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">13:30</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">20:00</span></li>
                        </ul>
                        <p class="route__price text text_semibold">От 2260р.</p><a class="route__booking button button_theme_blue text text_semibold" href="booking.html">Забронировать</a>
                    </article>
                </li>
                <li class="routes__item">
                    <article class="route routes__article" style='background-image: url("../img/Levokum.jpg");'>
                        <h2 class="route__title text text_semibold">Левокумское - Москва</h2>
                        <p class="route__sending text text_regular">Отправление</p>
                        <ul class="route__list">
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">09:00</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">16:30</span></li>
                            <li class="route__item text text_regular"><span class="route__date">08.06 |</span><span class="route__time">18:00</span></li>
                        </ul>
                        <p class="route__price text text_semibold">От 2260р.</p><a class="route__booking button button_theme_blue text text_semibold" href="booking.html">Забронировать</a>
                    </article>
                </li>
            </ul>
        </section>
        <section class="advantage page__advantage">
						<h1 class="advantage__title text text_semibold">Преимущество бронирования у нас</h1>
												<!-- Slider main container -->

            
							<div class="swiper-container">
									<!-- Additional required wrapper -->
									<div class="swiper-wrapper">
											<!-- Slides -->
											<ul class="advantage__list">
										<div class="swiper-slide">
											<li class="advantage__item advantage__item_insurance">
												<p class="advantage__subtitle text text_semibold">Все пассажиры застрахованны</p>
												<p class="advantage__content text text_regular">В стоимость билета включено страхование.</p>
											</li>
										</div>
										<div class="swiper-slide">
											<li class="advantage__item advantage__item_safety">
													<p class="advantage__subtitle text text_semibold">Безопасный поездки</p>
													<p class="advantage__content text text_regular">Наши автобусы проходят технический осмотр, а водители медицинскую проверку перед каждой поездкой. На случай непредвиденных ситуаций у нас всегда есть дежурный водитель и резервный автобус.</p>
											</li>
										</div>
										<div class="swiper-slide">
											<li class="advantage__item advantage__item_driver">
													<p class="advantage__subtitle text text_semibold">Опытные водители</p>
													<p class="advantage__content text text_regular">Вас будет перевозить водитель со стажем пассажирских перевозок не менее 10 лет.</p>
											</li>
										</div>
										<div class="swiper-slide">
											<li class="advantage__item advantage__item_card-payment">
													<p class="advantage__subtitle text text_semibold">Безопасная олата картой</p>
													<p class="advantage__content text text_regular">Мы работаем только с надежными системами приема платежей, которые шифруют данные и регулярно проходят проверку безопасности по стандартам Visa и Mastercard.</p>
											</li>
										</div>
										<div class="swiper-slide">
											<li class="advantage__item advantage__item_station">
													<p class="advantage__subtitle text text_semibold">Остановки каждые 4 часа для обеда и отдыха</p>
													<p class="advantage__content text text_regular">Ваш маршрут предусматривает необходимые остановки в местах, где можно можно легко перекусить или плотно пообедать домашней едой, а также пройтись и размяться.</p>
											</li>
										</div>
											</ul>
								</div>
								<!-- If we need pagination -->
								<div class="swiper-pagination"></div>
					
						
																

						</div>
            <div class="control advantage__control">
                <ul class="control__list">
                    <li class="control__item control__item_active"><span class="visually-hidden">0</span></li>
                    <li class="control__item"><span class="visually-hidden">1</span></li>
                    <li class="control__item"><span class="visually-hidden">2</span></li>
                    <li class="control__item"><span class="visually-hidden">3</span></li>
                    <li class="control__item"><span class="visually-hidden">4</span></li>
                </ul>
            </div>
        </section>
        <section class="feedback page__feedback">
            <div class="feedback__wrapper">
                <div class="feedback__container">
										<h1 class="feedback__title text text_semibold">Контолируем качество перевозок</h1>
										<img src="img/gen-dir.jpg" class='feedback__img-dir' alt="">
                    <p class="feedback__content text text_regular">
												Максим Яцунов, заместитель генерального директора.
												<span class='feedback__content--quote'> Помогите нам стать лучше. Если у вас есть пожелания, рекомендации или претензии относительно оказанных услуг, пожалуйста, направьте их лично мне</span>
											
										</p>
								</div>
								<a class="button button_theme_red text text_regular feedback__link--write">Написать руководителю</a>
								<input type="hidden" class='feedback__link '>
            </div>
        </section>
        <section class="buses page__buses">
            <header class="buses__header">
                <h2 class="buses__title text text_semibold">Комфортабельные автобусы</h2>
                <p class="buses__header-description text text_regular">все для удобства вашей поездки</p>
            </header>
            <ul class="buses__list">
                <li class="buses__item">
                    <picture class="buses__picture">
                        <source type="image/webp" srcset="img/mercedes-mobile.webp"><img class="buses__image" src="img/mercedes-mobile.jpg" alt="Mercedes Sprinter">
                    </picture>
                    <div class="buses__container">
                        <p class="buses__model text text_semibold">Mercedes Sprinter
                            <ul class="buses__features-list">
                                <li class="buses__features-item text text_regular">Комфортные сидения</li>
                                <li class="buses__features-item text text_regular"> Откидывающиеся сидения</li>
                                <li class="buses__features-item text text_regular">Индивидуальный кондиционер</li>
                            </ul>
                        </p>
                    </div>
                </li>
                <li class="buses__item">
                    <picture class="buses__picture">
                        <source type="image/webp" srcset="img/hyundai-mobile.webp"><img class="buses__image" src="img/hyundai-mobile.jpg" alt="Hyundai">
                    </picture>
                    <div class="buses__container">
                        <p class="buses__model text text_semibold">Hyundai
                            <ul class="buses__features-list">
                                <li class="buses__features-item text text_regular">Комфортные сидения</li>
                                <li class="buses__features-item text text_regular"> Откидывающиеся сидения</li>
                                <li class="buses__features-item text text_regular">Индивидуальный кондиционер</li>
                                <li class="buses__features-item text text_regular">Микрофон для громкой связи с пассажирами</li>
                            </ul>
                        </p>
                    </div>
                </li>
                <li class="buses__item">
                    <picture class="buses__picture">
                        <source type="image/webp" srcset="img/1-stage-mobile.webp"><img class="buses__image" src="img/1-stage-mobile.jpg" alt="1,5 этажные автобусы">
                    </picture>
                    <div class="buses__container">
                        <p class="buses__model text text_semibold">1,5 этажные автобусы
                            <ul class="buses__features-list">
                                <li class="buses__features-item text text_regular">Комфортные сидения</li>
                                <li class="buses__features-item text text_regular"> Откидывающиеся сидения</li>
                                <li class="buses__features-item text text_regular">Индивидуальный кондиционер</li>
                                <li class="buses__features-item text text_regular">Микрофон для громкой связи с пассажирами</li>
                                <li class="buses__features-item text text_regular">Туалет</li>
                                <li class="buses__features-item text text_regular">Мини-кухня</li>
                            </ul>
                        </p>
                    </div>
                </li>
                <li class="buses__item">
                    <picture class="buses__picture">
                        <source type="image/webp" srcset="img/2-stage-mobile.webp"><img class="buses__image" src="img/2-stage-mobile.jpg" alt="2 этажные автобусы">
                    </picture>
                    <div class="buses__container">
                        <p class="buses__model text text_semibold">2 этажные автобусы
                            <ul class="buses__features-list">
                                <li class="buses__features-item text text_regular">Комфортные сидения</li>
                                <li class="buses__features-item text text_regular"> Откидывающиеся сидения</li>
                                <li class="buses__features-item text text_regular">Индивидуальный кондиционер</li>
                                <li class="buses__features-item text text_regular">Микрофон для громкой связи с пассажирами</li>
                                <li class="buses__features-item text text_regular">Туалет</li>
                                <li class="buses__features-item text text_regular">Мини-кухня</li>
                            </ul>
                        </p>
                    </div>
                </li>
            </ul>
            <div class="control buses__control">
                <ul class="control__list">
                    <li class="control__item control__item_active"><span class="visually-hidden">0</span></li>
                    <li class="control__item"><span class="visually-hidden">1</span></li>
                    <li class="control__item"><span class="visually-hidden">2</span></li>
                    <li class="control__item"><span class="visually-hidden">3</span></li>
                </ul>
            </div>
        </section>
        <!--include blocks/route-map/route-map-->
        <section class="features page__features">
            <h2 class="features__title text text_semibold">Исправные и чистые автобусы</h2>
						<div class="ready__items">
							<div class="ready__item">
								<img src="img/features-1.jpg" alt="EuroTrans" class="ready__item-img">
								<h3 class="ready__item-title">Отправляем автобусы на ТО перед каждой поездкой.</h3>
								<p class="ready__item-text">Проверяем транспорт на собственной станции техосмотра. В случае выявления неисправности, на рейс выходит резервный автобус.</p>
							</div>
							<div class="ready__item">
								<img src="img/features-2.jpg" alt="EuroTrans" class="ready__item-img">
								<h3 class="ready__item-title">Проводим медосмотр водителей перед рейсом. </h3>
								<p class="ready__item-text">Если наш мед.персонал выявляет отклонения от нормы в самочувствии водителя, его заменяет коллега.</p>
							</div>
							<div class="ready__item">
								<img src="img/features-3.jpg" alt="EuroTrans" class="ready__item-img">
								<h3 class="ready__item-title">Выводим транспорт на маршрут только после тщательной уборки. </h3>
								<p class="ready__item-text">Моем и пылесосим автобусы до и после рейсов.</p>
							</div>
						</div>

            <div class="control features__control">
                <ul class="control__list">
                    <li class="control__item control__item_active"><span class="visually-hidden">0</span></li>
                    <li class="control__item"><span class="visually-hidden">1</span></li>
                    <li class="control__item"><span class="visually-hidden">2</span></li>
                </ul>
            </div>
        </section>
        <section class="booking page__booking">
            <header class="booking__header">
                <h2 class="booking__title text text_semibold">Забронируй и оплатите билет сейчас</h2>
            </header>
            <section class="booking__container">
                <h3 class="visually-hidden"></h3>
                <form class="booking-form booking__form" action="/booking.php" id="booking__form" v-on:click.capture="removeList">
                    <div class="booking-form__container booking-form__container_footer" @click="removeList" id="cityFromFooter">
                        <label class="booking-form__label text text_regular booking-form__label_footer" for="from">Откуда</label>
                        <input class="booking-form__input booking-form__input_select" id="from" autocomplete="off" name="from" v-model="city" v-on:click="showList(1)" placeholder="Город отправления" required>
                        <ul class="booking-form__cities-list" v-if="isShowList">
                            <li class="booking-form__option booking-form__option_cities text text_regular" v-for="city in cities" v-on:click="setCity">{{city.name}}</li>
                        </ul>
                    </div>
                    <div class="booking-form__container booking-form__container_footer" @click="removeList" id="cityToFooter">
                        <label class="booking-form__label text text_regular booking-form__label_footer" for="to">Куда</label>
                        <input class="booking-form__input booking-form__input_select" id="to" autocomplete="off" name="to" v-model="city" v-on:click="showList(2)" required placeholder="Город прибытия">
                        <ul class="booking-form__cities-list" v-if="isShowList">
                            <li class="booking-form__option booking-form__option_cities text text_regular" v-for="city in cities" v-on:click="setCity">{{city.name}}</li>
                        </ul>
                    </div>
                    <div class="booking-form__container booking-form__container_footer">
                        <label class="booking-form__label text text_regular booking-form__label_footer" for="date">Когда</label>
                        <input class="booking-form__input booking-form__input_calendar text text_regular" type="text" id="dateFooter" name="date" placeholder="дд.мм.гггг" autocomplete="off" required>
                    </div>
                    <div class="booking-form__container booking-form__container_footer" id="passengerFooter">
                        <label class="booking-form__label text text_regular booking-form__label_footer" for="passengers">Пассажиры</label>
                        <input class="booking-form__input booking-form__input_passengers text text_regular booking-form__input_footer" type="text" id="passengers" v-model="getPassenger" v-on:click="showPassengerList" autocomplete="off" required>
                        <ul class="booking-form__cities-list booking-form__cities-list_passenger" v-if="isShowList">
                            <li class="booking-form__option booking-form__option_passengers text text_regular booking-form__option_passenger">
                                <p class="booking-form__passenger text text_regular">Взрослые<span class="booking-form__container-passenger">
                                      <button class="booking-form__count-passenger booking-form__count-passenger_minus" v-on:click.prevent="adult -= 1"><span class="visually-hidden">Минус</span></button>
                                    <input class="booking-form__counter text text_regular" v-model="adult" id="adult" name="adult" required autocomplete="off" value="0">
                                    <button class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active" v-on:click.prevent="adult += 1"><span class="visually-hidden">Плюс</span></button></span>
                                </p>
                            </li>
                            <li class="booking-form__option booking-form__option_passengers booking-form__option_passenger">
                                <p class="booking-form__passenger text text_regular">Дети<span class="booking-form__container-passenger">
                                      <button class="booking-form__count-passenger booking-form__count-passenger_minus" v-on:click.prevent="children -= 1"><span class="visually-hidden">Минус</span></button>
                                    <input class="booking-form__counter text text_regular" v-model="children" name="children" required autocomplete="off" value="0">
                                    <button class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active" v-on:click.prevent="children += 1"><span class="visually-hidden">Плюс</span></button></span>
                                </p>
                            </li>
                            <li class="booking-form__option booking-form__option_passengers text text_regular booking-form__option_passenger">
                                <p class="booking-form__passenger text text_regular">Младенцы<span class="booking-form__container-passenger">
                                      <button class="booking-form__count-passenger booking-form__count-passenger_minus" v-on:click.prevent="baby -= 1"><span class="visually-hidden">Минус</span></button>
                                    <input class="booking-form__counter text text_regular" v-model="baby" name="baby" required autocomplete="off" value="0">
                                    <button class="booking-form__count-passenger booking-form__count-passenger_plus booking-form__count-passenger_active" v-on:click.prevent="baby += 1"><span class="visually-hidden">Плюс</span></button></span>
                                </p>
                            </li>
                        </ul>
                    </div>
                    <p class="booking-form__container booking-form__container_footer">
                        <button class="booking-form__button button button_theme_red text text_regular">Найти билеты</button>
                    </p>
                </form>
            </section>
            <!-- <section class="booking__container">
                <h3 class="booking__subtitle text text_semibold">Наши акции</h3>
                <ul class="booking__list">
                    <li class="booking__item">
                        <p class="booking__description text text_semibold">Скидка 500 рублей на первую поездку</p>
                    </li>
                    <li class="booking__item">
                        <p class="booking__description text text_semibold">-50% с билета на детский билет</p>
                    </li>
                </ul>
                <div class="control booking__control">
                    <ul class="control__list">
                        <li class="control__item control__item_active"><span class="visually-hidden">0</span></li>
                        <li class="control__item"><span class="visually-hidden">1</span></li>
                    </ul>
                </div>
            </section> -->
        </section>
    </main>
    <footer class="main-footer page__main-footer">
        <section class="main-footer__top">
						<h2 class="visually-hidden">Верхняя секция основного подвала страницы</h2>
						<a class="logo main-footer__logo" href="/">
							<img class="logo__image" src="img/logo.png">
						</a>
            <section class="additional-menu main-footer__company">
                <h3 class="additional-menu__title text text_semibold">O компании</h3>
                <ul class="additional-menu__list">
                    <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">О нас</a></li>
                    <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Договор оферты</a></li>
                    <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Политика конфиденциальности</a></li>
                    <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Контакты</a></li>
                </ul>
            </section>
            <section class="additional-menu main-footer__company">
                <h3 class="additional-menu__title text text_semibold">Пользователям</h3>
                <ul class="additional-menu__list">
                    <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Вопросы и ответы</a></li>
                    <li class="additional-menu__item"><a class="additional-menu__link text text_regular" href="">Купить билеты</a></li>
                </ul>
            </section><a class="contacts contacts_footer main-footer__contacts" href="tel:8800121212"><span class="contacts__content text text_regular">Наш номер телефона</span><span class="contacts__phone text text_semibold">8-800-123-12-12</span></a>
        </section>
        <section class="main-footer__copyright">
            <h2 class="visually-hidden">Секция с копирайтами</h2>
            <p class="main-footer__copy text text_regular">ИП Яцунов М.С.</p><a class="main-footer__copy-link text text_regular" href="http://www.mindsell.ru">MindSell - разработка сайта</a>
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
              <label class="feedback-popup__label feedback-popup__label_checkbox text text_semibold" for="agreement">Согласие на обработку персональных данных</label>
              <p class="feedback-popup__content text text_regular">
                Я даю свое согласие ООО “ЕВРОТРАНС” на
                обработку моих персональных данных предоставленных
                 мной при регистрации на сайте/ оформлении на сайте www.
                ..ru, для их использования (в т.ч. передачу третьим лицам) в
                соответствии с Федеральным законом от 27. 07. 2006 ФЗ-152
                “О защиет персональных данных” в рамках и целях, опреде-
                ленных<a class="feedback__link"> Политикой конфиденциальности</a>и<a class="feedback__link"> пользовательским соглашением.</a>
              </p>
            </div>
          </form>
        </div>
      </section>
    </template>

	<div class="popup-boss" >
		<div class="popup-boss__mask"></div>
		<div class="popup-boss__window">
			<div class="popup-boss__close"></div>
			<form id="order-popup-boss" class="popup-boss__form-order" method="POST" onsubmit='return false;'>
				<input class='popup-boss__form-inf' type="text" name="name" placeholder="Ваше имя" required>
				<input class='popup-boss__form-inf' type="text" name="phone" placeholder="Ваш телефон" required>
				<input class='popup-boss__form-inf' type="email" name="email" placeholder="Ваш email" required>
				<textarea name="text" class='popup-boss__form-inf popup-boss__form-inf--textarea' placeholder='Текст обращения'></textarea>
				<input class='popup-boss__button' type="submit" value="Отправить">
			</form>
		</div>
	</div>

	<div class="popup-thank">
		<div class="popup-thank__mask"></div>
		<div class="popup-thank__window">
			<div class="popup-thank__close"></div>
			<img src="img/gen-dir.jpg" alt="EuroTrans" class="popup-thank__img">
			<p class="popup-thank__text">Спасибо, что помогаете нам стать лучше. Я лично рассмотрю ваше обращение и отвечу вам на него в течение трех дней.</p>
		</div>
	</div>



    <script src="js/flatpickr.min.js"></script>
    <script>
        flatpickr('#dateHeader', {
            enableTime: false,
            dateFormat: 'd-m-Y',
            time_24hr: true,
            locale: 'ru'
        });
        flatpickr('#dateFooter', {
            enableTime: false,
            dateFormat: 'd-m-Y',
            time_24hr: true,
            locale: 'ru'
        });
		</script>
		
		<!-- <script src="libs/swiper/swiper.min.js"></script> -->

		<script src="js/main.js"></script>

</body>

</html>