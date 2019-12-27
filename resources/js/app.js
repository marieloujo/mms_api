/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// 1. Comment out this following line:
// window.Vue = require('vue');

// 2. Add below the above commented-out line:
import Vue from 'vue'; 
//import VueRouter from "vue-router"; 
import vuetify from './vuetify/index'
import router from './routes/index';

window.Vue = Vue;

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import App from './components/App';

new Vue({
    el: '#app',
    router,
    vuetify,
    components:{
        'App':App
    },
});