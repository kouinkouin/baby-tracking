import axios from 'axios';

export default {
    getLogAddFields() {
        return axios
            .get('/api/log/add/fields')
            .then(resp => resp.data)
            ;
    },
    postLog(babyId, typeId, when, inputs) {
        console.log(babyId, typeId, when, inputs)
        return axios
            .post('/api/baby/' + babyId + '/logline', {
                'when': when,
                'typeId': typeId,
                'data': inputs,
            })
            .then(resp => resp.data)
            ;
    },
}
