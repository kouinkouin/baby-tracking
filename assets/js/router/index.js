import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    routes: [
        {path: '/', redirect: '/add'},
        {path: '/add', name: 'add', component: () => import('../views/Add')},
        {path: '/history', name: 'history', component: () => import('../views/History')}
    ]
})
