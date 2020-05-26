import logApi from '../../api/log';

const UPDATE_BABIES = 'UPDATE_BABIES';
const UPDATE_BABY_ID = 'UPDATE_BABY_ID';
const UPDATE_LOG_TYPES = 'UPDATE_LOG_TYPES';
const UPDATE_LOG_TYPE_ID = 'UPDATE_LOG_TYPE_ID';
const UPDATE_WHEN = 'UPDATE_WHEN';
const UPDATE_INPUTS = 'UPDATE_INPUTS';

const state = {
    babies: [],
    babyId: null,
    logTypes: [],
    logTypeId: null,
    when: null,
    inputs: null,
};

const actions = {
    loadAddFields({commit}) {
        logApi.getLogAddFields()
            .then(result => {
                commit(UPDATE_BABIES, result.babies);
                commit(UPDATE_BABY_ID, result.babyId);
                commit(UPDATE_LOG_TYPES, result.logTypes);
                commit(UPDATE_LOG_TYPE_ID, result.logTypeId);
                commit(UPDATE_WHEN, result.when);
                commit(UPDATE_INPUTS, result.inputs);
            });
    },
    postLog({commit, dispatch}, {babyId, logTypeId, datetime, inputs}) {
        logApi.postLog(babyId, logTypeId, datetime, inputs);
    }
};

const getters = {
    babies(state) {
        return state.babies;
    },
    babyId(state) {
        return state.babyId;
    },
    logTypes(state) {
        return state.logTypes;
    },
    logTypeId(state) {
        return state.logTypeId;
    },
    when(state) {
        return state.when;
    },
    inputs(state) {
        return state.inputs;
    },
};

const mutations = {
    [UPDATE_BABIES](state, value) {
        state.babies = value;
    },
    [UPDATE_BABY_ID](state, value) {
        state.babyId = value;
    },
    [UPDATE_LOG_TYPES](state, value) {
        state.logTypes = value;
    },
    [UPDATE_LOG_TYPE_ID](state, value) {
        state.logTypeId = value;
    },
    [UPDATE_WHEN](state, value) {
        state.when = value;
    },
    [UPDATE_INPUTS](state, value) {
        state.inputs = value;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
