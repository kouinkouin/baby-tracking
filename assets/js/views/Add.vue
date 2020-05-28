<template>
    <form method="post">
        <b-alert v-model="showSuccessAlert" variant="success" dismissible>
            Sauvé !
        </b-alert>

        <b-alert v-model="showDangerAlert" variant="danger" dismissible>
            Oups... une erreur :<br>
            {{ this.errorMessage }}
        </b-alert>

        <b-form-group label="Qui ?">
            <b-form-radio-group
                    v-model="model.babyId"
                    buttons
                    button-variant="outline-primary"
                    name="baby"
                    :options="babies"
                    required="required"
            />
        </b-form-group>

        <b-form-group label="Quand ?">
            <b-form-input type="datetime-local" name="datetime" v-model="model.now" required="required"/>
        </b-form-group>

        <b-form-group label="Quoi ?">
            <b-form-radio-group
                    v-model="model.logTypeId"
                    buttons
                    button-variant="outline-primary"
                    size="lg"
                    name="log_type"
                    :options="logTypes"
                    required="required"
            />
        </b-form-group>

        <div class="col-md-12" v-if="inputs">
            <div v-for="input in inputs[model.logTypeId]">
                <div>
                    <div v-if="input.type === 'number'">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ input.text }}</span>
                            </div>
                            <b-form-input
                                    :name="input.name"
                                    step="any"
                                    type="number"
                                    required="required"
                                    v-model="model.inputs[input.name]"
                            />
                            <div class="input-group-append" v-if="input.unit">
                                <span class="input-group-text">{{ input.unit }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="input.type === 'radio'">
                        <b-form-group :label="input.text">
                            <b-form-radio-group
                                    buttons
                                    button-variant="outline-secondary"
                                    :name="input.name"
                                    :options="input.choices"
                                    required="required"
                                    v-model="model.inputs[input.name]"
                            />
                        </b-form-group>
                    </div>
                    <div v-else-if="input.type === 'range'">
                        <label :for="input.name">{{input.text}}: {{ model[input.name] }} {{ input.unit }}</label>
                        <b-form-input
                                :id="input.name"
                                v-model="model.inputs[input.name]"
                                type="range"
                                required="required"
                                :min="input.min"
                                :max="input.max"
                        />
                    </div>
                </div>
            </div>
        </div>

        <button
                class="btn btn-primary"
                type="button"
                name="submit"
                :disabled="isSubmitting"
                v-on:click="submit">
            Sauver
        </button>
    </form>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'log-add',
        data() {
            return {
                isLoading: false,
                isSubmitting: false,
                showSuccessAlert: false,
                showDangerAlert: false,
                errorMessage: '',
                model: {
                    babyId: null,
                    logTypeId: null,
                    when: null,
                    inputs: []
                },
            }
        },
        computed: {
            ...mapGetters('log', [
                'babies',
                'babyId',
                'logTypes',
                'logTypeId',
                'when',
                'inputs',
            ]),
        },
        async created() {
            this.isLoading = true;

            await this.$store.dispatch('log/loadAddFields');

            this.model.babyId = this.babyId;
            this.model.logTypeId = this.logTypeId;
            this.model.when = this.when;

            this.isLoading = false;
        },
        methods: {
            async submit(e) {
                this.isSubmitting = true;
                this.showSuccessAlert = false;
                this.showDangerAlert = false;
                this.errorMessage = '';

                const finalInputs = {};
                for (const input of this.inputs[this.model.logTypeId]) {
                    finalInputs[input.name] = this.model.inputs[input.name];
                }
                console.log(finalInputs);

                await this.$store
                        .dispatch('log/postLog', {
                            babyId: this.model.babyId,
                            logTypeId: this.model.logTypeId,
                            datetime: this.model.when,
                            inputs: finalInputs
                        })
                        .then(() => {
                            for (const input of this.inputs[this.model.logTypeId]) {
                                this.model.inputs[input.name] = null;
                            }
                            this.showSuccessAlert = true;
                            this.isSubmitting = false;
                        })
                        .catch((e) => {
                            this.errorMessage = e.response.data.message;
                            this.showDangerAlert = true;
                            this.isSubmitting = false;
                        })
                ;
            },
        }
    };
</script>
