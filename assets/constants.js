// типы образований
export const EDUCATION_TYPES = {
    secondary: 'Среднее',
    secondarySpecial: 'Среднее специальное',
    incompleteHigher: 'Неоконченное высшее',
    higher: 'Высшее',
};

// статусы резюме
export const RESUME_STATUSES = {
    new: 'Новое',
    interviewScheduled: 'Назначено собеседование',
    accepted: 'Принят',
    rejected: 'Отказ',
};

// Структура образования
export const EDUCATION_TEMPLATE = {
    id: 0,
    type: '',
    institution: '',
    faculty: '',
    specialization: '',
    graduationYear: ''
};

// Регулярные выражения, применяемые при валидации
export const REGEXES = {
    isAlpha: new RegExp('^[A-ЯЁ][а-яё\-]{1,32}$', 'u'),
    isAlphaMultiWords: new RegExp('^([A-ЯЁA-Z][а-яёa-z]{1,32}|[A-ЯЁA-Z]{1,16})( ([A-ЯЁA-Z]?[а-яёa-z]{1,32}|[A-ЯЁA-Z]{1,16}))*$', 'u'),
    isDigit: new RegExp('^[0-9]+$'),
    city: new RegExp('^[А-ЯЁ][а-яё]{1,20}[\- ]?[А-ЯЁа-яё]{1,20}$'),
    email: new RegExp('^[\dA-Za-z][.-_\dA-Za-z]+[\dA-Za-z]?@([-\dA-Za-z]+\.){1,2}[-A-Za-z]{2,7}$'),
    imgUrl: new RegExp('^https?\:\/\/((www\.)?[-a-zA-Z0-9.\+]{1,256}\.[a-zA-Z0-9]{1,6})[-a-zA-Z0-9_\+.\/]+\.(jpe?g|png|svg|webp)([?][-a-zA-Z0-9%:_\+.~#&\/=]*)?$', 'gmi')
};

// Выводимые пользователю сообщения ошибок валидации
export const ERROR_MESSAGES = {
    isAlpha: 'Только слово на русском с заглавной буквы',
    isAlphaMultiWords: 'Только слова на русском. Первое - с заглавной буквы',
    isDigit: 'Только цифры',
    city: 'Некорректное название города',
    email: 'Некорректный email',
    url: 'Некорректная ссылка'
};
