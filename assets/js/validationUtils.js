export const validationUtils = {
  messages: {
    required: 'Обязательное поле'
  },
  formatErrors: formatErrors,
  isValuesValidated: isValuesValidated,
};

/**
 * Возвращает массив ошибок валидации
 * Аргументы:
 * value: string - проверяемое значение
 * constraints: array<string, mixed>
 * Returns: array<string> - ошибки валидации
 */
function formatErrors(value, constraints) {
  let errors = [];

  if (!constraints || !value && !constraints.required) {
    return errors;
  }
  if (constraints.required && !value) {
    errors.push(validationUtils.messages.required);
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

function isValuesValidated(constraints, values) {
  for (const fieldName in constraints) {
    if (constraints[fieldName].required === true) {
      if (!values[fieldName]) {
        return false;
      }
    }
  }
  return true;
}

// Выводимые пользователю сообщения ошибок валидации
// Регулярные выражения, применяемые при валидации
export const REGEXES = {
  isAlpha: new RegExp('^[A-ЯЁ][а-яё\-]{1,32}$', 'u'),
  isAlphaMultiWords: new RegExp('^([A-ЯЁA-Z][а-яёa-z]{1,32}|[A-ЯЁA-Z]{1,16})( ([A-ЯЁA-Z]?[а-яёa-z]{1,32}|[A-ЯЁA-Z]{1,16}))*$', 'u'),
  isDigit: new RegExp('^[0-9]+$'),
  email: new RegExp('^[\dA-Za-z][.-_\dA-Za-z]+[\dA-Za-z]?@([-\dA-Za-z]+\.){1,2}[-A-Za-z]{2,7}$'),
  imgUrl: new RegExp('^https?\:\/\/((www\.)?[-a-zA-Z0-9.\+]{1,256}\.[a-zA-Z0-9]{1,6})[-a-zA-Z0-9_\+.\/]+\.(jpe?g|png|svg|webp)([?][-a-zA-Z0-9%:_\+.~#&\/=]*)?$', 'gmi')
};

export const ERROR_MESSAGES = {
  isAlpha: 'Только слово на русском с заглавной буквы',
  isAlphaMultiWords: 'Только слова на русском. Первое - с заглавной буквы',
  isDigit: 'Только цифры',
  city: 'Некорректное название города',
  email: 'Некорректный email',
  url: 'Некорректная ссылка'
};

export const SUMMARY_CONSTRAINTS = {
  profession: {
    required: true,
    minLength: 3,
    maxLength: 200
  },
  city: {
    required: true,
    minLength: 2,
  },
  avatarUrl: {
    required: false,
    regex: REGEXES.imgUrl,
    regexErrorMessage: ERROR_MESSAGES.url
  },
  lastName: {
    required: true,
    minLength: 2,
    maxLength: 64,
    regex: REGEXES.isAlpha,
    regexErrorMessage: ERROR_MESSAGES.isAlpha
  },
  firstName: {
    required: true,
    minLength: 2,
    maxLength: 64,
    regex: REGEXES.isAlpha,
    regexErrorMessage: ERROR_MESSAGES.isAlpha
  },
  patronymic: {
    required: false,
    minLength: 4,
    maxLength: 64,
    regex: REGEXES.isAlpha,
    regexErrorMessage: ERROR_MESSAGES.isAlpha
  },
  phone: {
    required: true,
    minLength: 6,
    maxLength: 10,
    regex: REGEXES.isDigit,
    regexErrorMessage: ERROR_MESSAGES.isDigit
  },
  email: {
    required: false,
    minLength: 5,
    maxLength: 128,
    regex: REGEXES.email,
    regexErrorMessage: ERROR_MESSAGES.email
  },
  birthday: {
    required: true,
  },
  desiredSalary: {
    required: false,
  },
  keySkills: {
    required: false,
    maxLength: 2048,
  },
  about: {
    required: false,
    minLength: 32,
    maxLength: 2048,
  },
};

export const EDUCATION_CONSTRAINTS = {
  institution: {
    required: true,
    maxLength: 255,
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
};
