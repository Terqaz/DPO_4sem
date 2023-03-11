<template>
  <div class="mb-3">
    <label v-bind:for="'summaryForm-' + name" class="mb-2">{{ label }}</label>
    <input v-bind:type="type" v-model="inputValue" v-bind:id="'summaryForm-' + name"
           class="form-control form-control-sm" v-bind:placeholder="placeholder">
    <div v-if="help" class="form-text mt-2">{{ help }}</div>

    <ul v-if="errors.length > 0" class="invalid-feedback d-block mb-0">
      <li v-for="message in errors">
        {{ message }}
      </li>
    </ul>
  </div>
</template>

<script>
import {validationUtils} from "../js/validationUtils";

export default {
  name: "SummaryFormInput",

  props: {
    modelValue: {},
    // Название поля
    name: {
      type: String,
      required: true,
    },
    // Выводимое пользователю название поля
    label: {
      type: String,
      required: true,
    },
    // Тип поля ввода
    type: {
      type: String,
      required: true,
    },
    // Подсказка в поле ввода
    placeholder: {
      type: String,
      default: ''
    },
    // Подсказка под полем ввода
    help: {
      type: String,
      default: ''
    },
    // Правила валидации
    constraints: {
      type: Object,
    }
  },
  emits: [
    'update:modelValue',
    'validated', // Значение валидировано
  ],
  data() {
    return {
      errors: []
    }
  },
  watch: {
    modelValue(newValue, oldValue) {
      this.validate(newValue);
    }
  },
  computed: {
    /**
     * Обработка вводимого значения
     */
    inputValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
        this.validate(value);
      }
    },
  },
  methods: {
    validate(value) {
      this.errors = validationUtils.formatErrors(value, this.constraints);
      this.$emit('validated', this.name, value, 0 === this.errors.length);
    }
  }
}
</script>

<style scoped>
.invalid-feedback {
  font-size: 0.8rem;
}
</style>
