<template>
  <label class="mb-2">Образования</label>
  <div v-for="education in educations" :key="education.id">
    <EducationForm :education="education" :vkData="vkData"
                   @removed="() => removeEducation(education.id)"/>
  </div>
  <button @click="addEducation" class="btn btn-primary">Добавить</button>
</template>

<script>
import {EDUCATION_TEMPLATE} from "../constants";
import EducationForm from "./EducationForm.vue";

export default {
  name: "EducationFormList",
  components: {EducationForm},
  props: {
    // Данные об образовании
    educations: {
      type: Object,
      required: true
    },
    // Данные, полученные из ВК
    vkData: {
      type: Object,
      required: true
    }
  },
  methods: {
    addEducation(e) {
      let newEducation = structuredClone(EDUCATION_TEMPLATE);
      if (this.educations.length > 0) {
        newEducation.id = this.educations[this.educations.length - 1].id + 1;
      }
      this.educations.push(newEducation);
    },
    removeEducation(educationId) {
      let removingIndex = this.educations.findIndex((education) =>
          education.id === educationId);
      this.educations.splice(removingIndex, 1)
    }
  }
}
</script>

<style scoped>

</style>