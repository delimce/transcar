import 'bootstrap/dist/js/bootstrap.min';
import 'popper.js';
import 'bootstrap4-notify/bootstrap-notify.min.js';
import 'bootstrap-select-v4/dist/js/bootstrap-select.min'

window.api_url = process.env.MIX_API_URL;
global.axios = require('axios');
global.$ = global.jQuery = require('jquery');

global.axios.interceptors.request.use(function (config) {
    // spinning start to show
    // UPDATE: Add this code to show global loading indicator
    document.body.classList.add('loading-indicator');
    return config;

}, function (error) {
    return Promise.reject(error);
});

global.axios.interceptors.response.use(function (response) {
    // spinning hide
    // UPDATE: Add this code to hide global loading indicator
    document.body.classList.remove('loading-indicator');
    return response;
}, function (error) {
    document.body.classList.remove('loading-indicator');
    return Promise.reject(error);
});