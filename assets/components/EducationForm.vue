<template>
  <SummaryFormSelect label="Образование" name="type" :options="educationTypes"
                     @changed="setFormValue"
  />
  <div v-show="formData.type && formData.type !== 'secondary'">
    <SummaryFormField type="text" label="Учебное заведение" name="institution"
                      :constraints="constraints['institution']"
                      @validated="setFormValue" @invalidated="resetFormValue"
    />
    <SummaryFormField type="text" label="Факультет" name="faculty"
                      :constraints="constraints['faculty']"
                      @validated="setFormValue" @invalidated="resetFormValue"
    />
    <SummaryFormField type="text" label="Специализация" name="specialization"
                      :constraints="constraints['specialization']"
                      @validated="setFormValue" @invalidated="resetFormValue"
    />
    <SummaryFormField type="number" label="Год окончания" name="graduationYear"
                      :constraints="constraints['graduationYear']"
                      @validated="setFormValue" @invalidated="resetFormValue"
    />
  </div>
</template>

<script>
import SummaryFormSelect from "./SummaryFormSelect.vue";
import SummaryFormField from "./SummaryFormInput.vue";
import {EDUCATION_TYPES, ERROR_MESSAGES, REGEXES} from "../constants";

export default {
  name: "EducationForm",
  components: {SummaryFormField, SummaryFormSelect},
  emits: ["changed"],
  data() {
    return {
      formData: {
        type: '',
        institution: '',
        faculty: '',
        specialization: '',
        graduationYear: '',
      },

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
    setFormValue(field, value) {
      this.formData[field] = value;
      this.$emit('changed', field, value);
    },

    resetFormValue(field) {
      this.setFormValue(field, '');
      this.$emit('changed', field, '');
    },
  }
}
</script>
