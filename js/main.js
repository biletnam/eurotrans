(function() {
  "use strict";




  var feedback = () => {
    const feedbackButton = document.querySelector(`.feedback__link`);

    var closePopup = document.querySelector(`.feedback-popup__svg`);
    const feedbackTemplate = document
      .querySelector(`template`)
      .content.querySelector(`.feedback-popup`);
    const ESC_KEY_CODE = 27;

    function removePopup() {
      const feedbackContainer = document.querySelector(`.feedback-popup`);
      document.body.removeChild(feedbackContainer);
      document.body.removeEventListener("keydown", pressEscKey);
    }

    function pressEscKey(evt) {
      if (evt.keyCode !== ESC_KEY_CODE) {
        return;
      }
      evt.preventDefault();
      removePopup();
    }

    function clickMouse(evt) {
      if (!evt.target.classList.contains(`feedback-popup`)) {
        return;
      }
      evt.preventDefault();
      removePopup();
    }

    function changeField(evt) {
      console.log(evt.target.type);
      if (evt.target.type === "checkbox") {
        return;
      } else if (evt.target.value.length > 0) {
        evt.target.nextElementSibling.classList.add(
          `feedback-popup__label_close`
        );
      } else
        evt.target.nextElementSibling.classList.remove(
          `feedback-popup__label_close`
        );
    }

    function onBlurInput() {
      const targetInputs = document.body.lastChild.querySelectorAll(
        `.feedback-popup__input`
      );
      targetInputs.forEach(item => item.addEventListener(`blur`, changeField));
    }
    function clickClose(evt) {
      if (!evt.target.classList.contains(`feedback-popup__svg`)) {
        return;
      }
      evt.preventDefault();
      removePopup();
    }

    feedbackButton.addEventListener(`click`, evt => {
      evt.preventDefault();
      document.body.appendChild(feedbackTemplate);
      onBlurInput();

      document.body.addEventListener("keydown", pressEscKey);
      document.body.addEventListener("click", clickMouse);
      document.body.addEventListener("click", clickClose);
    });
  };

  const URL_GET_SEARCH_ROUTE = `https://erp.evrotrans.net/search_reis_v2.php`;
  const PASSENGERS = [`пассажир`, `пассажира`, `пассажиров`];

  const NUM_ENDING = {
    multipleHundred: 100,
    multipleTen: 10,
    greaterThenEleven: 11,
    lessThenNineteen: 19
  };

  const getNumEnding = (iNumber, aEndings) => {
    let sEnding;
    let i;
    iNumber = iNumber % NUM_ENDING.multipleHundred;
    if (
      iNumber >= NUM_ENDING.greaterThenEleven &&
      iNumber <= NUM_ENDING.lessThenNineteen
    ) {
      sEnding = aEndings[2];
    } else {
      i = iNumber % NUM_ENDING.multipleTen;
      switch (i) {
        case 1:
          sEnding = aEndings[0];
          break;
        case 2:
        case 3:
        case 4:
          sEnding = aEndings[1];
          break;
        default:
          sEnding = aEndings[2];
      }
    }
    return sEnding;
  };

  const checkStatus = response => {
    if (response.ok) {
      return response;
    } else {
      throw new Error(`${response.status}: ${response.statusText}`);
    }
  };

  const toJSON = res => {
    return res.json();
  };

  const formState = {
    from: "",
    to: "",
    date: "",
    passenger: 0,
    target: 0
  };

  const citiesListMixin = {
    data() {
      return {
        cities: [],
        isShowList: false,
        city: ""
      };
    },
    methods: {
      showList(target) {
        this.isShowList = true;
        formState.target = target;

        fetch(`${URL_GET_SEARCH_ROUTE}?target=${formState.target}`)
          .then(checkStatus)
          .then(toJSON)
          .then(data => {
            this.cities = data.destination;
          })
          .catch(err => err);
      },
      removeList(evt) {
        if (evt.target.classList.contains(`booking-form__input`)) {
          return;
        } else if (evt.target.classList.contains(`booking-form__cities-list`)) {
          return;
        } else if (
          evt.target.classList.contains(`booking-form__cities-option`)
        ) {
          return;
        } else if (
          evt.target.classList.contains(`booking-form__count-passenger`)
        ) {
          return;
        }

        this.isShowList = false;
      },
      setCity(evt) {
        this.city = evt.target.textContent.trim();
        this.isShowList = false;
      }
    }
  };

  if (document.querySelector(`#main-header__form`)) {
    const fromList = new Vue({
      el: "#cityFromHeader",
      mixins: [citiesListMixin]
    });

    const toList = new Vue({
      el: "#cityToHeader",
      mixins: [citiesListMixin]
    });

    const passenger = new Vue({
      el: "#passengerHeader",
      data() {
        return {
          isShowList: false,
          adult: 0,
          children: 0,
          baby: 0
        };
      },
      methods: {
        adultMinus() {
          this.adult -= 1;
          if (this.adult < 0) {
            this.adult = 0;
          }
        },
        childrenMinus() {
          this.children -= 1;
          if (this.children < 0) {
            this.children = 0;
          }
        },
        babyMinus() {
          this.baby -= 1;
          if (this.baby < 0) {
            this.baby = 0;
          }
        },
        showPassengerList() {
          this.isShowList = true;
        },
        removeList(evt) {
          if (evt.target.classList.contains(`booking-form__input`)) {
            return;
          } else if (
            evt.target.classList.contains(`booking-form__cities-list`)
          ) {
            return;
          } else if (
            evt.target.classList.contains(`booking-form__cities-option`)
          ) {
            return;
          } else if (
            evt.target.classList.contains(`booking-form__count-passenger`)
          ) {
            return;
          }

          this.isShowList = false;
        }
      },
      computed: {
        getPassenger() {
          const passengerCount = this.adult + this.children + this.baby;
          return `${passengerCount} ${getNumEnding(
            passengerCount,
            PASSENGERS
          )}`;
        }
      }
    });
  }

  if (
    document.querySelector(`#booking-form_page-booking`) ||
    document.querySelector(`#booking__form`)
  ) {
    const fromList = new Vue({
      el: "#cityFromFooter",
      mixins: [citiesListMixin]
    });

    const toList = new Vue({
      el: "#cityToFooter",
      mixins: [citiesListMixin]
    });

    const passenger = new Vue({
      el: "#passengerFooter",
      data() {
        return {
          isShowList: false,
          adult: 0,
          children: 0,
          baby: 0
        };
      },
      methods: {
        adultMinus() {
          this.adult -= 1;
          if (this.adult < 0) {
            this.adult = 0;
          }
        },
        childrenMinus() {
          this.children -= 1;
          if (this.children < 0) {
            this.children = 0;
          }
        },
        babyMinus() {
          this.baby -= 1;
          if (this.baby < 0) {
            this.baby = 0;
          }
        },
        showPassengerList() {
          this.isShowList = true;
        },
        removeList(evt) {
          if (evt.target.classList.contains(`booking-form__input`)) {
            return;
          } else if (
            evt.target.classList.contains(`booking-form__cities-list`)
          ) {
            return;
          } else if (
            evt.target.classList.contains(`booking-form__cities-option`)
          ) {
            return;
          } else if (
            evt.target.classList.contains(`booking-form__count-passenger`)
          ) {
            return;
          }

          this.isShowList = false;
        }
      },
      computed: {
        getPassenger() {
          let passengerCount = this.adult + this.children + this.baby;
          return `${passengerCount} ${getNumEnding(
            passengerCount,
            PASSENGERS
          )}`;
        }
      }
    });
  }

  if (window.location.pathname == "/") {
    feedback();
  }
})();



