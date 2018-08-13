import 'bootstrap';
import 'popper.js';
import 'bootstrap4-notify';

window.api_url = process.env.MIX_API_URL;
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');