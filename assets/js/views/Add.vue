<template>
    <form method="post">
        <fieldset class="col-md-12">
            <legend>Qui ?</legend>
            <b-form-group label="Qui ?">
                <b-form-radio-group
                        v-model="preselectedBabyId"
                        buttons
                        button-variant="outline-primary"
                        size="lg"
                        name="baby"
                        :options="babies"/>
            </b-form-group>
        </fieldset>

        <fieldset class="col-md-12">
            <legend>Quand ?</legend>
            <b-form-input type="datetime-local" name="datetime" v-model="now"></b-form-input>
        </fieldset>

        <fieldset class="col-md-12">
            <legend>Quoi ?</legend>
            <b-form-group label="Quoi ?">
                <b-form-radio-group
                        v-model="preselectedLogTypeId"
                        buttons
                        button-variant="outline-primary"
                        size="lg"
                        name="log_type"
                        :options="logTypes"/>
            </b-form-group>
        </fieldset>

        <fieldset class="col-md-12">
            <legend>Résultats</legend>
            <div class="input-group col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Valeur</span>
                </div>
                <input id="data" type="number" step="any" name="data" class="form-control"/>
                <div class="input-group-append">
                    <span class="input-group-text"></span>
                </div>
            </div>
        </fieldset>

        <fieldset class="col-md-12">
            <button class="btn btn-primary" type="submit" name="submit">Sauver</button>
        </fieldset>
    </form>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'log-add',
        data() {
            return {
                isLoading: false,
                model: {
                    name: '',
                    email: '',
                    lang: '',
                    markets: [],
                    sectors: [],
                    capitalization: {
                        small: false,
                        medium: false,
                        big: false,
                    }
                }
            }
        },
        computed: {
            ...mapGetters('log', [
                'babies',
                'preselectedBabyId',
                'logTypes',
                'preselectedLogTypeId',
                'now',
            ]),
        },
        async created() {
            this.isLoading = true;
            await this.$store.dispatch('log/loadAddFields');
            this.isLoading = false;
        },
        methods: {
            async changeLanguage() {
//                await this.$store.dispatch('settings/updateSetting', {name: 'language', value: this.model.lang});
            },
        }
    };
</script>
