(function () {
  'use strict';

  var feedback = () => {
    const feedbackButton = document.querySelector(`.feedback__link`);
    const feedbackTemplate = document.querySelector(`template`).content.querySelector(`.feedback-popup`);
    const ESC_KEY_CODE = 27;

    function removePopup () {
      const feedbackContainer = document.querySelector(`.feedback-popup`);
      document.body.removeChild(feedbackContainer);
      document.body.removeEventListener('keydown', pressEscKey);
    }

    function pressEscKey (evt) {
      if (evt.keyCode !== ESC_KEY_CODE) {
        return
      }
      evt.preventDefault();
      removePopup();
    }

    function clickMouse (evt) {
      if (!evt.target.classList.contains(`feedback-popup`)) {
        return
      }
      evt.preventDefault();
      removePopup();
    }

    function changeField (evt) {
      console.log(evt.target.type);
      if (evt.target.type === 'checkbox') {
        return
      } else if (evt.target.value.length > 0) {
        evt.target.nextElementSibling.classList.add(`feedback-popup__label_close`);
      } else (
        evt.target.nextElementSibling.classList.remove(`feedback-popup__label_close`)
      );
    }

    function onBlurInput () {
      const targetInputs = document.body.lastChild.querySelectorAll(`.feedback-popup__input`);
      targetInputs.forEach(item => item.addEventListener(`blur`, changeField));
    }

    feedbackButton.addEventListener(`click`, (evt) => {
      evt.preventDefault();
      document.body.appendChild(feedbackTemplate);
      onBlurInput();

      document.body.addEventListener('keydown', pressEscKey);
      document.body.addEventListener('click', clickMouse);
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
    if (iNumber >= NUM_ENDING.greaterThenEleven && iNumber <= NUM_ENDING.lessThenNineteen) {
      sEnding = aEndings[2];
    } else {
      i = iNumber % NUM_ENDING.multipleTen;
      switch (i) {
        case (1):
          sEnding = aEndings[0];
          break;
        case (2):
        case (3):
        case (4):
          sEnding = aEndings[1];
          break;
        default:
          sEnding = aEndings[2];
      }
    }
    return sEnding;
  };

  const checkStatus = (response) => {
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
    from: '',
    to: '',
    date: '',
    passenger: 0,
    target: 0
  };

  const citiesListMixin = {
    data() {
      return {
        cities: [],
        isShowList: false,
        city: ''
      }
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
        if (evt.target.classList.contains(`booking-form__input`)){
          return
        } else if(evt.target.classList.contains(`booking-form__cities-list`)) {
          return
        } else if(evt.target.classList.contains(`booking-form__cities-option`)) {
          return
        } else if (evt.target.classList.contains(`booking-form__count-passenger`)) {
          return
        }

        this.isShowList = false;
      },
      setCity(evt) {
        this.city = evt.target.textContent.trim();
        this.isShowList = false;
      },
    }
  };


  if (document.querySelector(`#main-header__form`)) {
    const fromList = new Vue({
      el: '#cityFromHeader',
      mixins: [citiesListMixin]
    });

    const toList = new Vue({
      el: '#cityToHeader',
      mixins: [citiesListMixin]
    });

    const passenger = new Vue({
      el: '#passengerHeader',
      data() {
        return {
          isShowList: false,
          adult: 0,
          children: 0,
          baby: 0,
        }
      },
      methods: {
        showPassengerList() {
          this.isShowList = true;
        },
        removeList(evt) {
          if (evt.target.classList.contains(`booking-form__input`)){
            return
          } else if(evt.target.classList.contains(`booking-form__cities-list`)) {
            return
          } else if(evt.target.classList.contains(`booking-form__cities-option`)) {
            return
          } else if (evt.target.classList.contains(`booking-form__count-passenger`)) {
            return
          }

          this.isShowList = false;
        }
      },
      computed: {
        getPassenger() {
          const passengerCount = this.adult + this.children + this.baby;
          return `${passengerCount} ${getNumEnding(passengerCount, PASSENGERS)}`
        }
      }
    });
  }

  if (document.querySelector(`#booking-form_page-booking`) || document.querySelector(`#booking__form`)) {
    const fromList = new Vue({
      el: '#cityFromFooter',
      mixins: [citiesListMixin]
    });

    const toList = new Vue({
      el: '#cityToFooter',
      mixins: [citiesListMixin]
    });

    const passenger = new Vue({
      el: '#passengerFooter',
      data() {
        return {
          isShowList: false,
          adult: 0,
          children: 0,
          baby: 0,
        }
      },
      methods: {
        showPassengerList() {
          this.isShowList = true;
        },
        removeList(evt) {
          if (evt.target.classList.contains(`booking-form__input`)){
            return
          } else if(evt.target.classList.contains(`booking-form__cities-list`)) {
            return
          } else if(evt.target.classList.contains(`booking-form__cities-option`)) {
            return
          } else if (evt.target.classList.contains(`booking-form__count-passenger`)) {
            return
          }

          this.isShowList = false;
        }
      },
      computed: {
        getPassenger() {
          let passengerCount = this.adult + this.children + this.baby;
          return `${passengerCount} ${getNumEnding(passengerCount, PASSENGERS)}`
        }
      }
    });
  }

  if (window.location.pathname == '/') {
    feedback();
  }

}());
