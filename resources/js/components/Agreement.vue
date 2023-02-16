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
      :headers="headers"
      :items="recordsFiltered"
      sort-by="name"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Convenios</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="1400px" persistent>
            <template v-slot:activator="{ on, attrs }">
              <v-row>
                <v-col align="end">
                  <v-btn
                    class="mb-2 btn-normal"
                    v-bind="attrs"
                    v-on="on"
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
                    v-model="search"
                    @keyup="searchValue()"
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
                  <h5>Información del convenio</h5>
                  <v-divider></v-divider>
                  <v-row>
                    <!-- Agreement Name -->
                    <v-col cols="12" sm="12" md="3">
                      <base-input
                        label="Nombre"
                        v-model="$v.editedItem.agreement_name.$model"
                        :validation="$v.editedItem.agreement_name"
                        validationTextType="default"
                      />
                    </v-col>
                    <!-- Agreement Name -->
                    <!-- Agreement type -->
                    <v-col cols="12" sm="12" md="3">
                      <base-select-search
                        label="Tipo de convenio"
                        v-model.trim="$v.editedItem.type_agreement_name.$model"
                        :items="types"
                        item="type_agreement_name"
                        :validation="$v.editedItem.type_agreement_name"
                      />
                    </v-col>
                    <!-- Agreement type -->
                    <!-- Agreement:Entity -->
                    <v-col cols="12" sm="12" md="3">
                      <base-select-search
                        label="Entidad"
                        v-model.trim="$v.editedItem.entity_name.$model"
                        :items="entities"
                        item="entity_name"
                        :validation="$v.editedItem.entity_name"
                      />
                    </v-col>
                    <!-- Agreement:Entity -->
                    <!-- Agreement description -->
                    <v-col cols="12" sm="12" md="3">
                      <v-textarea
                        label="Descripción del convenio"
                        v-model="$v.editedItem.description.$model"
                        :validation="$v.editedItem.description"
                        auto-grow
                        outlined
                        rows="1"
                        row-height="15"
                        dense
                      ></v-textarea>
                    </v-col>
                    <!-- Agreement description -->
                  </v-row>
                  <v-row>
                    <v-col align="start">
                      <v-btn
                        color="btn btn-normal mb-3 mt-3"
                        rounded
                        v-if="editedItem.hour != ''"
                        @click="openExonerationModal()"
                      >
                        Agregar Exoneración
                      </v-btn>
                    </v-col>
                  </v-row>
                  <!-- Form -->
                  <v-row>
                    <v-col cols="12" lg="12" md="12" xs="12">
                      <!-- Academic Level -->
                      <div class="table-responsive-md">
                        <table
                          class="table table-responsive-md table-hover"
                          v-if="editedItem.hour != ''"
                        >
                          <thead>
                            <th>Especio/Elenco</th>
                            <th>Fechas exoneradas</th>
                            <th>Horas exoneradas</th>
                            <th>Número de personas</th>
                            <th>Tarifa ($)</th>
                            <th>Monto exonerado ($)</th>
                            <th class="text-center">Acciones</th>
                          </thead>
                          <tbody>
                            <tr
                              v-for="(
                                exoneration, index
                              ) in editedItem.exonerations"
                              :key="index"
                            >
                              <td>
                                <p>
                                  {{ exoneration.place_name }}
                                </p>
                              </td>
                              <td>
                                <p>{{ exoneration.date }}</p>
                              </td>
                              <td>
                                <p>{{ exoneration.hour }}</p>
                              </td>
                              <td>
                                <p>{{ exoneration.people }}</p>
                              </td>
                              <td>
                                <p>{{ exoneration.charge }}</p>
                              </td>
                              <td>
                                <p>{{ exoneration.exonerated_amount }}</p>
                              </td>
                              <td class="text-center">
                                <a
                                  @click="deleteExoneration(exoneration.id)"
                                  class="p-1 mr-1 text-center"
                                  ><span class="material-icons text-blue">
                                    delete
                                  </span></a
                                >
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col align="center">
                      <v-btn
                        color="btn-normal no-uppercase mt-3"
                        rounded
                        @click="save"
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
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="400px">
            <v-card class="h-100">
              <v-container>
                <h3 class="black-secondary text-center mt-3 mb-3">
                  Eliminar registro
                </h3>
                <v-row>
                  <v-col align="center">
                    <v-btn
                      color="btn-normal no-uppercase mt-3 mb-3 pr-5 pl-5 mx-auto"
                      rounded
                      @click="deleteItemConfirm"
                      >Confirmar</v-btn
                    >
                    <v-btn
                      color="btn-normal-close no-uppercase mt-3 mb-3"
                      rounded
                      @click="closeDelete"
                    >
                      Cancelar
                    </v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
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
    <template>
      <v-dialog v-model="dialogExoneration" max-width="1400px" persistent>
        <v-card class="flexcard" height="100%">
          <h3 class="black-secondary text-center mt-4 mb-4">
            Agregar Exoneracion
          </h3>
          <v-card-text>
            <v-container>
              <br />
              <h5>Información del espacio</h5>
              <v-divider></v-divider>
              <v-row>
                <!-- Space: National -->
                <v-col cols="12" sm="12" md="6">
                  <base-select-search
                    label="Dirección Nacional"
                    v-model.trim="$v.editedItem.national_direction_name.$model"
                    :items="directions"
                    item="national_direction_name"
                    :validation="$v.editedItem.national_direction_name"
                    @change="changeDirection()"
                  />
                </v-col>
                <v-col cols="12" sm="12" md="6">
                  <base-select-search
                    label="Dependencia"
                    v-model.trim="$v.editedItem.dependence_name.$model"
                    :items="dependences"
                    item="dependence_name"
                    :validation="$v.editedItem.dependence_name"
                  />
                </v-col>
                <!-- Space: Place -->
              </v-row>
              <br /><br />
              <h5>Información de la exoneración</h5>
              <v-divider></v-divider>
              <v-btn
                class="mb-2 btn-normal no-uppercase"
                rounded
                small
                @click="OpenNewPlace()"
              >
                Espacio/Elenco
                <v-icon right> mdi-plus-circle </v-icon>
              </v-btn>
              <v-row>
                <v-checkbox
                  @click="hidden = !hidden"
                  :label="`Monto no tarifado`"
                >
                </v-checkbox>
                <!-- Exoneracion: date -->
                <v-col cols="12" sm="12" md="6">
                  <base-input
                    label="Fecha de evento"
                    v-model="$v.editedItem.date.$model"
                    :validation="$v.editedItem.date"
                    validationTextType="none"
                    type="date"
                  />
                </v-col>
                <!-- Space: Place -->
                <v-col cols="12" sm="12" md="6">
                  <base-select-search
                    label="Espacio/Elenco de Servicio"
                    v-model.trim="$v.editedItem.place_name.$model"
                    :items="places"
                    item="place_name"
                    :validation="$v.editedItem.place_name"
                    @change="changePlace()"
                  />
                </v-col>
                <!-- not Tariff-->
                <v-col cols="12" xs="12" sm="12" md="12" v-show="hidden">
                  <base-input
                    label="Tarifa"
                    v-model.trim="$v.editedItem.not_charged.$model"
                    :validation="$v.editedItem.not_charged"
                    validationTextType="none"
                  />
                </v-col>
                <!-- Space: Tariff -->
                <v-col cols="12" sm="12" md="12" v-show="!hidden">
                  <base-select-search
                    label="Tipo de Tarifa"
                    v-model.trim="$v.editedItem.type_charge.$model"
                    :items="tariffs"
                    item="type_charge"
                    :validation="$v.editedItem.type_charge"
                  />
                </v-col>
                <v-col cols="12" sm="12" md="12">
                  <v-textarea
                    label="Descripción de la exoneración"
                    v-model="$v.editedItem.exonerated_description.$model"
                    :validation="$v.editedItem.exonerated_description"
                    auto-grow
                    outlined
                    rows="1"
                    row-height="15"
                    dense
                  ></v-textarea>
                </v-col>
                <!-- Exoneracion: Exempted hours -->
                <v-col cols="12" sm="12" md="4">
                  <base-input
                    label="Horas exoneradas"
                    v-model="$v.editedItem.hour.$model"
                    :validation="$v.editedItem.hour"
                    type="number"
                    validationTextType="none"
                  />
                  <br />
                </v-col>
                <!-- Exoneracion: number of people -->
                <v-col cols="12" sm="12" md="4">
                  <base-input
                    label="Número de personas"
                    v-model="$v.editedItem.people.$model"
                    :validation="$v.editedItem.people"
                    type="number"
                    v-mask="'#####'"
                  />
                </v-col>
                <!-- Exoneracion: exonerated_amount -->
                <v-col cols="12" sm="12" md="4">
                  <base-input
                    label="Monto"
                    v-model="$v.editedItem.exonerated_amount.$model"
                    :validation="$v.editedItem.exonerated_amount"
                    type="number"
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col align="center">
                  <v-btn
                    class="btn btn-normal mb-3 mt-1"
                    @click="saveExoneration()"
                    >Guardar</v-btn
                  >
                  <v-btn
                    color="btn-normal-close mb-3 mt-1"
                    rounded
                    @click="closeExoneration"
                  >
                    Cancelar
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
    </template>
  </div>
