<template>
  <h4 class="mx-auto text-center">Ваше резюме</h4>
  <div class="d-flex">
    <img v-show="summary.avatarUrl"
         :src="summary.avatarUrl" alt="" class="summary__avatar mx-auto">
  </div>

  <h3 v-show="summary.firstName && summary.lastName">{{ fullName }}</h3>

  <h3>О себе</h3>
  <p v-show="summary.birthday">Дата рождения: {{ summary.birthday }}</p>
  <p v-show="summary.city">{{ summary.city }}</p>
  <p v-show="summary.about">{{ summary.about }}</p>

  <div v-for="education in summary.educations" :key="education.id">
    <div v-show="education.type">
      <h3>Образование</h3>
      <p>Уровень: {{ educationTypes[education.type] }}</p>

      <div v-show="education.type !== 'secondary'">
        <p v-show="education.institution">Учебное заведение: {{ education.institution }}</p>
        <p v-show="education.faculty">Факультет: {{ education.faculty }}</p>
        <p v-show="education.specialization">Специализация: {{ education.specialization }}</p>
        <p v-show="education.graduationYear">Год окончания: {{ education.graduationYear }}</p>
      </div>
    </div>
  </div>


  <h3>Профессиональные качества</h3>
  <p v-show="summary.profession">Профессия: {{ summary.profession }}</p>
  <p v-show="summary.desiredSalary > 0">Желаемая заработная плата: {{ summary.desiredSalary }} ₽</p>

  <p v-show="summary.keySkills">Ключевые навыки: {{ summary.keySkills }}</p>

  <h3 v-show="summary.phone || summary.email">Контакты</h3>
  <p v-show="summary.phone">Телефон: {{ formattedPhone }}</p>
  <p v-show="summary.email">Email: {{ summary.email }}</p>
</template>

<script>
import {EDUCATION_TYPES} from "../constants";

export default {
  name: "SummaryResult",
  props: {
    summary: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      educationTypes: EDUCATION_TYPES,
    }
  },
  computed: {
    /**
     * Получить полное имя (ФИО) пользователя
     * Returns: string - ФИО пользователя
     */
    fullName() {
      let fullName = this.summary.lastName + ' ' +
          this.summary.firstName;
      if (this.summary.patronymic) {
        fullName += ' ' + this.summary.patronymic;
      }
      return fullName;
    },

    /**
     * Добавить к номеру телефона код страны, если его длина равна 10
     * Returns: string - номер телефона
     */
    formattedPhone() {
      if (this.summary.phone.length === 10) {
        return '+7' + this.summary.phone;
      }
      return this.summary.phone;
    }
  },
  methods: {}
}
</script>

<style scoped>
p + p {
  margin-bottom: 5px;
}

h3 {
  margin-top: 1.5rem;
}

.summary__avatar {
  max-width: 100%;
  max-height: 17rem;
}
</style>