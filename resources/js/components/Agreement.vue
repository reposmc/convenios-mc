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
          <v-dialog v-model="dialog" max-width="600px" persistent>
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
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Nombre"
                        v-model="$v.editedItem.agreement_name.$model"
                        :validation="$v.editedItem.agreement_name"
                        validationTextType="default"
                      />
                    </v-col>
                    <!-- Agreement Name -->
                    <!-- Agreement type -->
                    <v-col cols="12" sm="12" md="6">
                      <base-select-search
                        label="Tipo de convenio"
                        v-model.trim="$v.editedItem.type_agreement_name.$model"
                        :items="types"
                        item="type_agreement_name"
                        :validation="$v.editedItem.type_agreement_name"
                      />
                    </v-col>
                    <!-- Agreement type -->
                    <!-- Agreement description -->
                    <v-col cols="12" sm="12" md="12">
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
                    <!-- Agreement:Entity -->
                    <v-col cols="12" sm="12" md="12">
                      <base-select-search
                        label="Entidad"
                        v-model.trim="$v.editedItem.entity_name.$model"
                        :items="entities"
                        item="entity_name"
                        :validation="$v.editedItem.entity_name"
                      />
                    </v-col>
                    <!-- Agreement:Entity -->
                  </v-row>
                  <!-- Form -->
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

    <!-- Exoneracion -->
    <v-data-table
      :headers="headers2"
      :items="recordsFiltered"
      sort-by="name"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Exoneraciones</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog2" max-width="600px" persistent>
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
                    @keyup="searchValue2()"
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
                  <h5>Convenio</h5>
                  <v-divider></v-divider>
                  <v-row>
                    <!-- Space: National -->
                    <v-col cols="12" sm="12" md="12">
                      <base-select-search
                        label="Convenio"
                        v-model.trim="$v.editedItem.agreement_name.$model"
                        :items="agreements"
                        item="agreement_name"
                        :validation="$v.editedItem.agreement_name"
                      />
                    </v-col>
                  </v-row>
                  <br /><br />
                  <h5>Información del espacio</h5>
                  <v-divider></v-divider>
                  <v-row>
                    <!-- Space: National -->
                    <v-col cols="12" sm="12" md="6">
                      <base-select-search
                        label="Dirección Nacional"
                        v-model.trim="
                          $v.editedItem.national_direction_name.$model
                        "
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
                  <v-btn class="mb-2 btn-normal no-uppercase" rounded small>
                    Espacio/Elenco
                    <v-icon right> mdi-plus-circle </v-icon>
                  </v-btn>
                  <v-row>
                    <v-checkbox
                      @click="hidden = !hidden"
                      :label="`Monto no tarifado`"
                    >
                    </v-checkbox>
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
                    <!-- Space: No Tariff -->
                    <v-col cols="12" sm="12" md="6" v-show="hidden">
                      <base-input
                        label="Tarifa"
                        v-model.trim="$v.editedItem.not_charged.$model"
                        :validation="$v.editedItem.not_charged"
                        validationTextType="none"
                      />
                    </v-col>
                    <!-- Space: Tariff -->
                    <v-col cols="12" sm="12" md="6" v-show="!hidden">
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
                    <!-- Exoneracion: number of people -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Número de personas"
                        v-model="$v.editedItem.people.$model"
                        :validation="$v.editedItem.people"
                        type="number"
                        v-mask="'#####'"
                      />
                    </v-col>
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
                    <!-- Exoneracion: Exempted hours -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Horas exoneradas"
                        v-model="$v.editedItem.hour.$model"
                        :validation="$v.editedItem.hour"
                        type="number"
                        validationTextType="none"
                      />
                      <br />
                    </v-col>
                    <!-- Exoneracion: exonerated_amount -->
                    <v-col cols="12" sm="12" md="6">
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
                        color="btn-normal no-uppercase mt-3"
                        rounded
                        @click="savefrm()"
                      >
                        Guardar
                      </v-btn>

                      <v-btn
                        color="btn-normal-close no-uppercase mt-3"
                        rounded
                        @click="close2"
                      >
                        Cancelar
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete2" max-width="400px">
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
                      @click="deleteItemConfirm2"
                      >Confirmar</v-btn
                    >
                    <v-btn
                      color="btn-normal-close no-uppercase mt-3 mb-3"
                      rounded
                      @click="closeDelete2"
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
import exonerationDetailApi from "../apis/exonerationDetailApi";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data: () => ({
    search: "",
    dialog: false,
    hidden: false,
    dialogDelete: false,
    headers: [
      { text: "CONVENIO", value: "agreement_name" },
      { text: "TIPO DE CONVENIO", value: "type_agreement_name" },
      { text: "DESCRIPCIÓN", value: "description" },
      { text: "ENTIDAD", value: "entity_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
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
    agreements: [],
    types: [],
    entities: [],
    directions: [],
    places: [],
    tariffs: [],
    dependences: [],
    redirectSessionFinished: false,
    search2: "",
    dialog2: false,
    dialogDelete2: false,
    headers2: [
      { text: "CONVENIO", value: "agreement_name" },
      { text: "ENTIDAD", value: "entity_name" },
      { text: "ESPACIO/ELENCO EXONERADO", value: "place_name" },
      { text: "FECHAS EXONERADAS", value: "date" },
      { text: "HORAS EXONERADAS", value: "hour" },
      { text: "NÚMERO DE PERSONAS", value: "people" },
      { text: "ACCIONES", value: "actions", sortablse: false },
    ],
  }),

  // Validations
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
        required,
      },
      dependence_name: {
        required,
      },
      place_name: {
        required,
      },
      type_charge: {
        
      },
      not_charged: {
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      people: {
        minLength: minLength(1),
        maxLength: maxLength(150),
        required,
      },
      exonerated_amount: {
        minLength: minLength(1),
        required,
      },
      hour: {
        minLength: minLength(1),
        maxLength: maxLength(150),
        required,
      },
      date: {
        minLength: minLength(1),
        required,
      },
      exonerated_description: {
        minLength: minLength(1),
        required,
      },
    },
  },
  // Validations
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialog2(val) {
      val || this.close2();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
    dialogDelete2(val) {
      val || this.closeDelete2();
    },
  },

  created() {
    this.initialize();
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
      this.agreements = responses[0].data.agreements;
      this.types = responses[1].data.types;
      this.entities = responses[2].data.entities;
      this.directions = responses[3].data.directions;
      this.editedItem.national_direction_name =
        this.editedItem.national_direction_name ??
        this.directions[3].national_direction_name;
      this.places = responses[4].data.places;
      this.editedItem.place_name =
        this.editedItem.place_name ?? this.places[4].place_name;
      this.dependences = responses[5].data.dependences;
      this.tariffs = responses[6].data.tariffs;

      this.changeDirection();
      this.changePlace();

      this.recordsFiltered = this.records;
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.type_agreement_name.$model =
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
      this.$v.editedItem.not_charged.$model = this.editedItem.not_charged;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItem2(item) {
      this.editedIndex2 = this.recordsFiltered2.indexOf(item);
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
      } else {
        this.updateAlert(true, "No se pudo eliminar el registro.", "fail");
      }

      this.initialize();
      this.closeDelete();

      this.activateAlert();
    },

    async deleteItemConfirm2() {
      const res = await exonerationDetailApi
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
      } else {
        this.updateAlert(true, "No se pudo eliminar el registro.", "fail");
      }

      this.initialize();
      this.closeDelete2();

      this.activateAlert();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
      });
    },

    close2() {
      this.dialog2 = false;
      this.$nextTick(() => {
        this.editedItem = this.defaultItem2;
        this.editedIndex2 = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });

      this.dialogDelete = false;
    },

    closeDelete2() {
      this.dialogDelete2 = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem2);
        this.editedIndex2 = -1;
      });

      this.dialogDelete2 = false;
    },

    async save() {
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

    async savefrm() {
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
      this.close2();
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

    searchValue2() {
      this.recordsFiltered = [];

      if (this.search2 != "") {
        this.records2.forEach((record) => {
          let searchConcat = "";
          for (let i = 0; i < record.agreement_name.length; i++) {
            searchConcat += record.agreement_name[i].toUpperCase();
            if (
              searchConcat === this.search.toUpperCase() &&
              !this.recordsFiltered2.some((rec) => rec == record)
            ) {
              this.recordsFiltered2.push(record);
            }
          }
        });
        return;
      }

      this.recordsFiltered2 = this.records2;
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