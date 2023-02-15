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
            <v-toolbar-title>Dependencias</v-toolbar-title>
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
                    <!-- Form -->
                    <!-- Department Name -->
                    <v-row v-if="directions.length > 0">
                      <v-col cols="12" sm="6" md="6">
                        <base-select-search
                          label="Direcciones"
                          v-model.trim="$v.editedItem.national_direction_name.$model"
                          :items="directions"
                          item="national_direction_name"
                          :validation="$v.editedItem.national_direction_name"
                        />
                      </v-col>
                      <!-- Department Name -->
                      <!-- Municipality Name-->
                      <v-col cols="12" sm="6" md="6">
                        <base-input
                          label="Nombre lugar"
                          v-model="$v.editedItem.dependence_name.$model"
                          :validation="$v.editedItem.dependence_name"
                          validationTextType="default"
                          :validationsInput="{
                            required: true,
                            format: false,
                            minLength: true,
                            maxLength: true,
                          }"
                        />
                      </v-col>
                      <!-- Municipality Name-->
                    </v-row>
                    <!-- Form -->
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
    </div>
  </template>
  
  <script>
  import directionApi from "../apis/directionApi";
  import dependenceApi from "../apis/dependenceApi";
  import lib from "../libs/function";
  import { required, minLength, maxLength } from "vuelidate/lib/validators";
  
  export default {
    data: () => ({
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        { text: "NOMBRE", value: "dependence_name" },
        { text: "DIRECIÓN NACIONAL", value: "national_direction_name" },
        { text: "ACCIONES", value: "actions", sortable: false },
      ],
      records: [],
      recordsFiltered: [],
      editedIndex: -1,
      editedItem: {
        dependence_name: "",
        national_direction_name: "Jefatura de Gabinete",
      },
      defaultItem: {
        dependence_name: "",
        national_direction_name: "Jefatura de Gabinete", 
      },
      textAlert: "",
      alertEvent: "success",
      showAlert: false,
      departments: [],
      redirectSessionFinished: false,
      alertTimeOut: 0,
    }),
  
    // Validations
    validations: {
      editedItem: {
        dependence_name: {
          required,
          minLength: minLength(1),
          maxLength: maxLength(150),
        },
        national_direction_name: {
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
  
        let requests = [dependenceApi.get(), directionApi.get()];
        let responses = await Promise.all(requests).catch((error) => {
          this.updateAlert(true, "No fue posible obtener los registros.", "fail");
          this.redirectSessionFinished = lib.verifySessionFinished(
            error.response.status,
            401
          );
        });
  
        this.records = responses[0].data.dependences;
        this.directions = responses[1].data.directions;
  
        this.recordsFiltered = this.records;
      },
  
      editItem(item) {
        this.dialog = true;
        this.editedIndex = this.recordsFiltered.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.$v.editedItem.national_direction_name.$model =
          this.editedItem.national_direction_name;
      },
  
      deleteItem(item) {
        this.editedIndex = this.recordsFiltered.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialogDelete = true;
      },
  
      async deleteItemConfirm() {
        const res = await dependenceApi
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
  
      close() {
        this.dialog = false;
        this.$nextTick(() => {
          this.editedItem = this.defaultItem;
          this.editedIndex = -1;
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
  
      async save() {
        this.$v.$touch();
        if (this.$v.$invalid || this.editedItem.national_direction_name == "") {
          this.updateAlert(true, "Campos obligatorios.", "fail");
          return;
        }
  
        if (this.editedIndex > -1) {
          const res = await dependenceApi
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
          const res = await dependenceApi
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
        return;
      },
  
      searchValue() {
        this.recordsFiltered = [];
  
        if (this.search != "") {
          this.records.forEach((record) => {
            let searchConcat = "";
            for (let i = 0; i < record.dependence_name.length; i++) {
              searchConcat += record.dependence_name[i].toUpperCase();
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
  
  