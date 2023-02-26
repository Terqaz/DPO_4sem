<template>
  <div class="mb-3">
    <label v-bind:for="'summaryForm-' + name" class="mb-2">Учебное заведение</label>
    <div class="field">
      <input type="text" v-model="inputValue" v-bind:id="'summaryForm-' + name"
             class="form-control form-control-sm" v-bind:placeholder="placeholder">
      <div class="form-text mt-2">Подсказки появятся после выбора города из выпадающего списка</div>
      <ul v-show="vkData.universities.length > 0" class="field__choices list-group">
        <li v-for="university in vkData.universities" :key="university.id"
            @click="selectUniversity(university.id)"
            class="list-group-item list-group-item-action"
        >
          {{ university.title }}
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
  name: "SummaryFormInputInstitution",

  props: {
    name: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: ''
    },
    constraints: {
      type: Object,
    },
    vkData: {
      type: Object,
      required: true
    },
  },

  emits: ['university-selected', 'validated', 'invalidated'],

  data() {
    return {
      hasChanged: false,
      value: '',
      // Ошибки при валидации
      errors: []
    }
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
          if (this.vkData.selectedCity) {
            this.queryUniversityList(newValue)
          }

          this.$emit('validated', this.name, newValue);
        } else {
          this.vkData.cities = [];
          this.$emit('invalidated', this.name);
        }
      }
    }
  },

  methods: {
    queryUniversityList(query) {
      vkApi
          .get(vkApi.methods.database.getUniversities, {
            country_id: this.vkData.russiaId,
            city_id: this.vkData.selectedCity.id,
            q: query,
            count: 10
          }, (function (err, data) {
            if (err) {
              console.error(err);
            } else if (data.error) {
              console.error(data.error)
            } else {
              this.vkData.universities = data.response.items;
            }
          }).bind(this));
    },

    selectUniversity(cityId) {
      let university = this.vkData.universities.find((city) => city.id === cityId);
      this.value = university.title;
      this.vkData.universities = [];
      this.$emit('university-selected', university);
      this.$emit('validated', this.name, university.title);
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
