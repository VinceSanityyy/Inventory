require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

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
