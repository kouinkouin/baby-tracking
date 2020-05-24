import logApi from '../../api/log';

const UPDATE_BABIES = 'UPDATE_BABIES';
const UPDATE_PRESELECTED_BABY_ID = 'UPDATE_PRESELECTED_BABY_ID';
const UPDATE_LOG_TYPES = 'UPDATE_LOG_TYPES';
const UPDATE_PRESELECTED_LOG_TYPE_ID = 'UPDATE_PRESELECTED_LOG_TYPE_ID';
const UPDATE_NOW = 'UPDATE_NOW';

const state = {
    babies: [],
    preselectedBabyId: null,
    logTypes: [],
    preselectedLogTypeId: null,
    now: null,
};

const actions = {
    loadAddFields({commit}) {
        logApi.getLogAddFields()
            .then(function (result) {
                console.log(result);
                commit(UPDATE_BABIES, result.babies);
                commit(UPDATE_PRESELECTED_BABY_ID, result.preselectedBabyId);
                commit(UPDATE_LOG_TYPES, result.logTypes);
                commit(UPDATE_PRESELECTED_LOG_TYPE_ID, result.preselectedLogTypeId);
                commit(UPDATE_NOW, result.now);
            });
    },
};

const getters = {
    babies(state) {
        return state.babies;
    },
    preselectedBabyId(state) {
        return state.preselectedBabyId;
    },
    logTypes(state) {
        return state.logTypes;
    },
    preselectedLogTypeId(state) {
        return state.preselectedLogTypeId;
    },
    now(state) {
        return state.now;
    },
};

const mutations = {
    [UPDATE_BABIES](state, babies) {
        console.log(babies);
        state.babies = babies;
    },
    [UPDATE_PRESELECTED_BABY_ID](state, preselectedBabyId) {
        state.preselectedBabyId = preselectedBabyId;
    },
    [UPDATE_LOG_TYPES](state, logTypes) {
        state.logTypes = logTypes;
    },
    [UPDATE_PRESELECTED_LOG_TYPE_ID](state, preselectedLogTypeId) {
        state.preselectedLogTypeId = preselectedLogTypeId;
    },
    [UPDATE_NOW](state, now) {
        state.now = now;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
