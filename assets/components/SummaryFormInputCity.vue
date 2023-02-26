<template>
  <div class="mb-3">
    <label v-bind:for="'summaryForm-' + name" class="mb-2">{{ label }}</label>
    <div class="field">
      <input v-bind:type="type" v-model="inputValue" v-bind:id="'summaryForm-' + name"
             class="form-control form-control-sm" v-bind:placeholder="placeholder">
      <div v-if="help" class="form-text mt-2">{{ help }}</div>
      <ul v-show="vkData.cities.length > 0" class="field__choices list-group">
        <li v-for="city in vkData.cities" :key="city.id"
            @click="selectCity(city.id)"
            class="list-group-item list-group-item-action"
        >
          {{ formatCityData(city) }}
        </li>
      </ul>
    </div>

    <ul v-show="errors.length > 0" class="invalid-feedback d-block mb-0">
      <li v-for="message in errors">
        {{ message }}
      </li>
    </ul>
  </div>
</template>

<script>
import {vkApi} from "../vkApiClient";

export default {
  name: "SummaryFormInputCity",

  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: ''
    },
    help: {
      type: String,
      default: ''
    },
    constraints: {
      type: Object,
    }
  },

  emits: ['city-selected', 'validated', 'invalidated'],

  data() {
    return {
      hasChanged: false,
      value: '',
      // Для работы с API ВК
      vkData: {
        russiaId: undefined,
        cities: [],
        selectedCity: undefined
      },
      // Ошибки при валидации
      errors: []
    }
  },
  mounted() {
    this.queryRussiaCountryId();
  },
  computed: {
    inputValue: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.value = newValue;

        if (this.hasChanged) {
          this.errors = this.formatErrors(newValue, this.constraints);
        }
        this.hasChanged = true;

        if (0 === this.errors.length) {
          this.queryCitiesList(newValue);
          this.$emit('validated', this.name, newValue);
        } else {
          this.vkData.cities = [];
          this.$emit('invalidated', this.name);
        }
      }
    }
  },

  methods: {
    queryCitiesList(query) {
      this.queryRussiaCountryId();

      vkApi
          .get(vkApi.methods.database.getCities, {
            country_id: this.vkData.russiaId,
            q: query.substr(0, 15),
            need_all: 1,
            count: 10
          }, (function (err, data) {
            if (err) {
              console.error(err);
            } else if (data.error) {
              console.error(data.error)
            } else {
              this.vkData.cities = data.response.items;
            }
          }).bind(this));
    },

    queryRussiaCountryId() {
      if (this.vkData.russiaId !== undefined) {
        return;
      }

      vkApi
          .get(vkApi.methods.database.getCountries, {
            code: 'RU',
            count: 1
          }, (function (err, data) {
            if (err) {
              console.error(err);
            } else if (data.error) {
              console.error(data.error)
            } else {
              this.vkData.russiaId = data.response.items[0].id;
            }
          }).bind(this));
    },

    formatCityData(city) {
      let s = city.title;

      let additionalData = [];
      if (city.area) {
        additionalData.push(city.area);
      }
      if (city.region) {
        additionalData.push(city.region);
      }

      if (additionalData.length > 0) {
        s += ' (' + additionalData.join(', ') + ')';
      }
      return s;
    },

    selectCity(cityId) {
      let city = this.vkData.cities.find((city) => city.id === cityId);
      this.value = city.title;
      this.vkData.cities = [];
      this.$emit('city-selected', city);
      this.$emit('validated', this.name, city.title);
    },

    /** todo вынести
     * Возвращает массив ошибок валидации
     * Аргументы:
     * value: string - проверяемое значение
     * constraints: array<string, mixed>
     * Returns: array<string> - ошибки валидации
     */
    formatErrors(value, constraints) {
      let errors = [];

      if (!constraints || !value && !constraints.required) {
        return errors;
      }
      if (constraints.required && !value) {
        errors.push('Обязательное поле');
      }

      if (typeof value === 'string') {
        let lengthMessage = 'Длина - ';
        if (constraints.minLength && value.length < constraints.minLength) {
          lengthMessage += ' от ' + constraints.minLength;
        }
        if (constraints.maxLength && value.length > constraints.maxLength) {
          lengthMessage += ' до ' + +constraints.maxLength;
        }
        if (lengthMessage.length > 8) {
          lengthMessage += ' символов'
          errors.push(lengthMessage);
        }

        if (constraints.regex && constraints.regexErrorMessage && !constraints.regex.test(value)) {
          errors.push(constraints.regexErrorMessage);
        }
      } else if (typeof value === 'number') {
        let lengthMessage = 'Число - ';
        if (constraints.minLength && value < constraints.minLength) {
          lengthMessage += ' от ' + constraints.minLength;
        }
        if (constraints.maxLength && value > constraints.maxLength) {
          lengthMessage += ' до ' + +constraints.maxLength;
        }

        if (lengthMessage.length > 8) {
          errors.push(lengthMessage);
        }
      }

      return errors;
    }
  }
}
</script>

<style scoped>
.field {
  position: relative
}

.field__choices {
  position: absolute;
  top: 20px;
  left: 0;
}

.invalid-feedback {
  font-size: 0.8rem;
}
</style>
