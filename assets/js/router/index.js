import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    routes: [
        {path: '/', redirect: '/add'},
        {path: '/home', name: 'home', component: () => import('../views/Home')},
        {path: '/add', name: 'add', component: () => import('../views/Add')}
    ]
})