function checkInput(inputText, parentInput) {
  inputText.addEventListener("focus", function(e) {
    this.nextElementSibling.style.display = "block";
  });

  inputText.addEventListener("blur", function(e) {
    this.nextElementSibling.style.display = "none";
  });

  parentInput.onmousedown = function(e) {
    if (document.activeElement === inputText) {
      e.preventDefault();
    }
  };
}

if (document.querySelector("#cityFromHeader")) {
  checkInput(
    document.querySelector("#cityFromHeader .booking-form__input"),
    document.querySelector("#cityFromHeader")
  );
  checkInput(
    document.querySelector("#cityToHeader .booking-form__input"),
    document.querySelector("#cityToHeader")
  );
  checkInput(
    document.querySelector("#passengerHeader .booking-form__input"),
    document.querySelector("#passengerHeader")
  );
}
if (document.querySelector("#cityFromFooter")) {
  checkInput(
    document.querySelector("#cityFromFooter .booking-form__input"),
    document.querySelector("#cityFromFooter")
  );
  checkInput(
    document.querySelector("#cityToFooter .booking-form__input"),
    document.querySelector("#cityToFooter")
  );
  checkInput(
    document.querySelector("#passengerFooter .booking-form__input"),
    document.querySelector("#passengerFooter")
  );
}



// var inputsCountMinus = document.querySelectorAll('.booking-form__counter'),
// 	count, inputCountMinus;

// for (count = 0; count < inputsCountMinus.length; count++) {
// 	inputCountMinus = inputsCountMinus[count];
// 	inputCountMinus.addEventListener('change', changeMinusIs);
// }

// function changeMinusIs(event) {

// console.log(this);

// }

