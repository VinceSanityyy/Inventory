require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from 'vue-router'
import toastr from 'admin-lte/plugins/toastr/toastr.min.js'
import swal from 'admin-lte/plugins/sweetalert2/sweetalert2.min.js'
Vue.use(VueRouter)
Vue.use(toastr)



window.swal = swal;
window.toastr = require('toastr')

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const routes = [
    { path: '/products', component: require('./components/Products.vue').default },
]


const router = new VueRouter({
    mode: 'history',
    routes
})

const app = new Vue({
    el: '#app',
    router
});
