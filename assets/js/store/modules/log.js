import logApi from '../../api/log';

const UPDATE_BABIES = 'UPDATE_BABIES';
const UPDATE_BABY_ID = 'UPDATE_BABY_ID';
const UPDATE_TYPES = 'UPDATE_TYPES';
const UPDATE_TYPE_ID = 'UPDATE_TYPE_ID';
const UPDATE_WHEN = 'UPDATE_WHEN';
const UPDATE_INPUTS = 'UPDATE_INPUTS';

const state = {
    babies: [],
    babyId: null,
    types: [],
    typeId: null,
    when: null,
    inputs: null,
};

const actions = {
    loadAddFields({commit}) {
        return logApi.getLogAddFields()
            .then(result => {
                commit(UPDATE_BABIES, result.babies);
                commit(UPDATE_BABY_ID, result.babyId);
                commit(UPDATE_TYPES, result.types);
                commit(UPDATE_TYPE_ID, result.typeId);
                commit(UPDATE_WHEN, result.when);
                commit(UPDATE_INPUTS, result.inputs);
            });
    },
    postLog({commit, dispatch}, {babyId, typeId, when, inputs}) {
        return logApi.postLog(babyId, typeId, when, inputs);
    }
};

const getters = {
    babies(state) {
        return state.babies;
    },
    babyId(state) {
        return state.babyId;
    },
    types(state) {
        return state.types;
    },
    typeId(state) {
        return state.typeId;
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
    [UPDATE_TYPES](state, value) {
        state.types = value;
    },
    [UPDATE_TYPE_ID](state, value) {
        state.typeId = value;
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
