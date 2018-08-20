import 'bootstrap/dist/js/bootstrap.min';
import 'popper.js';
import 'bootstrap4-notify/bootstrap-notify.min.js';
import 'bootstrap-select-v4/dist/js/bootstrap-select.min'

window.api_url = process.env.MIX_API_URL;
global.axios = require('axios');
global.$ = global.jQuery = require('jquery');