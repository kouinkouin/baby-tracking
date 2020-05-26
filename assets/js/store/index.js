import Vue from 'vue';
import Vuex from 'vuex';

import log from './modules/log';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        log,
    },
    strict: debug,
})
