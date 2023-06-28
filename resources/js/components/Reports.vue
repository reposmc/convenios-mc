<template>
    <v-card class="mx-auto" height="100%" width="60%">
        <v-card-title>
            <h1 class="black-secondary">
                Reportes
            </h1>
        </v-card-title>
        <v-card-text>
            <v-divider></v-divider>
            <v-container>
                <v-row class="mx-auto">
                    <v-col cols="12" sm="12" md="12">
                        <base-select-search label="Tipo de Reporte"
                            :items="['Instrumento', 'Dirección Nacional', 'General',]"
                            :validation="$v.parameters.kind_of_report" />
                    </v-col>
                    <!-- <v-col cols="12" sm="12" md="12" v-if="parameters.kindOfReport == 'General'">
                        <base-select-search label="Institución" v-model.trim="$v.parameters.entity_name.$model"
                            :items="entities" item="entity_name" :validation="$v.parameters.entity_name" />
                    </v-col> -->
                    <v-col cols="12" sm="12" md="12" v-if="parameters.kind_of_report == 'Dirección Nacional'">
                        <base-select-search label="Dirección Nacional"
                            v-model.trim="$v.parameters.national_direction_name.$model" :items="nationals"
                            item="national_direction_name" :validation="$v.parameters.national_direction_name" />
                    </v-col>
                    <v-col cols="12" sm="12" md="12" v-if="parameters.kind_of_report == 'Instrumento'">
                        <base-select-search label="Instrumento" v-model.trim="$v.parameters.instrument_name.$model"
                            :items="instruments" item="instrument_name" :validation="$v.parameters.instrument_name" />
                    </v-col>
                    <v-col cols="12" sm="12" md="6">
                        <base-input label="Fecha inicial" v-model="$v.parameters.dateOne.$model"
                            :validation="$v.parameters.dateOne" validationTextType="none" type="date" />
                    </v-col>
                    <v-col cols="12" sm="12" md="6">
                        <base-input label="Fecha final" v-model="$v.parameters.dateTwo.$model"
                            :validation="$v.parameters.dateTwo" validationTextType="none" type="date" />
                    </v-col>
                </v-row>
                <br>
                <v-row class="mx-auto">
                    <v-btn class="btn btn-normal mb-3 mt-1" @click="generateReport">
                        GENERAR REPORTE
                    </v-btn>
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>
</template>
<script>

import reportsApi from "../apis/reportsApi"
import BaseInput from "./base-components/BaseInput.vue";
import lib from "../libs/function";
import { required, minLength, maxLength, requiredIf } from "vuelidate/lib/validators";
import axios from "axios";

export default {
    components: { BaseInput },
    data: () => ({
        records: [],
        recordsFiltered: [],
        editedIndex: -1,
        parameters: {
            national_direction_name: "",
            dateOne: "",
            dateTwo: "",
            instrument_name: "",
            kind_of_report: "",
        },
        textAlert: "",
        alertEvent: "success",
        showAlert: false,
        instruments: [],
        entities: [],
        nationals: [],
        redirectSessionFinished: false,
        alertTimeOut: 0,
        hidden: false,
        tab: null,
        isChecked: false
    }),

    //Validations
    validations: {
        parameters: {
            national_direction_name: {
                required: requiredIf(function () {
                    return (
                        this.parameters.kind_of_report == "Dirección Nacional" && this.parameters.instrument_name == ""
                    );
                }),
                minLength: minLength(1),
                maxLength: maxLength(150),
            },
            dateOne: {
                required,
                minLength: minLength(1),
                maxLength: maxLength(150),
            },
            dateTwo: {
                required,
                minLength: minLength(1),
                maxLength: maxLength(150),
            },
            instrument_name: {
                required: requiredIf(function () {
                    return (
                        this.parameters.kind_of_report == "Instrumento" && this.parameters.national_direction_name == ""
                    );
                }),
                minLength: minLength(1),
                maxLength: maxLength(150),
            },
            kind_of_report: {
                required: requiredIf(function () {
                    return (
                        this.parameters.instrument_name == "" && this.parameters.national_direction_name == ""
                    );
                }),
            },
        },
    },

    mounted() {
        this.initialize();
    },

    methods: {
        async initialize() {
            let requests = [reportsApi.get()];
            let responses = await Promise.all(requests).catch((error) => {
                this.updateAlert(true, "No fue posible obtener los registros.", "fail");
                this.redirectSessionFinished = lib.verifySessionFinished(
                    error.response.status,
                    401
                );
            });

            this.nationals = responses[0].data.nationals;
            this.instruments = responses[0].data.instruments;
            this.entities = responses[0].data.entities;
        },

        async generateReport() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.updateAlert(true, "Campos obligatorios.", "fail");
                return;
            }

            if (this.parameters) {
                window.open(`/pdf/reports?instrument_name=${this.parameters.instrument_name}&&national_direction_name=${this.parameters.national_direction_name}&&kind_of_report=${this.parameters.kind_of_report}&&dateOne=${this.parameters.dateOne}&&dateTwo=${this.parameters.dateTwo}`);
                this.parameters.national_direction_name = '';
                this.parameters.instrument_name = '';
                this.parameters.kind_of_report = '';
                this.parameters.dateOne = '';
                this.parameters.dateTwo = '';
                this.parameters.selectedOrder = '';
                window.location.reload();
                return;
            }
        },

        updateAlert(show = false, text = "Alerta", event = "success") {
            this.textAlert = text;
            this.alertEvent = event;
            this.showAlert = show;
        },

        updateTimeOut(event) {
            this.redirectSessionFinished = event;
        },
    },
};
</script>