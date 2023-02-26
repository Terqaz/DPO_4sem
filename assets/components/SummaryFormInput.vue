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
export default {
  name: "SummaryFormInput",

  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: ''
    },
    help: {
      type: String,
      default: ''
    },
    constraints: {
      type: Object,
    }
  },

  emits: ['validated', 'invalidated'],

  data() {
    return {
      hasChanged: false,
      value: '',
      errors: []
    }
  },

  computed: {
    inputValue: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.value = newValue;

        if (this.hasChanged) {
          this.errors = this.formatErrors(newValue, this.constraints);
        }
        this.hasChanged = true;

        if (0 === this.errors.length) {
          this.$emit('validated', this.name, newValue);
        } else {
          this.$emit('invalidated', this.name);
        }
      }
    }
  },

  methods: {
    /** todo вынести
     * Возвращает массив ошибок валидации
     * Аргументы:
     * value: string - проверяемое значение
     * constraints: array<string, mixed>
     * Returns: array<string> - ошибки валидации
     */
    formatErrors(value, constraints) {
      let errors = [];

      if (!constraints || !value && !constraints.required) {
        return errors;
      }
      if (constraints.required && !value) {
        errors.push('Обязательное поле');
      }

      if (typeof value === 'string') {
        let lengthMessage = 'Длина - ';
        if (constraints.minLength && value.length < constraints.minLength) {
          lengthMessage += ' от ' + constraints.minLength;
        }
        if (constraints.maxLength && value.length > constraints.maxLength) {
          lengthMessage += ' до ' + +constraints.maxLength;
        }
        if (lengthMessage.length > 8) {
          lengthMessage += ' символов'
          errors.push(lengthMessage);
        }

        if (constraints.regex && constraints.regexErrorMessage && !constraints.regex.test(value)) {
          errors.push(constraints.regexErrorMessage);
        }
      } else if (typeof value === 'number') {
        let lengthMessage = 'Число - ';
        if (constraints.minLength && value < constraints.minLength) {
          lengthMessage += ' от ' + constraints.minLength;
        }
        if (constraints.maxLength && value > constraints.maxLength) {
          lengthMessage += ' до ' + +constraints.maxLength;
        }

        if (lengthMessage.length > 8) {
          errors.push(lengthMessage);
        }
      }

      return errors;
    }
  }
}
</script>

<style scoped>
.invalid-feedback {
  font-size: 0.8rem;
}
</style>
