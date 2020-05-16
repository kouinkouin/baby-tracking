<template>
    <h1>toto</h1>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        components: {},
        data() {
            return {
                term: '',
                results: [],
                searched: false,
                isLoadingSearch: false
            }
        },
        computed: {
            ...mapGetters('security', [
                'isAuthenticated',
                'isLoading'
            ])
        },
        methods: {}
    }
</script>
