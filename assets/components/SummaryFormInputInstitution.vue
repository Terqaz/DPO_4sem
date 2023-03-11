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
import {vkApi} from "../js/vkApiClient";
import {validationUtils} from "../js/validationUtils";

export default {
  name: "SummaryFormInputInstitution",

  props: {
    modelValue: {
      type: String,
    },
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
          if (this.vkData.selectedCity) {
            this.queryUniversityList(value)
          }
        } else {
          this.vkData.universities = [];
        }
      }
    }
  },

  methods: {
    validate(value) {
      this.errors = validationUtils.formatErrors(value, this.constraints);
      let isValid = 0 === this.errors.length;
      this.$emit('validated', this.name, value, isValid);
      return isValid
    },

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
