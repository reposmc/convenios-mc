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
                    <!-- Agreement -->
                    <v-col cols="12" sm="12" md="12">
                        <base-select-search
                            label="Convenio"
                            v-model.trim="$v.parameters.agreement_name.$model"
                            :items="agreements"
                            item="agreement_name"
                            :validation="$v.parameters.agreement_name"
                        />
                    </v-col>
                    <!-- Agreement -->
                </v-row>
                <v-row class="mx-auto">
                    <!-- Date -->
                        <v-col cols="12" sm="12" md="6" v-show="hidden"> 
                          <base-input
                            label="Fecha de inicio"
                            v-model="$v.parameters.dateOne.$model"
                            :validation="$v.parameters.dateOne"
                            validationTextType="none"
                            type="date"
                            />
                        </v-col>
                        <v-col cols="12" sm="12" md="6" v-show="hidden">
                            <base-input
                             label="Fecha final"
                             v-model="$v.parameters.dateTwo.$model"
                             :validation="$v.parameters.dateTwo"
                             validationTextType="none"
                             type="date"
                            />
                        </v-col>   
                </v-row>
                <v-row class="mx-auto">
                      <v-checkbox
                        @click="hidden = !hidden"
                        :label="`Reporte por período`"
                      >
                      </v-checkbox>
                </v-row>
                <br>
                <v-row class="mx-auto">
                    <v-btn
                        class="btn btn-normal mb-3 mt-1"
                        @click="generateReport"
                    >
                    GENERAR REPORTE
                    </v-btn>
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>
</template>

<script>
import agreementApi from "../apis/agreementApi";
import BaseInput from "./base-components/BaseInput.vue";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import axios from "axios";

export default {
    components: { BaseInput },
    data: () => ({
        records: [],
        recordsFiltered: [],
        editedIndex: -1,
        parameters: {
            agreement_name: "",
            dateOne: "",
            dateTwo: "",
        },
        textAlert: "",
        alertEvent: "success",
        showAlert: false,
        agreements: [],
        redirectSessionFinished: false,
        alertTimeOut: 0,
        hidden: false,
    }),

    //Validations
    validations: {
        parameters: {
            agreement_name: {
                required,
            },
            dateOne: {
                minLength: minLength(1),
            },
            dateTwo: {
                minLength: minLength(1),
            },
        },
    },

    mounted() {
        this.initialize();
    },

    methods: {
        async initialize() {
            this.records = [];
            this.recordsFiltered = [];

            let requests = [agreementApi.get()];
            let responses = await Promise.all(requests).catch((error) => {
                this.updateAlert(true, "No fue posible obtener los registros.", "fail");
                this.redirectSessionFinished = lib.verifySessionFinished(
                    error.response.status,
                    401
                );
            });

            this.agreements = responses[0].data.agreements;
            this.recordsFiltered = this.records;
        },

        async generateReport() {
            console.log(this.parameters);
            if(this.parameters){
                window.open(`/pdf/reports?agreement_name=${this.parameters.agreement_name}&&dateOne=${this.parameters.dateOne}&&dateTwo=${this.parameters.dateTwo}`);
                return;
            }
        },
    },
};
</script>

<script>
import agreementApi from "../apis/agreementApi";
import BaseInput from "./base-components/BaseInput.vue";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import axios from "axios";
export default {
    components: { BaseInput },
    data: () => ({
        records: [],
        recordsFiltered: [],
        editedIndex: -1,
        parameters: {
            agreement_name: "",
            dateOne: "",
            dateTwo: "",
        },
        textAlert: "",
        alertEvent: "success",
        showAlert: false,
        agreements: [],
        redirectSessionFinished: false,
        alertTimeOut: 0,
        hidden: false,
    }),
    //Validations
    validations: {
        parameters: {
            agreement_name: {
                required,
            },
            dateOne: {
                minLength: minLength(1),
            },
            dateTwo: {
                minLength: minLength(1),
            },
        },
    },
    mounted() {
        this.initialize();
    },
    methods: {
        async initialize() {
            this.records = [];
            this.recordsFiltered = [];
            let requests = [agreementApi.get()];
            let responses = await Promise.all(requests).catch((error) => {
                this.updateAlert(true, "No fue posible obtener los registros.", "fail");
                this.redirectSessionFinished = lib.verifySessionFinished(
                    error.response.status,
                    401
                );
            });
            this.agreements = responses[0].data.agreements;
            this.recordsFiltered = this.records;
        },
        async generateReport() {
            console.log(this.parameters);
            if(this.parameters){
                window.open(`/pdf/reports?agreement_name=${this.parameters.agreement_name}&&dateOne=${this.parameters.dateOne}&&dateTwo=${this.parameters.dateTwo}`);
                return;
            }
        },
    },
};
</script>