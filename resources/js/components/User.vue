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
      :search="search"
      :options.sync="options"
      :server-items-length="total"
      :footer-props="{ itemsPerPageOptions: [50] }"
      :items-per-page="take"
      @update:options="updatePagination"
      :page.sync="actualPage"
      item-key="id"
      sort-by="name"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Usuarios</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="700px" persistent>
            <template v-slot:activator="{ on, attrs }">
              <v-row>
                <v-col align="right">
                  <v-btn
                    class="mb-2 btn-normal no-uppercase"
                    v-bind="attrs"
                    v-on="on"
                    rounded
                    @click="newUser()"
                    :disabled="loading != false"
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
                    v-model="search"
                    @input="searchValue()"
                  ></v-text-field>
                </v-col>
              </v-row>
            </template>
            <v-card class="flexcard auth" height="100%">
              <v-card-title>
                <h1 class="mx-auto pt-3 mb-3 text-center black-secondary">
                  {{ formTitle }}
                </h1>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <!-- Form -->
                  <v-row>
                    <!-- User Name -->
                    <v-col cols="12" sm="6" md="6">
                      <base-input
                        label="Nombre"
                        v-model="$v.editedItem.name.$model"
                        :validation.sync="$v.editedItem.name"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <base-input
                        label="Apellidos"
                        v-model="$v.editedItem.last_name.$model"
                        :validation.sync="$v.editedItem.last_name"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>

                    <!-- DUI -->
                    <!-- <v-col cols="12" sm="6" md="6">
                      <base-input
                        label="DUI"
                        v-model.trim="$v.editedItem.dui.$model"
                        :validation="$v.editedItem.dui"
                        validationTextType="only-numbers"
                        v-mask="'########-#'"
                        :validationsInput="{
                          required: true,
                          isValidDui: true,
                        }"
                      />
                    </v-col> -->

                    <!-- Rol  -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Rol"
                        v-model.trim="$v.editedItem.rol.$model"
                        :items="roles"
                        :validation="$v.editedItem.rol"
                      />
                    </v-col>
                    <!-- Rol  -->
                    <!-- E-mail -->
                    <v-col cols="12" sm="6" md="6">
                      <base-input
                        label="Correo electrónico"
                        v-model="$v.editedItem.email.$model"
                        :validation.sync="$v.editedItem.email"
                        validationTextType="none"
                        :validationsInput="{
                          required: true,
                          email: true,
                        }"
                      />
                    </v-col>
                    <!-- E-mail -->
                    <!-- Password -->
                    <v-col cols="12" sm="6" md="6">
                      <base-input
                        label="Contraseña"
                        v-model="$v.editedItem.password.$model"
                        :validation.sync="$v.editedItem.password"
                        validationTextType="none"
                        :type="typePassword"
                        :min="1"
                        autocomplete="off"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxnLength: true,
                          isValidPassword: true,
                          showPassword: true,
                        }"
                        @update-password="showPassword($event)"
                      />
                    </v-col>
                    <!-- Password -->
                    <!-- Dependencies -->
                    <template>
                      <h5 class="pt-3">Dependencias</h5>
                      <hr />
                      <!-- dependence_name -->
                      <v-col cols="12" md="6">
                        <base-select-search
                          label="Dependencia"
                          v-model.trim="
                            $v.formDependencies.dependence_name.$model
                          "
                          :items="dependencies"
                          item="dependence_name"
                          :validation="$v.formDependencies.dependence_name"
                        />
                      </v-col>
                      <!-- dependence_name -->
                      <!-- assignDependency -->
                      <v-col cols="12" md="6">
                        <a
                          href="#"
                          class="btn btn-normal"
                          @click="assignDependency"
                        >
                          Agregar
                        </a>
                      </v-col>
                      <!-- assignDependency -->

                      <!-- dependency table -->
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
                      <!-- dependency table -->
                    </template>
                    <!-- Dependencies -->
                  </v-row>
                  <!-- Form -->
                  <v-row>
                    <v-col align="center">
                      <v-btn
                        color="btn-normal no-uppercase mt-3"
                        rounded
                        @click="save"
                        :disabled="loading != false"
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
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
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
import roleApi from "../apis/roleApi";
import userApi from "../apis/userApi";
import dependenceApi from "../apis/dependenceApi";
import lib from "../libs/function";

import {
  required,
  minLength,
  maxLength,
  email,
  helpers,
} from "vuelidate/lib/validators";

