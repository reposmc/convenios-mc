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
      <div>
        <p> CONVENIO: {{  }}</p>
        <p> TIPO DE CONVENIO: {{  }}</p>
        <p> DESCRIPCIÓN: {{  }}</p>
        <p> ENTIDAD: {{  }}</p> 
      </div>
    </div>
  </template>
  
  <script>
  import departmentApi from "../apis/departmentApi";
  import municipalityApi from "../apis/municipalityApi";
  import lib from "../libs/function";
  import { required, minLength, maxLength } from "vuelidate/lib/validators";
  
  export default {
    data: () => ({
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        { text: "CÓDIGO", value: "cod_mun" },
        { text: "NOMBRE", value: "municipality_name" },
        { text: "DEPARTAMENTO", value: "department_name" },
        { text: "MINÚSCULAS", value: "mun_min" },
        { text: "MAYUSCULA", value: "mun_may" },
        { text: "DEP-MUN COD", value: "dm_cod" },
        { text: "ACCIONES", value: "actions", sortable: false },
      ],
      records: [],
      recordsFiltered: [],
      editedIndex: -1,
      editedItem: {
        municipality_name: "",
        department_name: "Ahuachapán",
        mun_min: "",
        mun_may: "",
        dm_cod: "",
        cod_mun: "",
      },
      defaultItem: {
        municipality_name: "",
        department_name: "Ahuachapán",
        mun_min: "",
        mun_may: "",
        dm_cod: "",
        cod_mun: "",
      },
      textAlert: "",
      alertEvent: "success",
      showAlert: false,
      departments: [],
      redirectSessionFinished: false,
    }),
  
    // Validations
    validations: {
      editedItem: {
        municipality_name: {
          required,
          minLength: minLength(1),
          maxLength: maxLength(150),
        },
        department_name: {
          required,
        },
        mun_min: {
          required,
          minLength: minLength(1),
          maxLength: maxLength(150),
        },
        mun_may: {
          required,
          minLength: minLength(1),
          maxLength: maxLength(150),
        },
        dm_cod: {
          required,
          minLength: minLength(1),
          maxLength: maxLength(150),
        },
        cod_mun: {
          required,
          minLength: minLength(1),
          maxLength: maxLength(150),
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
  
        let requests = [municipalityApi.get(), departmentApi.get()];
        let responses = await Promise.all(requests).catch((error) => {
          this.updateAlert(true, "No fue posible obtener los registros.", "fail");
          this.redirectSessionFinished = lib.verifySessionFinished(
            error.response.status,
            401
          );
        });
  
        this.records = responses[0].data.municipalities;
        this.departments = responses[1].data.departments;
  
        this.recordsFiltered = this.records;
      },
  
      editItem(item) {
        this.dialog = true;
        this.editedIndex = this.recordsFiltered.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.$v.editedItem.department_name.$model =
          this.editedItem.department_name;
      },
  
      deleteItem(item) {
        this.editedIndex = this.recordsFiltered.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialogDelete = true;
      },
  
      async deleteItemConfirm() {
        const res = await municipalityApi
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
        if (this.$v.$invalid || this.editedItem.department_name == "") {
          this.updateAlert(true, "Campos obligatorios.", "fail");
          return;
        }
  
        if (this.editedIndex > -1) {
          const res = await municipalityApi
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
          const res = await municipalityApi
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
  
      searchValue() {
        this.recordsFiltered = [];
  
        if (this.search != "") {
          this.records.forEach((record) => {
            let searchConcat = "";
            for (let i = 0; i < record.municipality_name.length; i++) {
              searchConcat += record.municipality_name[i].toUpperCase();
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