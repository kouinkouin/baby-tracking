import '../css/app.css';

import Vue from "vue";
import App from "./App";
import router from "./router";
import store from "./store";
import BootstrapVue from 'bootstrap-vue';

Vue.use(BootstrapVue);

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount("#app");