export default {
  data() {
    return {
      search: "",
      loading: false,
      dialog: false,
      headers: [
        { text: "USUARIO", value: "name" },
        { text: "ROL", value: "rol" },
        { text: "CORREO ELECTRÓNICO", value: "email" },
        { text: "ACCIONES", value: "actions", sortable: false },
      ],
      records: [],
      recordsFiltered: [],
      editedIndex: -1,
      skip: 0,
      take: 50,
      title: "Usuarios",
      numberItemsToAdd: 50,
      total: 50,
      loadMoreItems: false,
      options: {},
      actualPage: 1,
      editedItem: {
        name: "",
        last_name: "",
        email: "",
        password: "",
        dui: "",
        rol: "Administrator",
        assignedDependencies: [],
      },
      defaultItem: {
        name: "",
        email: "",
        last_name: "",
        password: "",
        dui: "",
        rol: "Administrator",
        assignedDependencies: [],
      },
      formDependencies: {
        dependence_name: "",
      },
      textAlert: "",
      alertEvent: "success",
      roles: [],
      dependencies: [],
      redirectSessionFinished: false,
      showAlert: false,
      typePassword: "password",
    };
  },

  // Validations
  validations: {
    editedItem: {
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(13),
        isValidPassword: helpers.regex(
          "isValidPassword",
          /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,13}$/
        ),
      },
      email: {
        required,
        email,
      },
      name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      last_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      rol: {
        required,
      },
      // dui: {
      //   required,
      //   isValidDui: helpers.regex("isValidDui", /[0-9]{8}-[0-9]/),
      // },
      assignedDependencies: {
        // required,
      },
    },
    formDependencies: {
      dependence_name: {
        // required,
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
    options: {
      handler() {
        this.loadMore();
      },
      deep: false,
      dirty: false,
    },
    dialog(val) {
      val || this.close();
    },
    dialogBlock(val) {
      val || this.closeBlock();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    async initialize() {
      this.loading = true;
      this.$v.$reset();
      this.records = [];
      this.recordsFiltered = [];

      let requests = [
        userApi.get(null, {
          params: { skip: this.skip, take: this.take },
        }),
        roleApi.get(),
        dependenceApi.get(),
      ];

      const responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener el registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          419
        );
      });

      this.records = responses[0].data.users;
      this.recordsFiltered = responses[0].data.users;
      this.dependencies = responses[2].data.dependences;
      this.total = responses[0].data.total;

      this.roles = responses[1].data.roles;
      this.loading = false;
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

    closeBlock() {
      this.dialogBlock = false;

      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async save() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      if (this.editedIndex > -1) {
        const edited = Object.assign(
          this.recordsFiltered[this.editedIndex],
          this.editedItem
        );

        const res = await userApi
          .put(`/${edited.id}`, edited)
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible actualizar el registro.",
              "fail"
            );

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
          this.updateAlert(true, "Registro actualizado.", "success");
        }
      } else {
        //Creating user
        const res = await userApi.post(null, this.editedItem).catch((error) => {
          this.updateAlert(true, "No fue posible crear el registro.", "fail");
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

          for (let i = 0; i < record.name.length; i++) {
            searchConcat += record.name[i].toUpperCase();

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

    async loadMore() {
      if (this.actualPage == 1) {
        this.actualPage = 1;
        this.skip = 0;
        this.take = this.numberItemsToAdd;
      }
      const res = await userApi
        .get(null, {
          params: { skip: this.skip, take: this.take },
        })
        .catch((error) => {
          this.redirectSessionFinished = lib.verifySessionFinished(
            res.status,
            200
          );
          this.updateAlert(
            true,
            "Registro almacenado correctamente.",
            "success"
          );
        });

      this.records = res.data.users;
      this.recordsFiltered = res.data.users;

      this.search = "";

      this.$v.editedItem.rol.$model = "Postulante";
    },

    updatePagination(pagination) {
      if (pagination.page != 1) {
        if (pagination.page <= this.actualPage) {
          this.skip -= this.take;
          this.take -= this.numberItemsToAdd;
        } else {
          this.skip = this.take;
          this.take += this.numberItemsToAdd;
        }
      } else {
        this.skip = 0;
        this.take = this.numberItemsToAdd;
      }
      this.actualPage = pagination.page;
    },

    newUser() {
      this.dialog = true;
      this.editedItem.rol = this.roles[0].name;
      this.$v.$reset();
    },

    updateAlert(show = false, text = "Alerta", event = "success") {
      this.textAlert = text;
      this.alertEvent = event;
      this.showAlert = show;
    },

    updateTimeOut(event) {
      this.redirectSessionFinished = event;
    },

    showPassword(e) {
      this.typePassword = e.show;
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
  },
};
</script>
