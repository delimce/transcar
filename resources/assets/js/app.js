import 'bootstrap';

window.api_url = process.env.MIX_API_URL;
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');