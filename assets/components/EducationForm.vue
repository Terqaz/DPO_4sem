<template>
  <div class="container-fluid d-inline-flex p-0">
    <button class="btn btn-sm btn-outline-danger me-2 mb-auto"
            @click="$emit('removed')"
    >
      X
    </button>

    <div>
      <SummaryFormSelect :modelValue="modelValue.type"
                         @update:modelValue="(value) => educationChanged('type', value)"
                         label="Образование" name="type" :options="educationTypes"
      />

      <div v-show="modelValue.type && modelValue.type !== 'secondary'">
        <SummaryFormInputInstitution :modelValue="modelValue.institution"
                                     name="institution"
                                     :constraints="constraints['institution']"
                                     :vk-data="vkData"
                                     @validated="educationChanged"
        />
        <SummaryFormField :modelValue="modelValue.faculty"
                          type="text" label="Факультет" name="faculty"
                          :constraints="constraints['faculty']"
                          @validated="educationChanged"
        />
        <SummaryFormField :modelValue="modelValue.specialization"
                          type="text" label="Специализация" name="specialization"
                          :constraints="constraints['specialization']"
                          @validated="educationChanged"
        />
        <SummaryFormField :modelValue="modelValue.graduationYear"
                          type="number" label="Год окончания" name="graduationYear"
                          :constraints="constraints['graduationYear']"
                          @validated="educationChanged"
        />
      </div>
    </div>
  </div>
</template>

<script>
import {EDUCATION_TYPES} from "../js/constants";
import SummaryFormSelect from "./SummaryFormSelect.vue";
import SummaryFormField from "./SummaryFormInput.vue";
import SummaryFormInputInstitution from "./SummaryFormInputInstitution.vue";
import {EDUCATION_CONSTRAINTS} from "../js/validationUtils";

export default {
  name: "EducationForm",
  components: {SummaryFormInputInstitution, SummaryFormField, SummaryFormSelect},
  emits: ['update:modelValue', 'removed'],
  props: {
    // Данные об образовании
    modelValue: {
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
      constraints: EDUCATION_CONSTRAINTS,
    }
  },
  methods: {
    educationChanged(field, value) {
      const education = structuredClone(this.modelValue);
      education[field] = value;
      this.$emit('update:modelValue', education);
    }
  }
}
</script>
