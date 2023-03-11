<template>
  <div class="mb-3">
    <label v-bind:for="'summaryForm-' + name" class="mb-2">Город проживания</label>
    <div class="field">
      <input type="text" v-model="inputValue" v-bind:id="'summaryForm-' + name"
             class="form-control form-control-sm" v-bind:placeholder="placeholder">
      <div v-if="help" class="form-text mt-2">{{ help }}</div>
      <ul v-show="vkData.cities.length > 0" class="field__choices list-group">
        <li v-for="city in vkData.cities" :key="city.id"
            @click="selectCity(city.id)"
            class="list-group-item list-group-item-action"
        >
          {{ city }}
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
import {vkApi} from "../js/vkApiClient";
import {validationUtils} from "../js/validationUtils";

export default {
  name: "SummaryFormInputCity",

  props: {
    modelValue: {
      type: String,
    },
    // Название поля
    name: {
      type: String,
      required: true,
    },
    // Подсказка в поле ввода
    placeholder: {
      type: String,
      default: ''
    },
    // Подсказка под полем ввода
    help: {
      type: String,
      default: ''
    },
    // Правила валидации
    constraints: {
      type: Object,
    },
    // Для работы с VK API
    vkData: {
      type: Object,
      required: true
    }
  },

  emits: [
    'update:modelValue',
    'validated'
  ],

  data() {
    return {
      // Ошибки при валидации
      errors: []
    }
  },
  mounted() {
    this.queryRussiaCountryId();
  },
  watch: {
    modelValue(newValue, oldValue) {
      this.validate(newValue);
    }
  },
  computed: {
    inputValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);

        if (this.validate(value)) {
          this.queryCitiesList(value);
        } else {
          this.vkData.cities = [];
        }
      }
    },
  },

  methods: {
    validate(value) {
      this.errors = validationUtils.formatErrors(value, this.constraints);
      let isValid = 0 === this.errors.length;
      this.$emit('validated', this.name, value, isValid);
      return isValid
    },

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
      this.vkData.selectedCity = city;
      this.$emit('validated', this.name, this.formatCityData(city), true);
    },
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
