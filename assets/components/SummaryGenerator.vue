<template>
  <p v-if="errors.loading" class="text-danger">
    Произошла ошибка при загрузке страницы. Не волнуйтесь, вскоре сервис возобновит свою работу :)
  </p>

  <div v-else class="d-flex flex-row">
    <!-- Форма -->
    <div class="summary-form__wrapper w-50 p-3">
      <div id="summaryForm" class="summary-form flex-column mx-auto rounded-3 overflow-hidden px-2">
        <h4 class="mx-auto text-center">Заполните данные о себе</h4>

        <div class="summary-form__body text-dark d-flex flex-column">
          <SummaryFormSelect v-model="formData.status"
                             label="Статус" name="status" :options="summaryStatuses"
          />
          <SummaryFormInput v-model="formData.profession"
                            label="Профессия" name="profession" type="text"
                            placeholder="Например, 'Программист C++'"
                            :constraints="constraints['profession']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInputCity v-model="formData.city"
                                name="city"
                                placeholder="Например, Москва"
                                :constraints="constraints['city']"
                                :vkData="vkData"
                                @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.avatarUrl"
                            label="Ссылка на фото" name="avatarUrl" type="text"
                            placeholder="https://example.site"
                            :constraints="constraints['avatarUrl']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.lastName"
                            label="Фамилия" name="lastName" type="text"
                            placeholder="Иванов"
                            :constraints="constraints['lastName']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.firstName"
                            label="Имя" name="firstName" type="text"
                            placeholder="Иван"
                            :constraints="constraints['firstName']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.patronymic"
                            label="Отчество" name="patronymic" type="text"
                            placeholder="Иванович"
                            :constraints="constraints['patronymic']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.phone"
                            label="Телефон" name="phone" type="text"
                            placeholder="002030 или 9001002030"
                            help="Сокращенный (6 цифр) или полный без кода страны (10 цифр)"
                            :constraints="constraints['phone']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.email"
                            label="Email" name="email" type="text"
                            placeholder="example@some.site"
                            :constraints="constraints['email']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.birthday"
                            label="Дата рождения" name="birthday" type="date"
                            :constraints="constraints['birthday']"
                            @validated="setValidatedValue"
          />

          <EducationFormList :modelValue="formData.educations"
                             @update:modelValue="(value) => setValidatedValue('educations', value)"
                             :vkData="vkData"/>

          <SummaryFormInput v-model="formData.desiredSalary"
                            label="Желаемая зарплата" name="desiredSalary" type="number"
                            :constraints="constraints['desiredSalary']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.keySkills"
                            label="Ключевые навыки" name="keySkills" type="text"
                            placeholder="Symfony, Vue.js, ..."
                            :constraints="constraints['keySkills']"
                            @validated="setValidatedValue"
          />
          <SummaryFormInput v-model="formData.about"
                            label="О себе" name="about" type="text"
                            placeholder="Попытайтесь описать себя"
                            :constraints="constraints['about']"
                            @validated="setValidatedValue"
          />
        </div>
        <button class="btn btn-primary" @click="save">Применить</button>


        <p v-if="errors.beforeSaving" class="text-danger">
          {{ errors.beforeSaving }}
        </p>
        <p v-else-if="errors.saving" class="text-danger">
          Не удалось сохранить резюме. Не волнуйтесь, вскоре сервис
          возобновит свою работу :)
        </p>
        <p v-else-if="hasSaved">
          Успешно сохранено
        </p>
      </div>
    </div>
    <!-- Резюме -->
    <div class="summary container-fluid w-50 p-3">
      <SummaryResult :summary="validatedValues"/>
    </div>
  </div>
</template>

<script>
import {SUMMARY_STATUSES, SUMMARY_TEMPLATE} from "../js/constants";
import SummaryFormInput from "./SummaryFormInput.vue";
import SummaryFormInputCity from "./SummaryFormInputCity.vue"
import EducationFormList from "./EducationFormList.vue";
import SummaryResult from "./SummaryResult.vue";
import SummaryFormSelect from "./SummaryFormSelect.vue";
import {summaryApi} from "../js/summaryApiClient";
import {SUMMARY_CONSTRAINTS, validationUtils} from "../js/validationUtils";

export default {
  name: 'SummaryGenerator',
  components: {SummaryFormSelect, SummaryFormInput, SummaryFormInputCity, EducationFormList, SummaryResult},
  created() {
    /**
     * Обработать ответ
     * @param response
     * @returns обработанный ответ
     */
    const processResponse = (response) => {
      response.birthday = response.birthday.substring(0, 10);
      return response;
    }

    /**
     * Загрузить резюме изначально и при изменнении роута
     * @param newName - новое имя роута
     * @param oldName - старое имя роута
     */
    const loadSummary = (newName, oldName = '') => {
      // Подгружаем данные существующего резюме при редактировании
      if (newName === 'summary.add') {
        this.formData = structuredClone(SUMMARY_TEMPLATE);
        this.validatedValues = {};
        this.hasSaved = false;
      } else if (newName === 'summary.edit') {
        const id = parseInt(this.$route.params.id);

        summaryApi
            .getById(id)
            .then((response) => {
              if (response.status === 200) {
                const data = processResponse(response.data);
                this.formData = data;
                this.validatedValues = structuredClone(data);
              } else {
                this.errors.loading = response.data.error;
                console.log(response);
              }
            });
      }
    };

    loadSummary(this.$route.name);

    this.$watch(
        () => this.$route.name,
        loadSummary
    )
  },
  data() {
    return {
      summaryStatuses: SUMMARY_STATUSES,

      // Данные для резюме
      formData: structuredClone(SUMMARY_TEMPLATE),

      // Условия для валидации каждого поля
      constraints: SUMMARY_CONSTRAINTS,

      // Валидированные значения для отрисовки и отправки резюме по API
      validatedValues: {},
      // Ошибки в процессе работы с API
      errors: {
        loading: '',
        saving: '',
        beforeSaving: ''
      },
      // Успешно ли сохранилось резюме
      hasSaved: false,

      // Для работы с API ВК
      vkData: {
        russiaId: undefined,
        cities: [],
        universities: [],
        selectedCity: undefined
      },
    };
  },

  methods: {
    /**
     * Обновить валидное значение поля
     * @param field - имя поля
     * @param value - значение
     * @param isValid - валидное ли значение
     */
    setValidatedValue(field, value, isValid = true) {
      this.validatedValues[field] = isValid ? value : '';
      this.errors.beforeSaving = '';
    },

    /**
     * Сохранить резюме по API
     */
    save() {
      this.validatedValues.status = this.formData.status;

      if (!validationUtils.isValuesValidated(this.constraints, this.validatedValues)) {
        this.errors.beforeSaving = 'Пожалуйста, проверьте корректность заполненных данных';
        return;
      }

      // Выполняем запрос в зависимости от роута
      if (this.$route.name === "summary.add") {
        summaryApi
            .add(this.validatedValues)
            .then((response) => {
              if (response.status === 200) {
                this.hasSaved = true;
              } else {
                this.errors.saving = response.data.error;
                console.log(response);
              }
            });
      } else if (this.$route.name === "summary.edit") {
        summaryApi
            .edit(parseInt(this.$route.params.id), this.validatedValues)
            .then((response) => {
              if (response.status === 200) {
                this.hasSaved = true;
              } else {
                this.errors.saving = response.data.error;
                console.log(response);
              }
            });
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