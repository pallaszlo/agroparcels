/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import Vuetify, { VSnackbar, VBtn, VIcon } from 'vuetify';
import VueFormWizard from 'vue-form-wizard';
import VeeValidate from 'vee-validate';
import { Validator } from 'vee-validate';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Index from './components/Index';
import App from './components/App';
import router from './router';
import store from './store';
import { mapActions } from "vuex";
import VueLayers from 'vuelayers'
import 'vuelayers/dist/vuelayers.css' // needs css-loader


window.Vue = require('vue');
Vue.use(Vuetify);
Vue.use(VueFormWizard);
Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(VeeValidate);
Vue.use(VueLayers)


Vue.component('home', require('./components/Home.vue').default);
Vue.component('login', require('./components/auth/login/Login.vue').default);
Vue.component('dashboard', require('./components/dashboard/Dashboard.vue').default);
Vue.component('notfound', require('./components/NotFound.vue').default);
Vue.component('regisztracio', require('./components/auth/regisztracio/Regisztracio.vue').default);

const dict = {
    custom: {
        nev: {
            required: 'Kötelező kitölteni'
        },
        email: {
            required: 'Kötelező kitölteni',
            email: 'Helytelen email cím'
        },
        password: {
            required: 'Kötelező kitölteni',
            min: 'Minimum 6 karakter kell'
        },
        oldpassword: {
            required: 'Kötelező kitölteni',
        },
        password_confirm: {
            required: 'Kötelező kitölteni',
            min: 'Minimum 6 karakter kell',
            confirmed: 'A két jelszó nem egyezik'
        },
        password_confirmation: {
            required: 'Kötelező kitölteni',
            min: 'Minimum 6 karakter kell',
            confirmed: 'A két jelszó nem egyezik'
        },
    }
};

Validator.localize('en', dict);

axios.defaults.withCredentials = true;
//axios.defaults.baseURL = '/api';
axios.defaults.baseURL = 'http://127.0.0.1:8000/';

Vue.prototype.$baseURL = 'http://127.0.0.1:8000/';

const opts = {};

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(opts),
    router,
    store,
    created() {
        // user refreshes a page
        const userInfo = localStorage.getItem('user')
        if (userInfo) {
            const userData = JSON.parse(userInfo)
            this.$store.commit("auth/setUser", userData);
        }

        // token is expired or if the user is unauthorized
        axios.interceptors.response.use(
            response => response,
            error => {
                switch (error.response.status) {
                    case 401:
                        console.log('-------401-------');
                        this.$router.push({ name: "login" });
                        break;
                    case 419:
                        console.log('-------419-------');
                        //window.location.reload();
                }
                return Promise.reject(error)
            }
        )

    },
    render: h => h(App),
});
