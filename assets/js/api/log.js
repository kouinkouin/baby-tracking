import axios from 'axios';

export default {
    getLogAddFields() {
        return axios
            .get('/api/log/add/fields')
            .then(resp => resp.data)
            ;
    },
    postLog(babyId, logTypeId, datetime, inputs) {
        console.log(babyId, logTypeId, datetime, inputs)
        return axios
            .post('/api/baby/' + babyId + '/logline', {
                'creationDatetime': datetime,
                'typeId': logTypeId,
                'data': inputs,
            })
            .then(resp => resp.data)
            ;
    },
}
