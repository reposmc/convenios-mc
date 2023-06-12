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
                        <base-select-search label="Institución" v-model.trim="$v.parameters.entity_name.$model"
                            :items="entities" item="entity_name" :validation="$v.parameters.entity_name"
                            @change="changeEntity()" />
                    </v-col>
                    <v-col cols="12" sm="12" md="6" v-show="hidden">
                        <base-select-search label="Dirección Nacional"
                            v-model.trim="$v.parameters.national_direction_name.$model" :items="nationals"
                            item="national_direction_name" :validation="$v.parameters.national_direction_name" />
                    </v-col>
                    <v-col cols="12" sm="12" md="6" v-show="!hidden">
                        <base-select-search label="Servicio" v-model.trim="$v.parameters.service_place_name.$model"
                            :items="exonerations" item="service_place_name"
                            :validation="$v.parameters.service_place_name" />
                    </v-col>
                    <v-col cols="12" sm="12" md="6">
                        <base-input label="Fecha" v-model="$v.parameters.dateOne.$model" :validation="$v.parameters.dateOne"
                            validationTextType="none" type="date" />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" sm="12" md="12">
                        <v-checkbox @click="hidden = !hidden" :label="`Reporte por Dirección Nacional`">
                        </v-checkbox>
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
import exonerationApi from "../apis/exonerationApi";
import entityApi from "../apis/entityApi";
import instrumentApi from "../apis/instrumentApi";
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
            service_place_name: "",
            dateOne: "",
            entity_name: "",
        },
        textAlert: "",
        alertEvent: "success",
        showAlert: false,
        nationals: [],
        exonerations: [],
        entities: [],
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
                required: requiredIf(function (parameters) {
                    return (
                        this.parameters.service_place_name == ""
                    );
                }),
                minLength: minLength(1),
                maxLength: maxLength(150),
            },
            service_place_name: {
                required: requiredIf(function (parameters) {
                    return (
                        this.parameters.national_direction_name == ""
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
            entity_name: {
                required,
                minLength: minLength(1),
                maxLength: maxLength(150),
            },
        },
    },

    mounted() {
        this.initialize();
    },

    methods: {
        async initialize() {
            let requests = [reportsApi.get(), exonerationApi.get(), entityApi.get()];
            let responses = await Promise.all(requests).catch((error) => {
                this.updateAlert(true, "No fue posible obtener los registros.", "fail");
                this.redirectSessionFinished = lib.verifySessionFinished(
                    error.response.status,
                    401
                );
            });

            this.nationals = responses[0].data.nationals;
            this.exonerations = responses[1].data.exonerations;
            this.entities = responses[2].data.entities;
        },

        async generateReport() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.updateAlert(true, "Campos obligatorios.", "fail");
                return;
            }

            console.log(this.parameters);
            if (this.parameters) {
                window.open(`/pdf/reports?national_direction_name=${this.parameters.national_direction_name}&&service_place_name=${this.parameters.service_place_name}&&dateOne=${this.parameters.dateOne}&&entity_name=${this.parameters.entity_name}`);
                this.parameters.national_direction_name = '';
                this.parameters.entity_name = '';
                this.parameters.service_place_name = '';
                this.parameters.dateOne = '';
                window.location.reload();
                return;
            }
        },

        async changeEntity() {
            try {
                const response = await axios.get(`api/web/exonerations/byEntityName/${this.parameters.entity_name}`);
                const servicePlaces = response.data.exonerations;

                for (let i = 0; i < servicePlaces.length; i++) {
                    if (!servicePlaces[i]) {
                        servicePlaces[i] = this.parameters.concept;
                    }
                }

                this.exonerations = servicePlaces;
            } catch (error) {
                this.updateAlert(true, 'No fue posible obtener la información de los espacios.', 'fail');
            }
        },

        /* async changeEntity() {
            let { data } = await axios
                .get(
                    "api/web/exoneration/byEntityName/" +
                    this.parameters.entity_name
                )
                .catch((error) => {
                    this.updateAlert(
                        true,
                        "No fue posible obtener la información de los espacios.",
                        "fail"
                    );
                });

            this.exonerations = data.exonerations;
        }, */

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