<template>
  <div class="mb-3">
    <label v-bind:for="'summaryForm-' + name" class="mb-2">{{ label }}</label>
    <select v-model="inputValue" v-bind:id="'summaryForm-' + name"
            class="form-control form-control-sm">
      <option v-for="(value, option) in options" :key="option" :value="option">{{ value }}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: "SummaryFormSelect",
  props: {
    modelValue: {
      required: true,
    },
    // Выводимое пользователю название поля
    label: {
      type: String,
      required: true
    },
    // Название поля
    name: {
      type: String,
      required: true
    },
    // Значения для выбора
    options: {
      type: Object,
      required: true
    },

    default: {
      type: String,
      default: ''
    }
  },

  emits: [
    "update:modelValue"
  ],

  computed: {
    inputValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      }
    }
  },
}
</script>
