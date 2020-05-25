import logApi from '../../api/log';

const UPDATE_BABIES = 'UPDATE_BABIES';
const UPDATE_SELECTED_BABY_ID = 'UPDATE_SELECTED_BABY_ID';
const UPDATE_LOG_TYPES = 'UPDATE_LOG_TYPES';
const UPDATE_SELECTED_LOG_TYPE_ID = 'UPDATE_SELECTED_LOG_TYPE_ID';
const UPDATE_NOW = 'UPDATE_NOW';
const UPDATE_INPUTS = 'UPDATE_INPUTS';

const state = {
    babies: [],
    selectedBabyId: null,
    logTypes: [],
    selectedLogTypeId: null,
    now: null,
    inputs: null,
};

const actions = {
    loadAddFields({commit}) {
        logApi.getLogAddFields()
            .then(result => {
                commit(UPDATE_BABIES, result.babies);
                commit(UPDATE_SELECTED_BABY_ID, result.selectedBabyId);
                commit(UPDATE_LOG_TYPES, result.logTypes);
                commit(UPDATE_SELECTED_LOG_TYPE_ID, result.selectedLogTypeId);
                commit(UPDATE_NOW, result.now);
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
    selectedBabyId(state) {
        return state.selectedBabyId;
    },
    logTypes(state) {
        return state.logTypes;
    },
    selectedLogTypeId(state) {
        return state.selectedLogTypeId;
    },
    now(state) {
        return state.now;
    },
    inputs(state) {
        return state.inputs;
    },
};

const mutations = {
    [UPDATE_BABIES](state, value) {
        state.babies = value;
    },
    [UPDATE_SELECTED_BABY_ID](state, value) {
        state.selectedBabyId = value;
    },
    [UPDATE_LOG_TYPES](state, value) {
        state.logTypes = value;
    },
    [UPDATE_SELECTED_LOG_TYPE_ID](state, value) {
        state.selectedLogTypeId = value;
    },
    [UPDATE_NOW](state, value) {
        state.now = value;
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
