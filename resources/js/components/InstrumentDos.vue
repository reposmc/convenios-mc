<template>
  <div data-app>
    <alert-time-out
      :redirect="redirectSessionFinished"
      @redirect="updateTimeOut($event)"
    />
    <alert
      :text="textAlert"
      :event="alertEvent"
      :show="showAlert"
      @show-alert="updateAlert($event)"
      class="mb-2"
    />
    <v-data-table
      v-model="selected"
      :single-select="false"
      :search="options.search"
      :headers="headers"
      :items="recordsFiltered"
      :options.sync="options"
      :loading="loading"
      item-key="id"
      sort-by="id"
      :footer-props="{ 'items-per-page-options': [15, 30, 50, 100] }"
      class="elevation-3 shadow p-3 mt-3"
    >
    <h1></h1>
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Instrumentos 2</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="800px" persistent>
            <template v-slot:activator="{}">
              <v-row>
                <v-col align="end">
                  <v-btn
                    class="mb-2 btn-normal"
                    @click="addRecord()"
                    :disabled="loading != false"
                    rounded
                  >
                    Agregar
                  </v-btn>
                </v-col>
                <v-col
                  xs="6"
                  sm="6"
                  md="6"
                  class="d-none d-md-block d-lg-block"
                >
                  <v-text-field
                    dense
                    label="Buscar"
                    outlined
                    type="text"
                    class=""
                    v-model="options.search"
                  ></v-text-field>
                </v-col>
              </v-row>
            </template>
            <v-card class="flexcard" height="100%">
              <v-card-title>
                <h1 class="mx-auto pt-3 mb-3 text-center black-secondary">
                  {{ formTitle }}
                </h1>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <h5>Información del instrumento</h5>
                  <hr />
                  <v-row>
                    <!-- type_instrument_name -->
                    <v-col cols="12" sm="12" md="6">
                      <base-select-search
                        label="Tipo de instrumento"
                        v-model.trim="$v.editedItem.type_instrument_name.$model"
                        :items="types"
                        item="type_instrument_name"
                        :validation="$v.editedItem.type_instrument_name"
                      />
                    </v-col>
                    <!-- type_instrument_name -->
                    <!-- instrument_name -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Nombre"
                        v-model="$v.editedItem.instrument_name.$model"
                        :validation="$v.editedItem.instrument_name"
                        validationTextType="default"
                      />
                    </v-col>
                    <!-- instrument_name -->
                  </v-row>
                  <v-row>
                    <!-- entity_name -->
                    <v-col cols="12" sm="12" md="6">
                      <base-select-search
                        label="Entidad"
                        v-model.trim="$v.editedItem.entity_name.$model"
                        :items="entities"
                        item="entity_name"
                        :validation="$v.editedItem.entity_name"
                      />
                    </v-col>
                    <!-- entity_name -->
                    <!-- sector_name -->
                    <v-col cols="12" sm="12" md="6">
                      <base-select-search
                        label="Sector"
                        v-model.trim="$v.editedItem.sector_name.$model"
                        :items="sectors"
                        item="sector_name"
                        :validation="$v.editedItem.sector_name"
                      />
                    </v-col>
                    <!-- sector_name -->
                  </v-row>
                  <v-row>
                    <!-- description -->
                    <v-col cols="12" sm="12" md="12">
                      <v-textarea
                        label="Descripción del instrumento"
                        v-model="$v.editedItem.description.$model"
                        :validation="$v.editedItem.description"
                        outlined
                        rows="3"
                      ></v-textarea>
                    </v-col>
                    <!-- description -->
                  </v-row>
                  <!-- Form -->
                  <!-- Dependencies -->
                  <template>
                    <h5 class="pt-3">Dependencias</h5>
                    <hr />
                    <v-col cols="12" md="6">
                      <base-select-search
                        label="Dependencia"
                        v-model.trim="
                          $v.formDependencies.dependence_name.$model
                        "
                        :items="dependences"
                        item="dependence_name"
                        :validation="$v.formDependencies.dependence_name"
                      />
                    </v-col>
                    <v-col cols="12" md="6">
                      <a
                        href="#"
                        class="btn btn-normal"
                        @click="assignDependency"
                      >
                        Agregar
                      </a>
                    </v-col>

                    <v-simple-table class="mt-2">
                      <thead>
                        <tr>
                          <th>Dependencia</th>
                          <th>Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(
                            assigned, index
                          ) in editedItem.assignedDependencies"
                          :key="index"
                        >
                          <td>{{ assigned }}</td>
                          <td>
                            <v-icon @click="deleteAssignedDependency(index)">
                              delete
                            </v-icon>
                          </td>
                        </tr>
                      </tbody>
                    </v-simple-table>
                  </template>
                  <!-- Dependencies -->
                  <!-- save buttons -->
                  <v-row>
                    <v-col align="center">
                      <v-btn
                        color="btn-normal no-uppercase mt-3"
                        rounded
                        @click="save()"
                      >
                        Guardar
                      </v-btn>
                      <v-btn
                        color="btn-normal-close no-uppercase mt-3"
                        rounded
                        @click="close"
                      >
                        Cancelar
                      </v-btn>
                    </v-col>
                  </v-row>
                  <!-- save buttons -->
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item)"
              v-bind="attrs"
              v-on="on"
            >
              mdi-pencil
            </v-icon>
          </template>
          <span>Editar</span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              small
              class="mr-2"
              @click="addNewExoneration(item)"
              v-bind="attrs"
              v-on="on"
            >
              post_add
            </v-icon>
          </template>
          <span>Agregar exoneración</span>
        </v-tooltip>
      </template>
      <template v-slot:no-data>
        <a
          href="#"
          class="btn btn-normal-secondary no-decoration"
          @click="initialize"
        >
          Reiniciar
        </a>
      </template>
    </v-data-table>

    <v-dialog v-model="dialogExoneration" max-width="900px">
      <v-card class="h-100 p-3">
        <v-container>
          <h1 class="black-secondary text-center mt-3 mb-3">
            Agregar exoneraciones
          </h1>
          <!-- title -->
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <h5>Detalle del instrumento</h5>
              <hr />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-input
                label="Instrumento"
                v-model.trim="$v.editedItem.instrument_name.$model"
                :validation="$v.editedItem.instrument_name"
                validationTextType="default"
                :disabled="true"
              />
            </v-col>
          </v-row>
          <!-- title -->

          <!-- Diferente de convenio -->
          <v-row v-if="editedItem.type_instrument_name != 'Convenio'">
            <v-col cols="12" xs="12" sm="12" md="12">
              <base-input
                label="Concepto"
                v-model.trim="$v.formExonerations.concept.$model"
                :validation="$v.formExonerations.concept"
                validationTextType="default"
              />
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <base-input
                label="Fecha de evento"
                v-model="$v.formExonerations.date_event.$model"
                :validation="$v.formExonerations.date_event"
                validationTextType="none"
                type="date"
              />
            </v-col>
            <v-col cols="12" xs="12" sm="12" md="4">
              <base-input
                label="Valor"
                v-model.trim="$v.formExonerations.worth.$model"
                :validation="$v.formExonerations.worth"
                type="number"
                v-mask="'####'"
              />
            </v-col>
            <v-col cols="12" xs="12" sm="12" md="4">
              <base-input
                label="Monto"
                v-model.trim="$v.formExonerations.concept_amount.$model"
                :validation="$v.formExonerations.concept_amount"
                type="number"
              />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-text-area
                label="Descripción de la exoneración"
                v-model.trim="$v.formExonerations.exonerated_description.$model"
                :validation="$v.formExonerations.exonerated_description"
                :rows="3"
              ></base-text-area>
            </v-col>
          </v-row>
          <!-- Diferente de convenio -->

          <v-row v-if="editedItem.type_instrument_name == 'Convenio'">
            <v-col cols="12" sm="12" md="12">
              <h5>Detalle de la exoneración</h5>
              <hr />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select-search
                label="Dependencia"
                v-model.trim="$v.formExonerations.dependence_name.$model"
                :items="dependences"
                item="dependence_name"
                :validation="$v.formExonerations.dependence_name"
                @change="changeDependency()"
              />
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <base-input
                label="Fecha de evento"
                v-model="$v.formExonerations.date_event.$model"
                :validation="$v.formExonerations.date_event"
                validationTextType="none"
                type="date"
              />
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <base-input
                label="Espacio de servicio"
                v-model.trim="$v.formExonerations.service_place_name.$model"
                :validation="$v.formExonerations.service_place_name"
                validationTextType="default"
              />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <v-checkbox
                class="mb-3"
                v-model="$v.formExonerations.is_tariffed.$model"
                label="Monto no tarifado"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select-search
                label="Tarifa de dependencia"
                v-model.trim="$v.formExonerations.tariff_type_charge.$model"
                :items="tariffs"
                item="type_charge"
                :validation="$v.formExonerations.tariff_type_charge"
                @change="getTariffAmount()"
              />
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <h5 class="mb-0 mt-2 text-primary">
                Tarifa: ${{ this.tariff_value }}
              </h5>
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <base-input
                label="Evento/alquiler"
                v-model.number="$v.formExonerations.number_hour.$model"
                :validation="$v.formExonerations.number_hour"
                type="number"
                :disabled="this.formExonerations.number_people != ''"
              />
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <base-input
                label="Número de personas"
                v-model.number="$v.formExonerations.number_people.$model"
                :validation="$v.formExonerations.number_people"
                type="number"
                :disabled="this.formExonerations.number_hour != ''"
              />
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <h5
                class="mb-0 text-primary"
                v-if="this.formExonerations.number_people"
              >
                Total por personas: ${{ totalTariffNumberPeople }}
              </h5>
              <h5
                class="mb-0 text-primary"
                v-if="this.formExonerations.number_hour"
              >
                Total por evento/alquiler: ${{ totalTariffNumberHour }}
              </h5></v-col
            >
            <v-col cols="12" sm="12" md="12">
              <base-text-area
                label="Descripción de la exoneración"
                v-model.trim="$v.formExonerations.exonerated_description.$model"
                :validation="$v.formExonerations.exonerated_description"
                :rows="3"
              ></base-text-area>
            </v-col>
          </v-row>

          <!-- Exonerations -->
          <template>
            <v-col cols="12" md="6">
              <a
                href="#"
                class="btn btn-normal mt-3"
                @click="assingExoneration"
              >
                AGREGAR EXONERACIÓN
              </a>
            </v-col>
            <!-- table exoneration  -->
            <v-simple-table class="mt-2">
              <!-- header -->
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">Espacio</th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">¿Tarifado?</th>
                  <th v-if="editedItem.type_instrument_name != 'Convenio'">Concepto</th>
                  <th v-if="editedItem.type_instrument_name != 'Convenio'">Valor</th>
                  <th>Descripción</th>
                  <th v-if="editedItem.type_instrument_name != 'Convenio'">Monto</th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">
                    Descripción tarifa
                  </th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">Tarifa</th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">Cant. Horas/Personas</th>
                  <!-- <th>
                    Cant. Personas
                  </th> -->
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">Total</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <!-- header -->
              <!-- body -->
              <tbody>
                <tr
                  v-for="(assigned, index) in editedItem.assignedExonerations"
                  :key="index"
                >
                  <td v-if="assigned.date_event">
                    {{ assigned.date_event }}
                  </td>
                  <td v-if="assigned.service_place_name ">
                    {{ assigned.service_place_name }}
                  </td>
                  <td v-if="assigned.is_tariffed ">
                    {{ assigned.is_tariffed }}
                  </td>
                  <td v-if="assigned.concept ">
                    {{ assigned.concept }}
                  </td>
                  <td v-if="assigned.worth ">
                    {{ assigned.worth }}
                  </td>
                  <td v-if="assigned.exonerated_description">
                    {{ assigned.exonerated_description }}
                  </td>
                  <td v-if="assigned.concept_amount ">
                    {{ assigned.concept_amount }}
                  </td>
                  <td v-if="assigned.tariff_type_charge ">
                    {{ assigned.tariff_type_charge }}
                  </td>
                  <td v-if="assigned.tariff_amount ">
                    {{ assigned.tariff_amount }}
                  </td>
                  <td v-if="assigned.number_hour ">
                    {{ assigned.number_hour }}
                  </td>
                  <td v-if="assigned.number_people ">
                    {{ assigned.number_people }}
                  </td>
                  <td >
                    {{ assigned.tafiff_total_amount }}</td>
                  <td>
                    <v-icon @click="deleteAssignedExoneration(index)">
                      delete
                    </v-icon>
                  </td>
                </tr>
              </tbody>
              <!-- body -->
            </v-simple-table>
            <!-- table exoneration  -->
          </template>
          <!-- Exonerations -->
        </v-container>

        <!-- save exoneration -->
        <v-container>
          <v-row>
            <v-col align="center">
              <v-btn
                color="btn-normal no-uppercase mt-3 mb-3 pr-5 pl-5 mx-auto"
                rounded
                @click="addExoneration()"
              >
                Guardar
              </v-btn>
              <v-btn
                color="btn-normal-close no-uppercase mt-3 mb-3"
                rounded
                @click="closeExoneration()"
              >
                Cerrar
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
        <!-- save exoneration -->
      </v-card>
    </v-dialog>
    <!--form detail-->
  </div>
