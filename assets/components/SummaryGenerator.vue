<template>
  <div class="d-flex flex-row w-100">
    <!-- Форма -->
    <div class="summary-form__wrapper container-fluid w-50 p-3">
      <div id="summaryForm" class="summary-form flex-column mx-auto rounded-3 overflow-hidden px-2">
        <h4 class="mx-auto text-center">Заполните данные о себе</h4>

        <div class="summary-form__body text-dark d-flex flex-column">
          <SummaryFormSelect label="Статус" name="status" :options="resumeStatuses" default="new"
                             @changed="setFormValue"
          />
          <SummaryFormInput label="Профессия" name="profession" type="text"
                            placeholder="Например, 'Программист C++'"
                            :constraints="constraints['profession']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInputCity name="city"
                                placeholder="Например, Москва"
                                :constraints="constraints['city']"
                                :vkData="vkData"
                                @validated="(field, value) => formatCityData(value)"
                                @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Ссылка на фото" name="avatarUrl" type="text"
                            placeholder="https://example.site"
                            :constraints="constraints['avatarUrl']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Фамилия" name="lastName" type="text"
                            placeholder="Иванов"
                            :constraints="constraints['lastName']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Имя" name="firstName" type="text"
                            placeholder="Иван"
                            :constraints="constraints['firstName']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Отчество" name="patronymic" type="text"
                            placeholder="Иванович"
                            :constraints="constraints['patronymic']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Телефон" name="phone" type="text"
                            placeholder="002030 или 9001002030"
                            help="Сокращенный (6 цифр) или полный без кода страны (10 цифр)"
                            :constraints="constraints['phone']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Email" name="email" type="text"
                            placeholder="example@some.site"
                            :constraints="constraints['email']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Дата рождения" name="birthday" type="date"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />

          <EducationFormList :educations="formData.educations" :vkData="vkData"/>

          <SummaryFormInput label="Желаемая зарплата" name="desiredSalary" type="number"
                            :constraints="constraints['desiredSalary']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="Ключевые навыки" name="keySkills" type="text"
                            placeholder="Symfony, Vue.js, ..."
                            :constraints="constraints['keySkills']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
          <SummaryFormInput label="О себе" name="about" type="text"
                            placeholder="Попытайтесь описать себя"
                            :constraints="constraints['about']"
                            @validated="setFormValue" @invalidated="resetFormValue"
          />
        </div>
      </div>
    </div>
    <!-- Резюме -->
    <div class="summary container-fluid w-50 p-3">
      <SummaryResult :summary="formData"/>
    </div>
  </div>
</template>

<script>
import {ERROR_MESSAGES, REGEXES, RESUME_STATUSES} from "../constants";
import SummaryFormInput from "./SummaryFormInput.vue";
import SummaryFormInputCity from "./SummaryFormInputCity.vue"
import EducationFormList from "./EducationFormList.vue";
import SummaryResult from "./SummaryResult.vue";
import SummaryFormSelect from "./SummaryFormSelect.vue";

export default {
  name: 'SummaryGenerator',
  components: {SummaryFormSelect, SummaryFormInput, SummaryFormInputCity, EducationFormList, SummaryResult},
  data() {
    return {
      resumeStatuses: RESUME_STATUSES,

      // Данные для резюме
      formData: {
        status: '',
        profession: '',
        city: '',
        avatarUrl: '',
        lastName: '',
        firstName: '',
        patronymic: '',
        phone: '',
        email: '',
        birthday: '',
        educations: [],
        desiredSalary: '',
        keySkills: '',
        about: '',
      },
      // Для работы с API ВК
      vkData: {
        russiaId: undefined,
        cities: [],
        universities: [],
        selectedCity: undefined
      },

      // Условия для валидации каждого поля
      constraints: {
        profession: {
          required: true,
          minLength: 3,
          maxLength: 200
        },
        city: {
          required: true,
          minLength: 2,
          regex: REGEXES.city,
          regexErrorMessage: ERROR_MESSAGES.city
        },
        avatarUrl: {
          required: false,
          regex: REGEXES.imgUrl,
          regexErrorMessage: ERROR_MESSAGES.url
        },
        lastName: {
          required: true,
          minLength: 2,
          maxLength: 64,
          regex: REGEXES.isAlpha,
          regexErrorMessage: ERROR_MESSAGES.isAlpha
        },
        firstName: {
          required: true,
          minLength: 2,
          maxLength: 64,
          regex: REGEXES.isAlpha,
          regexErrorMessage: ERROR_MESSAGES.isAlpha
        },
        patronymic: {
          required: false,
          minLength: 4,
          maxLength: 64,
          regex: REGEXES.isAlpha,
          regexErrorMessage: ERROR_MESSAGES.isAlpha
        },
        phone: {
          required: true,
          minLength: 6,
          maxLength: 10,
          regex: REGEXES.isDigit,
          regexErrorMessage: ERROR_MESSAGES.isDigit
        },
        email: {
          required: false,
          minLength: 5,
          maxLength: 128,
          regex: REGEXES.email,
          regexErrorMessage: ERROR_MESSAGES.email
        },
        birthday: {
          required: true,
        },
        desiredSalary: {
          required: false,
        },
        keySkills: {
          required: false,
          maxLength: 2048,
        },
        about: {
          required: false,
          minLength: 32,
          maxLength: 2048,
        },
      },
    };
  },
  methods: {
    setFormValue(field, value) {
      this.formData[field] = value;
    },

    resetFormValue(field) {
      this.setFormValue(field, '');
    },

    formatCityData(cityTitle) {
      let city = this.vkData.selectedCity;
      if (!city) {
        // Если получен не из API ВК
        this.formData.city = 'г. ' + cityTitle;
      } else {
        let cityData = [];

        if (city.region) {
          cityData.push(city.region);
        }
        if (city.area) {
          cityData.push(city.area);
        }
        cityData.push('г. ' + city.title);

        this.formData.city = cityData.join(', ');
      }
    }
  }
}
</script>

<style scoped>
.summary-form__body {
  border: none;
  min-height: 30px;
}

.summary-form {
  max-width: 600px;
}
</style>