<template>
  <div class="container-fluid d-inline-flex p-0">
    <button class="btn btn-sm btn-outline-danger me-2 mb-auto" @click="$emit('removed')">X</button>

    <div>
      <SummaryFormSelect label="Образование" name="type" :options="educationTypes"
                         @changed="setEducationValue"
      />
      <div v-show="education.type && education.type !== 'secondary'">
        <SummaryFormInputInstitution name="institution"
                                     :vk-data="vkData" :constraints="constraints['institution']"
                                     @validated="setEducationValue" @invalidated="resetEducationValue"/>
        <SummaryFormField type="text" label="Факультет" name="faculty"
                          :constraints="constraints['faculty']"
                          @validated="setEducationValue" @invalidated="resetEducationValue"
        />
        <SummaryFormField type="text" label="Специализация" name="specialization"
                          :constraints="constraints['specialization']"
                          @validated="setEducationValue" @invalidated="resetEducationValue"
        />
        <SummaryFormField type="number" label="Год окончания" name="graduationYear"
                          :constraints="constraints['graduationYear']"
                          @validated="setEducationValue" @invalidated="resetEducationValue"
        />
      </div>
    </div>
  </div>
</template>

<script>
import {EDUCATION_TYPES, ERROR_MESSAGES, REGEXES} from "../constants";
import SummaryFormSelect from "./SummaryFormSelect.vue";
import SummaryFormField from "./SummaryFormInput.vue";
import SummaryFormInputInstitution from "./SummaryFormInputInstitution.vue";

export default {
  name: "EducationForm",
  components: {SummaryFormInputInstitution, SummaryFormField, SummaryFormSelect},
  emits: ["removed"],
  props: {
    // Данные об образовании
    education: {
      type: Object,
      required: true
    },
    // Данные, полученные из ВК
    vkData: {
      type: Object
    },
  },
  data() {
    return {
      educationTypes: EDUCATION_TYPES,

      // Условия для валидации каждого поля
      constraints: {
        institution: {
          required: true,
          maxLength: 300,
          regex: REGEXES.isAlphaMultiWords,
          regexErrorMessage: ERROR_MESSAGES.isAlphaMultiWords
        },
        faculty: {
          required: true,
          maxLength: 150,
          regex: REGEXES.isAlphaMultiWords,
          regexErrorMessage: ERROR_MESSAGES.isAlphaMultiWords
        },
        specialization: {
          required: true,
          maxLength: 150,
          regex: REGEXES.isAlphaMultiWords,
          regexErrorMessage: ERROR_MESSAGES.isAlphaMultiWords
        },
        graduationYear: {
          minLength: 1950,
          maxLength: new Date().getFullYear() + 6,
          required: true
        }
      },
    }
  },

  methods: {
    setEducationValue(field, value) {
      this.education[field] = value;
    },

    resetEducationValue(field) {
      this.setEducationValue(field, '');
    },
  }
}
</script>
