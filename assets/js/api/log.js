import axios from 'axios';

export default {
    getLogAddFields() {
        return axios.get('/api/log/add/fields')
            .then(resp => resp.data)
            .then(data => data.result);
    }
}
