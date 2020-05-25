<template>
    <form method="post">
        <b-form-group label="Qui ?">
            <b-form-radio-group
                    v-model="model.selectedBabyId"
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
                    v-model="model.selectedLogTypeId"
                    buttons
                    button-variant="outline-primary"
                    size="lg"
                    name="log_type"
                    :options="logTypes"
                    required="required"
            />
        </b-form-group>

        <div class="col-md-12" v-if="inputs">
            <div v-for="input in inputs[model.selectedLogTypeId]">
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
                model: {
                    selectedBabyId: null,
                    selectedLogTypeId: null,
                    now: null,
                    inputs: []
                },
            }
        },
        computed: {
            ...mapGetters('log', [
                'babies',
                'selectedBabyId',
                'logTypes',
                'selectedLogTypeId',
                'now',
                'inputs',
            ]),
        },
        async created() {
            this.isLoading = true;

            await this.$store.dispatch('log/loadAddFields');

            console.log(this.selectedBabyId);
            this.model.selectedBabyId = this.selectedBabyId;
            this.model.selectedLogTypeId = this.selectedLogTypeId;
            this.model.now = this.now;

            this.isLoading = false;
        },
        methods: {
            async submit(e) {
                this.isSubmitting = true;
                const finalInputs = {};
                for (const input of this.inputs[this.model.selectedLogTypeId]) {
                    finalInputs[input.name] = this.model.inputs[input.name];
                }
                console.log(finalInputs);

                await this.$store
                        .dispatch('log/postLog', {
                            babyId: this.model.selectedBabyId,
                            logTypeId: this.model.selectedLogTypeId,
                            datetime: this.model.now,
                            inputs: finalInputs
                        })
                        .then(e => this.isSubmitting = false)
                ;
            },
        }
    };
</script>