function loopAddRemoveClass(arr ,add, remove) {
	for (var t = 0; t < arr.length; ++t) {
		arr[t].classList.add(add);
		arr[t].classList.remove(remove);
  }
}

function loopRemoveAttributeClass(arr) {
	for (var i = 0; i < arr.length; ++i) {
		arr[i].removeAttribute("style");
  }
}


var swiperInitObj = {
  advantageSlider: false,
  busesSlider: false,
  featuresSlider: false
};


function initSwiper(swiperInitName, wrapper) {

	var wrapps = {
		containerD: "" + wrapper + " .container-desktop",
		wrapperD: "" + wrapper + " .wrapper-desktop",
		slideD: "" + wrapper + " .slide-desktop",
		containerM: "" + wrapper + " .swiper-container",
		wrapperM: "" + wrapper + " .swiper-wrapper",
		slideM: "" + wrapper + " .swiper-slide"
	};

	var containerDekstop = document.querySelectorAll(wrapps.containerD);
	var wrappersDekstop = document.querySelectorAll(wrapps.wrapperD);
	var slidersDekstop = document.querySelectorAll(wrapps.slideD);

	var containers = document.querySelectorAll(wrapps.containerM);
	var wrappers = document.querySelectorAll(wrapps.wrapperM);
	var sliders = document.querySelectorAll(wrapps.slideM);

	var screenWidth = window.innerWidth;
	
	if (screenWidth <= 1200 && swiperInitObj[swiperInitName] == false) {


		loopAddRemoveClass(containerDekstop, "swiper-container", "container-desktop");
		loopAddRemoveClass(wrappersDekstop, "swiper-wrapper", "wrapper-desktop");
		loopAddRemoveClass(slidersDekstop, "swiper-slide", "slide-desktop");


		swiperInitObj[swiperInitName] = new Swiper(wrapps.containerM, {

      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true
			}, 
			breakpoints: {
				1024: {
					spaceBetween: 10
				}
			}
			
		});
		
  } else if (screenWidth > 1200 && swiperInitObj[swiperInitName] != false) {

		swiperInitObj[swiperInitName].destroy();
		swiperInitObj[swiperInitName] = false;
		
		loopAddRemoveClass(containers, "container-desktop", "swiper-container");
		loopAddRemoveClass(wrappers, "wrapper-desktop", "swiper-wrapper");
		loopAddRemoveClass(sliders, "slide-desktop", "swiper-slide");

		loopRemoveAttributeClass(wrappersDekstop);
		loopRemoveAttributeClass(slidersDekstop);
	}
}
//Swiper plugin initialization

initSwiper("advantageSlider", ".advantage");
initSwiper("busesSlider", ".buses");
initSwiper("featuresSlider", ".features");


//Swiper plugin initialization on window resize

window.addEventListener('resize', function () {
	initSwiper("advantageSlider", ".advantage");
	initSwiper("busesSlider", ".buses");
	initSwiper("featuresSlider", ".features");
});






document.querySelector(".feedback__link--write").addEventListener('click', function (e) {
	document.querySelector(".popup-boss").classList.add("popup-boss--active");
	document.querySelector("body").style.overflowY = "hidden";
});

document.querySelector('.popup-boss__close').addEventListener('click', function (e) {
	document.querySelector(".popup-boss").classList.remove("popup-boss--active");
	document.querySelector('body').style.overflowY = "auto";
});

document.querySelector('.popup-boss__mask').addEventListener('click', function (e) {
	document.querySelector(".popup-boss").classList.remove("popup-boss--active");
	document.querySelector('body').style.overflowY = "auto";
});

document.querySelector('.popup-thank__close').addEventListener('click', function (e) {
	document.querySelector(".popup-thank").classList.remove("popup-thank--active");
	document.querySelector('body').style.overflowY = "auto";
});


document.querySelector('.popup-thank__mask').addEventListener('click', function (e) {
	document.querySelector(".popup-thank").classList.remove("popup-thank--active");
	document.querySelector('body').style.overflowY = "auto";
});




var topScroll = document.querySelector(".top-scroll");

window.addEventListener('scroll', function (e) {
	if (document.body.scrollTop >  document.body.clientHeight) {
		topScroll.classList.add('top-scroll_active');
	} else {
		topScroll.classList.remove('top-scroll_active');
	}
});

topScroll.addEventListener("click", function (e) {
	scrollToTop(500);
});

function scrollToTop(scrollDuration) {
	var scrollStep = -window.scrollY / (scrollDuration / 15),
		scrollInterval = setInterval(function () {
			if (window.scrollY != 0) {
				window.scrollBy(0, scrollStep);
			}
			else clearInterval(scrollInterval);
		}, 15);
}