</template>

<script>
import instrumentApi from "../apis/instrumentApi";
import entityApi from "../apis/entityApi";
import roleApi from "../apis/roleApi";
import typeApi from "../apis/typeApi";
import directionApi from "../apis/directionApi";
import dependenceApi from "../apis/dependenceApi";
import placeApi from "../apis/placeApi";
import sectorApi from "../apis/sectorApi";
import tariffApi from "../apis/tariffApi";
import exonerationApi from "../apis/exonerationApi";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import axios from "axios";
import BaseInput from "./base-components/BaseInput.vue";
import { title } from "process";

export default {
  components: { BaseInput },
  data() {
    return {
      search: "",
      dialog: false,
      showExoneration: false,
      dialogExoneration: false,
      viewExonerations: false,
      hidden: false,
      showModal: false,
      dialogDelete: false,
      dialogAddPlace: false,
      headers: [
        { text: "INSTRUMENTO", value: "instrument_name" },
        { text: "TIPO DE INSTRUMENTO", value: "type_instrument_name" },
        { text: "ENTIDAD", value: "entity_name" },
        { text: "SECTOR", value: "sector_name" },
        // { text: "DESCRIPCIÓN", value: "description" },
        { text: "ACCIONES", value: "actions", sortable: false },
      ],
      options: {},
      selected: [],
      records: [],
      totalItems: 0,
      recordsFiltered: [],
      editedIndex: -1,
      editedItem: {
        instrument_name: "",
        description: "",
        type_instrument_name: "",
        entity_name: "",
        amount_people: "",
        not_charged_hour: "",
        tariff_people: "",
        not_charged_people: "",
        sector_name: "",
        concept: "",
        worth: "",
        concept_amount: "",
        assignedDependencies: [],
        assignedExonerations: [],
      },
      defaultItem: {
        instrument_name: "",
        description: "",
        type_instrument_name: "",
        entity_name: "",
        national_direction_name: "",
        amount_people: "",
        not_charged_hour: "",
        tariff_people: "",
        not_charged_people: "",
        sector_name: "",
        concept: "",
        worth: "",
        concept_amount: "",
        assignedDependencies: [],
        assignedExonerations: [],
      },
      formDependencies: {
        dependence_name: "",
      },
      formExonerations: {
        concept: "",
        worth: "",
        concept_amount: "",
        dependence_name: "",
        date_event: "",
        service_place_name: "",
        is_tariffed: false,
        tariff_type_charge: "",
        tariff_amount: 0,
        number_hour: "",
        number_people: "",
        tafiff_total_amount: 0,
        exonerated_description: "",
      },
      defaultFormExonerations: {
        concept: "",
        worth: "",
        concept_amount: "",
        dependence_name: "",
        date_event: "",
        service_place_name: "",
        is_tariffed: false,
        tariff_type_charge: "",
        tariff_amount: 0,
        number_hour: "",
        number_people: "",
        tafiff_total_amount: 0,
        exonerated_description: "",
      },
      textAlert: "",
      alertEvent: "success",
      showAlert: false,
      exonerations: [],
      types: [],
      entities: [],
      directions: [],
      places: [],
      tariffs: [],
      dependences: [],
      sectors: [],
      redirectSessionFinished: false,
      alertTimeOut: 0,
      debounce: 0,
      selectedTab: 0,
      tariff_value: 0,
    };
  },

  //Validations
  validations: {
    editedItem: {
      instrument_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      type_instrument_name: {
        required,
      },
      description: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      entity_name: {
        required,
      },
      sector_name: {
        required,
      },
      national_direction_name: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      not_charged_hour: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      tariff_people: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      not_charged_people: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      amount_hour: {
        minLength: minLength(1),
      },
      amount_people: {
        minLength: minLength(1),
      },
      assignedDependencies: {
        required,
      },
      assignedExonerations: {
        required,
      },
    },
    formDependencies: {
      dependence_name: {
        required,
      },
    },
    formExonerations: {
      concept: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      worth: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      concept_amount: {
        minLength: minLength(1),
      },
      dependence_name: { required },
      date_event: { required },
      service_place_name: {},
      is_tariffed: {},
      tariff_type_charge: { required },
      tariff_amount: {},
      number_hour: {},
      tafiff_total_amount: {},
      number_people: {},
      exonerated_description: { required },
    },
  },

  //Validations
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
    isUsuarioRole() {
      return this.roles.some((role) => role.name === "Usuario");
    },
    totalTariffNumberPeople: function () {
      if (
        !isNaN(parseFloat(this.tariff_value)) &&
        !isNaN(parseFloat(this.formExonerations.number_people))
      ) {
        return (
          parseFloat(this.tariff_value) *
          parseFloat(this.formExonerations.number_people)
        );
      } else {
        return 0;
      }
    },
    totalTariffNumberHour: function () {
      if (
        !isNaN(parseFloat(this.tariff_value)) &&
        !isNaN(parseFloat(this.formExonerations.number_hour))
      ) {
        return (
          parseFloat(this.tariff_value) *
          parseFloat(this.formExonerations.number_hour)
        );
      } else {
        return 0;
      }
    },
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: false,
      dirty: false,
    },
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    async initialize() {
      this.loading = true;
      this.records = [];
      this.recordsFiltered = [];

      let requests = [
        this.getDataFromApi(),
        typeApi.get(),
        directionApi.get(),
        entityApi.get(),
        dependenceApi.get(),
        sectorApi.get(),
        roleApi.get(),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      this.types = responses[1].data.types;
      this.directions = responses[2].data.directions;
      this.entities = responses[3].data.entities;
      this.dependences = responses[4].data.dependences;
      this.sectors = responses[5].data.sectors;
      this.roles = responses[6].data.roles;

      this.recordsFiltered = this.records;
      this.loading = false;
    },

    addRecord() {
      this.dialog = true;
      this.editedIndex = -1;
      this.selectedTab = 0;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.$v.$reset();
    },

    async save() {
      this.$v.editedItem.$touch();

      if (this.$v.editedItem.$invalid) {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      //Update instrument
      if (this.editedIndex > -1) {
        const res = await instrumentApi
          .put(`/${this.editedItem.id}`, this.editedItem)
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible modificar el registro.",
              "fail"
            );
            this.close();
            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
          });

        if (res.data.status == "success") {
          this.updateAlert(
            true,
            "Registro modificado correctamente.",
            "success"
          );
        }
      } else {
        //Create instrument
        const res = await instrumentApi
          .post(null, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");

            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
          });

        if (res.success) {
          this.updateAlert(true, data.message, "success");
        }

        this.close();
        this.initialize();
        return;
      }
    },

    editItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeExoneration() {
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
      });
      this.$v.formExonerations.$reset();
      this.dialogExoneration = false;
    },

    addNewExoneration(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogExoneration = true;
    },

    async saveExoneration() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      const res = await exonerationApi
        .post(null, this.editedItem)
        .catch((error) => {
          this.updateAlert(true, "No fue posible crear el registro.", "fail");
        });

      if (res.data.status == "success") {
        this.updateAlert(true, "Registro almacenado correctamente.", "success");
      }

      this.close();
    },

    async changeDirection() {
      let { data } = await axios
        .get(
          "api/dependence/byDirectionName/" +
            this.editedItem.national_direction_name
        )
        .catch((error) => {
          this.$emit("update-alert", {
            show: true,
            message:
              "No fue posible obtener la información de las dependencias. ",
            type: "fail",
          });
        });
      this.dependences = data.dependences;
    },

    async changeDependency() {
      let { data } = await axios
        .get(
          "api/web/tariff/byDependencyName/" +
            this.formExonerations.dependence_name
        )
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible obtener la información de los espacios.",
            "fail"
          );
        });

      this.tariffs = data.tariffs;
    },

    async getTariffAmount() {
      let { data } = await axios
        .get(
          "/api/web/tariff/byTariffTypeCharge/" +
            this.formExonerations.tariff_type_charge
        )
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible obtener la información de los espacios.",
            "fail"
          );
        });

      this.tariff_value = parseFloat(data.tariff_amount);
    },

    getDataFromApi() {
      this.loading = true;
      this.records = [];
      this.recordsFiltered = [];

      //debounce
      clearTimeout(this.debounce);
      this.debounce = setTimeout(async () => {
        const { data } = await instrumentApi
          .get(null, {
            params: this.options,
          })
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible obtener los registros.",
              "fail"
            );
          });

        this.records = data.records;
        this.recordsFiltered = data.records;
        this.total = data.total;
        this.loading = false;
      }, 500);
    },

    async addExoneration() {
      
    },

    assingExoneration() {
      this.$v.formExonerations.$touch();

      if (this.$v.formExonerations.$invalid) {
        return;
      }

      this.formExonerations.tariff_amount = this.tariff_value;

      //setting tariff value if true/false
      if (this.formExonerations.is_tariffed == true) {
        this.formExonerations.is_tariffed = "No tarifado";
      } else {
        this.formExonerations.is_tariffed = "Tarifado";
      }

      //total by number hour/number people
      if (this.formExonerations.number_hour !== "") {
        this.formExonerations.tafiff_total_amount =
          this.formExonerations.tariff_amount *
          this.formExonerations.number_hour;
      } else {
        this.formExonerations.tafiff_total_amount =
          this.formExonerations.tariff_amount *
          this.formExonerations.number_people;
      }

      //push
      this.editedItem.assignedExonerations.push({
        ...this.formExonerations,
      });

      //clear inputs
      this.formExonerations = this.defaultFormExonerations;
      this.tariff_value = 0;

      //reset alerts
      this.$v.formExonerations.$reset();
    },

    deleteAssignedExoneration(index) {
      this.editedItem.assignedExonerations.splice(index, 1);
    },

    assignDependency() {
      this.$v.formDependencies.$touch();

      if (this.$v.formDependencies.$invalid) {
        return;
      }

      this.editedItem.assignedDependencies.push(
        this.formDependencies.dependence_name
      );
      this.formDependencies.dependence_name = "";

      this.$v.formDependencies.$reset();
    },

    deleteAssignedDependency(index) {
      this.editedItem.assignedDependencies.splice(index, 1);
      this.formDependencies.dependence_name = "";
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