</template>

<script>
import agreementApi from "../apis/agreementApi";
import entityApi from "../apis/entityApi";
import typeApi from "../apis/typeApi";
import directionApi from "../apis/directionApi";
import dependenceApi from "../apis/dependenceApi";
import placeApi from "../apis/placeApi";
import tariffApi from "../apis/tariffApi";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import axios from "axios";
import BaseInput from "./base-components/BaseInput.vue";

export default {
  components: { BaseInput },
  data: () => ({
    search: "",
    dialog: false,
    dialogExoneration: false,
    hidden: false,
    showModal: false,
    dialogDelete: false,
    headers: [
      { text: "CONVENIO", value: "agreement_name" },
      { text: "TIPO DE CONVENIO", value: "type_agreement_name" },
      { text: "DESCRIPCIÓN", value: "description" },
      { text: "ENTIDAD", value: "entity_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    headerExonerations: [
      { text: "ESPACIO/ELENCO", value: "place_name" },
      { text: "FECHAS EXONERADAS", value: "date" },
      { text: "HORAS EXONERADAS", value: "hour" },
      { text: "TARIFA ($)", value: "charge" },
      { text: "MONTO EXONERADO ($)", value: "exonerated_amount" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    recordsExonerations: [],
    recordsFilteredExonerations: [],
    editedIndex: -1,
    editedItem: {
      agreement_name: "",
      description: "",
      type_agreement_name: "",
      entity_name: "",
      national_direction_name: "",
      dependence_name: "",
      place_name: "",
      exonerated_amount: "",
      exonerated_description: "",
      people: "",
      hour: "",
      date: "",
      type_charge: "",
      not_charged: "",
    },
    defaultItem: {
      agreement_name: "",
      description: "",
      type_agreement_name: "",
      entity_name: "",
      national_direction_name: "",
      dependence_name: "",
      place_name: "",
      exonerated_amount: "",
      exonerated_description: "",
      people: "",
      hour: "",
      date: "",
      type_charge: "",
      not_charged: "",
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
    redirectSessionFinished: false,
    alertTimeOut: 0,
  }),

  //Validations
  validations: {
    editedItem: {
      agreement_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      type_agreement_name: {
        required,
      },
      description: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      entity_name: {
        required,
      },
      national_direction_name: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      dependence_name: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      place_name: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      type_charge: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      not_charged: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      people: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      exonerated_amount: {
        minLength: minLength(1),
      },
      hour: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      date: {
        minLength: minLength(1),
      },
      exonerated_description: {
        minLength: minLength(1),
      },
    },
  },

  //Validations
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  /* data1() {
    return {
      showExonerationButton: false,
    };
  }, */

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogExoneration(val) {
      val || this.closeExoneration();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
    this.getExonerations();
    //console.log(this.recordsFiltered);
    //this.exonerations = this.recordsFiltered.exonerations;
  },

  methods: {
    async initialize() {
      this.records = [];
      this.recordsFiltered = [];

      let requests = [
        agreementApi.get(),
        typeApi.get(),
        entityApi.get(),
        directionApi.get(),
        placeApi.get(),
        dependenceApi.get(),
        tariffApi.get(),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      this.records = responses[0].data.agreements;
      this.types = responses[1].data.types;
      this.entities = responses[2].data.entities;
      this.directions = responses[3].data.directions;
      this.places = responses[4].data.places;
      this.dependences = responses[5].data.dependences;
      this.tariffs = responses[6].data.tariffs;

      this.changeDirection();
      this.changePlace();

      this.recordsFiltered = this.records;
    },

    /* async getExonerations(agreementId) {
      this.recordsExonerations = [];
      this.recordsFilteredExonerations = [];

      let requestsExonerations = [
        exonerationApi.get("?agreement_id=" + agreementId),
      ];
      let responsesExonerations = await Promise.all(requestsExonerations).catch(
        (error) => {
          this.updateAlert(
            true,
            "No fue posible obtener las exoneraciones.",
            "fail"
          );
          this.redirectSessionFinished = lib.verifySessionFinished(
            error.response.status,
            401
          );
        }
      );

      this.recordsExonerations =
        responsesExonerations[0].data.exonerations.filter(
          (exonerations) => exonerations.agreement_id === agreementId
        );

      this.recordsFilteredExonerations = this.recordsExonerations;
    }, */

    OpenNewPlace() {
      window.location.href = "places/";
    },

    openExonerationModal() {
      this.dialogExoneration = true;
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);

      /* this.$v.editedItem.type_agreement_name.$model =
        this.editedItem.type_agreement_name;
      this.$v.editedItem.entity_name.$model = this.editedItem.entity_name;
      this.$v.editedItem.national_direction_name.$model =
        this.editedItem.national_direction_name;
      this.$v.editedItem.place_name.$model = this.editedItem.place_name;
      this.$v.editedItem.dependence_name.$model =
        this.editedItem.dependence_name;
      this.$v.editedItem.exonerated_amount.$model =
        this.editedItem.exonerated_amount;
      this.$v.editedItem.exonerated_description.$model =
        this.editedItem.exonerated_description;
      this.$v.editedItem.type_charge.$model = this.editedItem.type_charge;
      this.$v.editedItem.not_charged.$model = this.editedItem.not_charged; */
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await agreementApi
        .delete(`/${this.editedItem.id}`)
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible eliminar el registros.",
            "fail"
          );
          this.close();
          this.redirectSessionFinished = lib.verifySessionFinished(
            error.response.status,
            419
          );
        });

      if (res.data.status == "success") {
        this.redirectSessionFinished = lib.verifySessionFinished(
          res.status,
          200
        );
        this.updateAlert(true, "Registro eliminado.", "success");
      }

      this.initialize();
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeExoneration() {
      this.dialogExoneration = false;
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async save() {
      this.$v.$touch();
      if (this.editedIndex > -1) {
        const res = await agreementApi
          .put(`/${this.editedItem.id}`, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");
            this.close();
            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
          });

        if (res.data.status == "success") {
          this.updateAlert(
            true,
            "Registro almacenado correctamente.",
            "success"
          );
        }
      } else {
        const res = await agreementApi
          .post(null, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");
            this.close();
          });

        if (res.data.status == "success") {
          this.updateAlert(
            true,
            "Registro almacenado correctamente.",
            "success"
          );
        }
      }
      this.close();
      this.initialize();
    },

    async saveExoneration() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      const res = await agreementApi
        .post(null, this.editedItem)
        .catch((error) => {
          this.updateAlert(true, "No fue posible crear el registro.", "fail");
          this.closeExoneration();
        });

      if (res.data.status == "success") {
        this.updateAlert(true, "Registro almacenado correctamente.", "success");
        this.closeExoneration();
      }

      //this.closeExoneration();
      this.getExonerations();
      this.initialize();
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

    async changePlace() {
      let { data } = await axios
        .get("api/tariff/byPlaceName/" + this.editedItem.place_name)
        .catch((error) => {
          this.$emit("update-alert", {
            show: true,
            message:
              "No fue posible obtener la información de las dependencias. ",
            type: "fail",
          });
        });
      this.tariffs = data.tariffs;
    },

    searchValue() {
      this.recordsFiltered = [];

      if (this.search != "") {
        this.records.forEach((record) => {
          let searchConcat = "";
          for (let i = 0; i < record.agreement_name.length; i++) {
            searchConcat += record.agreement_name[i].toUpperCase();
            if (
              searchConcat === this.search.toUpperCase() &&
              !this.recordsFiltered.some((rec) => rec == record)
            ) {
              this.recordsFiltered.push(record);
            }
          }
        });
        return;
      }

      this.recordsFiltered = this.records;
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