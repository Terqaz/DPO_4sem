<template>
  <label class="mb-2">Образования</label>
  <div v-for="(education, index) in educations" :key="education.id">
    <EducationForm :modelValue="education"
                   @update:modelValue="(value) => educationChanged(index, value)"
                   :vkData="vkData"
                   @removed="() => removeEducation(education.index)"/>
  </div>
  <button @click="addEducation" class="btn btn-primary">Добавить</button>
</template>

<script>
import {EDUCATION_TEMPLATE} from "../js/constants";
import EducationForm from "./EducationForm.vue";

export default {
  name: "EducationFormList",
  components: {EducationForm},
  emits: ['update:modelValue'],
  props: {
    // Данные об образованиях
    modelValue: {
      type: Object,
      required: true
    },
    // Данные, полученные из ВК
    vkData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      educations: []
    }
  },
  watch: {
    modelValue(newValue, oldValue) {
      if (!newValue) {
        this.educations = [];
      }
      this.educations = structuredClone(newValue);
      let index = 0;
      for (const education of this.educations) {
        education.index = index++;
      }
    }
  },
  methods: {
    /**
     * @param e: Event
     */
    addEducation(e) {
      let newEducation = structuredClone(EDUCATION_TEMPLATE);
      if (this.educations.length > 0) {
        newEducation.index = this.educations[this.educations.length - 1].index + 1;
      }
      this.educations.push(newEducation);
      this.$emit('update:modelValue', this.educations);
    },

    /**
     * @param educationId: int
     */
    removeEducation(educationId) {
      let removingIndex = this.educations.findIndex((education) =>
          education.index === educationId);
      this.educations.splice(removingIndex, 1)
      this.$emit('update:modelValue', this.educations);
    },

    /**
     * Вызывается при изменении любого поля образования
     * @param index: int
     * @param education: Object
     */
    educationChanged(index, education) {
      this.educations[index] = education;
      this.$emit('update:modelValue', this.educations);
    },
  }
}
</script>

<style scoped>

</style>