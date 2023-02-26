import {APP_CONFIG} from "./config.js";
import jsonp from "jsonp";

const VK = APP_CONFIG.vk.uri;

export const vkApi = {
    methods: {
        database: {
            getCountries: 'database.getCountries',
            getCities: 'database.getCities',
            getUniversities: 'database.getUniversities',
        }
    },
    get: get,
};

function get(method, queryParams, fn) {
    queryParams.access_token = APP_CONFIG.vk.token;
    queryParams.v = APP_CONFIG.vk.apiVersion;
    queryParams.lang = 'ru';

    let uri = VK + method + '?' + makeQueryString(queryParams);
    jsonp(uri, null, fn);
}

function makeQueryString(queryParams) {
    return Array.from(Object.entries(queryParams))
        .map(value => value[0] + '=' + encodeURIComponent(value[1]))
        .join('&');
}
