<template>
  <div class="mb-3">
    <label v-bind:for="'summaryForm-' + name" class="mb-2">{{ label }}</label>
    <select v-model="inputValue" v-bind:id="'summaryForm-' + name"
            class="form-control form-control-sm">
      <option v-for="(value, option) in options" v-bind:value="option">{{ value }}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: "SummaryFormSelect",
  props: {
    label: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    options: {
      type: Object,
      required: true
    },
  },
  emits: ["changed"],

  data() {
    return {
      value: ""
    }
  },
  computed: {
    inputValue: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.value = newValue;
        this.$emit('changed', this.name, newValue);
      }
    }
  },
}
</script>
