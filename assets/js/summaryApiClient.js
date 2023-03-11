import axios from "axios";

const PATH_PREFIX = '/api/cv';

export const summaryApi = {
    getList: getList,
    getById: getById,
    add: add,
    edit: edit,
    updateStatus: updateStatus,
};

function getList() {
    return get('/');
}

function getById(id) {
    return get('/' + id);
}

function add(summary) {
    return post('/add', summary);
}

function edit(id, summary) {
    return post('/' + id + '/edit', summary);
}

function updateStatus(id, newStatus) {
    return post('/' + id + '/status/update', {
        status: newStatus
    });
}

function get(url, config = {}) {
    return axios
        .get(PATH_PREFIX + url, config)
}

function post(url, data = {}, config = {}) {
    const json = JSON.stringify(data, (k, v) => {
       if (v === '') {
           return undefined;
       }
       return v;
    });

    return axios
        .post(PATH_PREFIX + url, json, config)
}