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
            <b-form-input
                    type="datetime-local"
                    name="when"
                    v-model="model.when"
                    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}"
                    required="required"
            />
        </b-form-group>

        <b-form-group label="Quoi ?">
            <b-form-radio-group
                    v-model="model.typeId"
                    buttons
                    button-variant="outline-primary"
                    size="lg"
                    name="type"
                    :options="types"
                    required="required"
            />
        </b-form-group>

        <div class="col-md-12" v-if="inputs">
            <h4>Dernière entrée</h4>
            <div v-if="lastUpdates[model.babyId][model.typeId]" class="mb-3">
                <ul>
                    <li>Quand: {{ lastUpdates[model.babyId][model.typeId].when}}</li>
                    <li v-for="(input, key) in lastUpdates[model.babyId][model.typeId].inputs">
                        {{ key }} : {{ input }}
                    </li>
                </ul>
            </div>
            <div v-else class="mb-3 font-italic">
                Aucune entrée pour {{ types[model.typeId].name }} chez {{ babies[babyId].text }}
            </div>

            <div v-for="input in inputs[model.typeId]">
                <div>
                    <div v-if="input.type === 'number'">
                        <div class="input-group mb-3">
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
                        <div class="input-group mb-3">
                            <label :for="input.name">
                                {{input.text}}: {{ model.inputs[input.name] }} {{ input.unit}}
                            </label>
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
                    typeId: null,
                    when: null,
                    inputs: []
                },
            }
        },
        computed: {
            ...mapGetters('log', [
                'babies',
                'babyId',
                'types',
                'typeId',
                'when',
                'inputs',
                'lastUpdates',
            ]),
        },
        async created() {
            this.isLoading = true;

            await this.$store.dispatch('log/loadAddFields');

            this.model.babyId = this.babyId;
            this.model.typeId = this.typeId;
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
                for (const input of this.inputs[this.model.typeId]) {
                    finalInputs[input.name] = this.model.inputs[input.name];
                }

                await this.$store
                        .dispatch('log/postLog', {
                            babyId: this.model.babyId,
                            typeId: this.model.typeId,
                            when: this.model.when,
                            inputs: finalInputs
                        })
                        .then(() => this.showSuccess())
                        .then(() => this.initForm())
                        .catch((e) => this.showError(e))
                ;
            },
            showSuccess() {
                this.showSuccessAlert = true;
            },
            initForm() {
                for (const input of this.inputs[this.model.typeId]) {
                    this.model.inputs[input.name] = null;
                }
                this.isSubmitting = false;
            },
            showError(e) {
                this.errorMessage = e.response.data.message;
                this.showDangerAlert = true;
                this.isSubmitting = false;
            }
        }
    };
</script>
