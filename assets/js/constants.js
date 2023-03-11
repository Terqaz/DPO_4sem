// Типы образований
export const EDUCATION_TYPES = {
    secondary: 'Среднее',
    secondarySpecial: 'Среднее специальное',
    incompleteHigher: 'Неоконченное высшее',
    higher: 'Высшее',
};

// Статусы резюме
export const SUMMARY_STATUSES = {
    new: 'Новое',
    interviewScheduled: 'Назначено собеседование',
    accepted: 'Принят',
    rejected: 'Отказ',
};

// Экзепляр структуры резюме
export const SUMMARY_TEMPLATE = {
    status: 'new',
    profession: '',
    city: '',
    avatarUrl: '',
    lastName: '',
    firstName: '',
    patronymic: '',
    phone: '',
    email: '',
    birthday: '',
    educations: [],
    desiredSalary: '',
    keySkills: '',
    about: '',
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

