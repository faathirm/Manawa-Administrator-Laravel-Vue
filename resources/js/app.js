/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import Axios from 'axios';
import Buefy from 'buefy'
import VueCurrencyFilter from 'vue-currency-filter'
import CKEditor from '@ckeditor/ckeditor5-vue';
// import 'buefy/dist/buefy.css'

Vue.use(VueRouter,VueAxios,Axios);
Vue.use(Buefy);
Vue.use(require('vue-moment'));
Vue.use(VueCurrencyFilter,
    {
        symbol : 'Rp.',
        thousandsSeparator: '.',
        fractionCount: 0,
        fractionSeparator: ',',
        symbolPosition: 'front',
        symbolSpacing: true
    });
Vue.use( CKEditor );

import App from './components/App.vue';
import Read from './components/Read.vue';
import AdminNew from './components/Admin/New.vue';
import Farmer from './components/Pages/Farmer';
import Customer from './components/Pages/Customer';
import CustomerDetail from './components/Pages/CustomerDetail';
import Product from './components/Pages/Product';
import CustomerLivestock from './components/Pages/CustomerLivestock';
import CustomerLivestockDetail from './components/Pages/CustomerLivestockDetail';
import Transaction from './components/Pages/Transaction';
import Confirmation from './components/Pages/Confirmation';
import Withdrawal from './components/Pages/Withdrawal';
import Notificaton from './components/Pages/Notification';
import Voucher from './components/Pages/Voucher';
import Ticketing from "./components/Pages/Ticketing";

const routes = [
    {
        name: 'read',
        path: '/',
        component: Read
    },
    {
        name: 'admin_view',
        path: '/account/admin',
        component: Read
    },
    {
        name: 'admin_new',
        path: '/account/admin/new',
        component: AdminNew
    },
    {
        name: 'farmer',
        path: '/account/farmer',
        component: Farmer
    },
    {
        name: 'customer',
        path: '/account/customer',
        component: Customer
    },
    {
        name: 'customer_detail',
        path: '/account/customer/:id',
        component: CustomerDetail
    },
    {
        name: 'product',
        path: '/livestock/product',
        component: Product
    },
    {
        name: 'customer_livestock',
        path: '/livestock/customer',
        component: CustomerLivestock
    },
    {
        name: 'customer_livestock_detail',
        path: '/livestock/customer/:id',
        component: CustomerLivestockDetail
    },
    {
        name: 'transaction',
        path: '/transaction/view',
        component: Transaction
    },
    {
        name: 'confirmation',
        path: '/transaction/confirmation',
        component: Confirmation
    },
    {
        name: 'withdrawal',
        path: '/transaction/withdrawal',
        component: Withdrawal
    },
    {
        name: 'notification',
        path: '/crm/notification',
        component: Notificaton
    },
    {
        name: 'voucher',
        path: '/crm/voucher',
        component: Voucher
    },
    {
        name: 'ticketing',
        path: '/crm/ticketing',
        component: Ticketing
    }
]

const router = new VueRouter({ mode: 'history', routes: routes ,linkActiveClass: "is-active", linkExactActiveClass: "exact-active"});
new Vue(Vue.util.extend({router }, App)).$mount("#app");
