<template>
  <div data-app>
    <!-- <alert-time-out
      :redirect="redirectSessionFinished"
      @redirect="updateTimeOut($event)"
    /> -->
    <alert
      :text="textAlert"
      :event="alertEvent"
      :show="showAlert"
      @show-alert="updateAlert($event)"
      class="mb-2"
    />
          <v-card>
                <v-container >
                  <br>
                  <!-- Form -->
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
                  <br><br>
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
                  <br><br>
                  <h5>Información de la exoneración</h5>
                  <v-divider></v-divider>
                  <v-row>
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
                    <!-- Space: Tariff -->
                    <v-col cols="12" sm="12" md="6">
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
                    <v-col cols="12" sm="12" md="3"> 
                      <base-input
                        label="Número de personas"
                        v-model="$v.editedItem.people.$model"
                        :validation="$v.editedItem.people"
                        type="number"
                        v-mask = "'#####'"
                      />
                    </v-col>
                    <!-- Exoneracion: date -->
                    <v-col cols="12" sm="12" md="3">
                      <base-input
                        label="Fecha de evento"
                        v-model="$v.editedItem.date.$model"
                        :validation="$v.editedItem.date"
                        validationTextType="none"
                        type="date"
                      />
                    </v-col>
                  <!-- Exoneracion: Exempted hours -->
                    <v-col cols="12" sm="12" md="3"> 
                      <base-input
                        label="Horas exoneradas"
                        v-model="$v.editedItem.hour.$model"
                        :validation="$v.editedItem.hour"
                        type="number"
                        validationTextType="none"
                      />
                      <br>
                    </v-col>
                    <!-- Exoneracion: exonerated_amount -->
                    <v-col cols="12" sm="12" md="3"> 
                      <base-input
                        label="Monto"
                        v-model="$v.editedItem.exonerated_amount.$model"
                        :validation="$v.editedItem.exonerated_amount"
                        type="number"
                      />
                    </v-col> 
                  </v-row>
                  <v-row>
                    <v-col >
                      <v-btn
                        class="mb-2 btn-normal-close no-uppercase"
                        rounded
                        @click="saveForm()"
                        >
                        Guardar
                      </v-btn>
                    </v-col>
                  </v-row>
                  <br>
        </v-container>

    <v-data-table
      :headers="headers"
      :items="recordsFiltered"
      sort-by="name"
      class="elevation-3 shadow p-3 mt-3"
    >
      <!-- <template v-slot:[`item.actions`]="{ item }">
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
      </template> -->
    </v-data-table>
      </v-card>   
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
import axios from 'axios';

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "CONVENIO", value: "agreement_name" },
      { text: "TIPO DE CONVENIO", value: "agreement_name" },
      { text: "DESCRIPCIÓN", value: "description" },
      { text: "ENTIDAD", value: "entity_id" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      agreement_name: "",
      description: "",
      national_direction_name: "",
      dependence_name: "",
      type_agreement_name: "",
      entity_name: "",
      place_name: "",
      exonerated_amount:"",
      exonerated_description:"",
      people:"",
      hour:"",
      date: "",
      type_charge: "",
    },
    defaultItem: {
      agreement_name: "",
      description: "",
      national_direction_name: "",
      dependence_name: "",
      type_agreement_name: "",
      entity_name: "",
      place_name: "",
      exonerated_amount:"",
      exonerated_description:"",
      people:"",
      hour:"",
      date: "",
      type_charge: "",
    },
    textAlert: "",
    alertEvent: "success",
    showAlert: false,
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
        required,
      },
      dependence_name: {
        required,
      },
      place_name: {
        required,
      },
      type_charge: {
        required,
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
      date:{
        minLength: minLength(1),
        required,
      },
      exonerated_description: {
        minLength: minLength(1),
        required,
      },
    },
  },
  //Validations
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  watch: {
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
      this.records = [];
      this.recordsFiltered = [];

      let requests = [agreementApi.get(), typeApi.get(), entityApi.get(), directionApi.get(), placeApi.get(), dependenceApi.get(), tariffApi.get(),];
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
      this.editedItem.national_direction_name = this.editedItem.national_direction_name ?? this.directions[3].national_direction_name;
      this.places = responses[4].data.places;
      this.editedItem.place_name = this.editedItem.place_name ?? this.places[4].place_name; 
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
        this.$v.editedItem.entity_name.$model =
        this.editedItem.entity_name;
        this.$v.editedItem.national_direction_name.$model =
        this.editedItem.national_direction_name;
        this.$v.editedItem.place_name.$model =
        this.editedItem.place_name; 
        this.$v.editedItem.dependence_name.$model =
        this.editedItem.dependence_name; 
        this.$v.editedItem.exonerated_amount.$model =
        this.editedItem.exonerated_amount;
        this.$v.editedItem.exonerated_description.$model =
        this.editedItem.exonerated_description;
        this.$v.editedItem.type_charge.$model =
        this.editedItem.type_charge;
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
      //this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async saveForm() {
    this.$v.$touch();
    if (this.$v.$invalid) {
      this.updateAlert(true, "Campos obligatorios.", "fail");
      return;
    }

      console.log(this.editedItem);
      const res = await agreementApi.post(null, this.editedItem).catch((error) => {
        this.updateAlert(true, "No fue posible registrar el registro.", "fail");
      });

    console.log(res);

    if (res.data.status == "success") {
      this.updateAlert(
        true,
        "Registro almacenado correctamente.",
        "success"
      );
    }

    this.close();
    this.initialize();
  },

  async changeDirection(){
    let { data } = await axios
      .get(
        "api/dependence/byDirectionName/" + this.editedItem.national_direction_name
      )
      .catch((error) => {
        this.$emit("update-alert", {
          show: true,
          message: "No fue posible obtener la información de las dependencias. ",
          type: "fail",
        });
      });
      this.dependences = data.dependences;
  },

  async changePlace(){
    let { data } = await axios
      .get(
        "api/tariff/byPlaceName/" + this.editedItem.place_name
      )
      .catch((error) => {
        this.$emit("update-alert", {
          show: true,
          message: "No fue posible obtener la información de las dependencias. ",
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