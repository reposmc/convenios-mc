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
          <v-toolbar-title>Instrumentos</v-toolbar-title>
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
                    v-if="actualUser.role == 'Administrador'"
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
                    <!-- instrument_name -->
                    <v-col cols="12" sm="12" md="12">
                      <base-text-area
                        label="Nombre"
                        v-model="$v.editedItem.instrument_name.$model"
                        :validation="$v.editedItem.instrument_name"
                        validationTextType="default"
                        auto-grow
                        :max="700"
										    :min="1"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- instrument_name -->
                  </v-row>
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
                      <base-text-area
                        label="Descripción del instrumento"
                        v-model="$v.editedItem.description.$model"
                        :validation="$v.editedItem.description"
                        :rows="7"
                        validationTextType="default"
                        :max="500"
										    :min="1"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      ></base-text-area>
                     <!--  <div style="display: flex; justify-content: flex-end">
                        <span class="">(Máximo 500 caracteres)</span>
                      </div> -->
                    </v-col>
                    <!-- description -->
                  </v-row>
                  <!-- Form -->

                  <!-- Dependencies -->
                  <template>
                    <h5 class="pt-3">Dependencias</h5>
                    <hr />
                    <!-- dependence_name -->
                    <v-col cols="12" md="6">
                      <base-select-search
                        label="Dependencia"
                        v-model="$v.formDependencies.dependence_name.$model"
                        :items="dependences"
                        item="dependence_name"
                        :validation="$v.formDependencies.dependence_name"
                      />
                    </v-col>
                    <!-- dependence_name -->
                    <!-- assignDependency -->
                    <v-col cols="12" md="6">
                      <a
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
                          <td>{{ assigned.dependence_name }}</td>
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
        <v-tooltip top v-if="actualUser.role == 'Administrador'">
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              small
              class="mr-2"
              @click="editItemDependency(item)"
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
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              small
              class="mr-2"
              @click="viewExonerationItem(item)"
              v-bind="attrs"
              v-on="on"
            >
              visibility
            </v-icon>
          </template>
          <span>Ver Instrumento</span>
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

    <v-dialog v-model="dialogEditInstrument" max-width="1000px" persistent>
      <v-card-text>
        <v-container>
          <h5>Información del instrumento</h5>
          <hr />
          <v-row>
            <!-- instrument_name -->
            <v-col cols="12" sm="12" md="12">
              <base-text-area
                label="Nombre"
                v-model="$v.editedItem.instrument_name.$model"
                :validation="$v.editedItem.instrument_name"
                validationTextType="default"
                :rows="3"
                :max="700"
                :min="1"
                :validationsInput="{
                  required: true,
                  minLength: true,
                  maxLength: true,
                }"
              />
            </v-col>
            <!-- instrument_name -->
          </v-row>
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
              <base-text-area
                label="Descripción del instrumento"
                v-model="$v.editedItem.description.$model"
                :validation="$v.editedItem.description"
                :rows="7"
                validationTextType="default"
                :max="500"
                :min="1"
                :validationsInput="{
                  required: true,
                  minLength: true,
                  maxLength: true,
                }"
              ></base-text-area>
              <!--  <div style="display: flex; justify-content: flex-end">
                <span class="">(Máximo 500 caracteres)</span>
              </div> -->
            </v-col>
            <!-- description -->
          </v-row>
          <!-- Form -->

          <!-- Dependencies -->
          <template>
            <h5 class="pt-3">Dependencias</h5>
            <hr />
            <!-- dependence_name -->
            <v-col cols="12" md="6">
              <base-select-search
                label="Dependencia"
                v-model="$v.formDependencies.dependence_name.$model"
                :items="dependences"
                item="dependence_name"
                :validation="$v.formDependencies.dependence_name"
              />
            </v-col>
            <!-- dependence_name -->
            <!-- assignDependency -->
            <v-col cols="12" md="6">
              <a
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
    </v-dialog>

    <v-dialog v-model="dialogVerExoneration" max-width="1000px" persistent>
      <v-card
        class="flexcard"
        height="100%"
        v-for="(item, index) in exonerationSeleccionado" :key="index"
      >
        <v-card-title>
					<h1 class="mx-auto pt-3 mb-3 text-center black-secondary">
            {{ item.instrument_name }}
          </h1>
        </v-card-title>
        <v-card-text>
          <v-container>
            <!-- Form -->
            <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-simple-table>
                  <template v-slot:default>
                    <tbody>
                      <tr>
                        <th style="min-width: 100px">Entidad</th>
                        <td>
                          {{ item.entity_name }}
                        </td>
                      </tr>
                      <tr>
                        <th style="min-width: 100px">Sector</th>
                        <td>
                          {{ item.sector_name }}
                        </td>
                      </tr>
                      <tr>
                        <th style="min-width: 100px">Tipo de Instrumento</th>
                        <td>
                          {{ item.type_instrument_name }}
                        </td>
                      </tr>
                      <tr>
                        <th style="min-width: 100px">Descripción</th>
                        <td>
                          {{ item.description }}
                        </td>
                      </tr>
                      <tr>
                        <th style="min-width: 100px">Documento</th>
                        <td colspan="4">
                          <div>
                            <v-icon small> mdi-paperclip </v-icon>
                           <!--  <a
                              v-for="(
                                doc, i
                              ) in movimientoSeleccionado.archivo"
                              :key="i"
                              :value="doc.archivo"
                              :href="doc.url"
                              rel="noopener noreferrer"
                              target="_blank"
                            >
                              <span>{{ doc.archivo }}</span>
                            </a> -->
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
          <template>
            <!-- table exoneration  -->
            <v-simple-table class="mt-2">
              <!-- header -->
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th v-if="item.type_instrument_name == 'Convenio'">
                    Espacio
                  </th>
                  <th v-if="item.type_instrument_name == 'Convenio'">
                    ¿Tarifado?
                  </th>
                  <th v-if="item.type_instrument_name != 'Convenio'">
                    Concepto
                  </th>
                  <th v-if="item.type_instrument_name != 'Convenio'">
                    Cantidad
                  </th>
                  <th>Descripción</th>
                  <th v-if="item.type_instrument_name == 'Convenio'">
                    Descripción Tarifada/No tarifada
                  </th>
                  <th v-if="item.type_instrument_name == 'Convenio'">
                    Monto Tarifado/No tarifado
                  </th>
                  <th v-if="item.type_instrument_name == 'Convenio'">
                    Cant. Horas/Personas
                  </th>

                  <th v-if="item.type_instrument_name != 'Convenio'">
                    Precio estimado
                  </th>
                  <th>Total</th>
                </tr>
              </thead>
              <!-- header -->
              <!-- body -->
              <tbody>
                <tr
                  v-for="(assigned, index) in item.assignedExonerations"
                  :key="index"
                >
                  <td v-if="assigned.date_event">
                    {{ assigned.date_event }}
                  </td>
                  <td v-if="assigned.service_place_name">
                    {{ assigned.service_place_name }}
                  </td>
                  <td v-if="assigned.is_tariffed">
                    {{ assigned.is_tariffed }}
                  </td>
                  <td v-if="assigned.concept">
                    {{ assigned.concept }}
                  </td>
                  <td v-if="assigned.quantity">
                    {{ assigned.quantity }}
                  </td>
                  <td v-if="assigned.exonerated_description">
                    {{ assigned.exonerated_description }}
                  </td>
                  <td v-if="assigned.estimated_price">
                    {{ assigned.estimated_price }}
                  </td>
                  <td v-if="assigned.tariff_type_charge">
                    {{ assigned.tariff_type_charge }}
                  </td>
                  <td v-if="assigned.non_tariff_concept">
                    {{ assigned.non_tariff_concept }}
                  </td>
                  <td v-if="assigned.tariff_amount">
                    {{ assigned.tariff_amount }}
                  </td>
                  <td v-if="assigned.non_tariff_amount">
                    {{ assigned.non_tariff_amount }}
                  </td>
                  <td v-if="assigned.number_hour">
                    {{ assigned.number_hour }}
                  </td>
                  <td v-if="assigned.number_people">
                    {{ assigned.number_people }}
                  </td>
                  <td v-if="assigned.total_amount">
                    {{ assigned.total_amount }}
                  </td>
                </tr>
              </tbody>
              <!-- body -->
              <!-- total table -->
            </v-simple-table>
          </template>
        </v-row>
          <v-row>
            <v-col align="center">
              <v-btn
                color="btn-normal-close no-uppercase mt-3 mb-3"
                rounded
                @click="closeViewExoneration()"
              >
                Cerrar
              </v-btn>
            </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

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
            <!-- instrument_name -->
            <v-col cols="12" sm="12" md="12">
              <base-input
                label="Instrumento"
                v-model.trim="$v.editedItem.instrument_name.$model"
                :validation="$v.editedItem.instrument_name"
                validationTextType="default"
                :disabled="true"
              />
            </v-col>
            <!-- instrument_name -->
          </v-row>
          <!-- title -->
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <h5>Detalle de la exoneración</h5>
              <hr />
            </v-col>
            <!-- date_event -->
            <v-col cols="12" sm="12" md="6">
              <base-input
                label="Fecha de evento"
                v-model="$v.formExonerations.date_event.$model"
                :validation="$v.formExonerations.date_event"
                validationTextType="none"
                type="date"
              />
            </v-col>
            <!-- date_event -->
          </v-row>

          <!-- Diferente de convenio -->
          <v-row v-if="editedItem.type_instrument_name != 'Convenio'">
            <!-- concept -->
            <v-col cols="12" xs="12" sm="12" md="12">
              <base-input
                label="Concepto"
                v-model.trim="$v.formExonerations.concept.$model"
                :validation="$v.formExonerations.concept"
                validationTextType="default"
                :max="200"
                :min="1"
                :validationsInput="{
                  required: true,
                  minLength: true,
                  maxLength: true,
                }"
              />
            </v-col>
            <!-- concept -->

            <!-- quantity -->
            <v-col cols="12" xs="12" sm="12" md="6">
              <base-input
                label="Cantidad"
                v-model.trim="$v.formExonerations.quantity.$model"
                :validation="$v.formExonerations.quantity"
                type="number"
              />
            </v-col>
            <!-- quantity -->

            <!-- estimated_price -->
            <v-col cols="12" xs="12" sm="12" md="6">
              <base-input
                label="Precio estimado"
                v-model.trim="$v.formExonerations.estimated_price.$model"
                :validation="$v.formExonerations.estimated_price"
                type="number"
              />
            </v-col>
            <!-- estimated_price -->
          </v-row>
          <!-- Diferente de convenio -->

          <!-- Igual a convenio -->
          <v-row v-if="editedItem.type_instrument_name == 'Convenio'">
            <!-- dependence_name -->
            <v-col cols="12" sm="12" md="6">
              <base-select-search
                label="Dependencia"
                v-model.trim="$v.formExonerations.dependence_name.$model"
                :items="dependences"
                item="dependence_name"
                :validation="$v.formExonerations.dependence_name"
                @change="changeDependency()"
              />
            </v-col>
            <!-- dependence_name -->

            <!-- service_place_name -->
            <v-col cols="12" sm="12" md="6">
              <base-input
                label="Espacio de servicio"
                v-model.trim="$v.formExonerations.service_place_name.$model"
                :validation="$v.formExonerations.service_place_name"
                validationTextType="default"
              />
            </v-col>
            <!-- service_place_name -->

            <!-- is_tariffed -->
            <v-col cols="12" sm="12" md="12">
              <v-checkbox
                v-model="$v.formExonerations.is_tariffed.$model"
                label="No tarifado"
              >
              </v-checkbox>
            </v-col>
            <!-- is_tariffed -->

            <!-- non_tariff_concept -->
            <v-col
              cols="12"
              xs="12"
              sm="12"
              md="12"
              v-show="formExonerations.is_tariffed != false"
            >
              <base-input
                label="Concepto no tarifado"
                v-model.trim="$v.formExonerations.non_tariff_concept.$model"
                :validation="$v.formExonerations.non_tariff_concept"
                validationTextType="none"
              />
            </v-col>
            <!-- non_tariff_concept -->

            <!-- non_tariff_amount -->
            <v-col
              cols="12"
              sm="12"
              md="3"
              v-show="formExonerations.is_tariffed != false"
            >
              <base-input
                label="Monto no tarifado"
                v-model.number="$v.formExonerations.non_tariff_amount.$model"
                :validation="$v.formExonerations.non_tariff_amount"
                type="number"
              />
            </v-col>
            <!-- non_tariff_amount -->

            <!-- tariff_type_charge -->
            <v-col
              cols="12"
              sm="12"
              md="12"
              v-show="formExonerations.is_tariffed != true"
            >
              <base-select-search
                label="Tarifa de dependencia"
                v-model.trim="$v.formExonerations.tariff_type_charge.$model"
                :items="tariffs"
                item="type_charge"
                :validation="$v.formExonerations.tariff_type_charge"
                @change="getTariffAmount()"
              />
            </v-col>
            <!-- tariff_type_charge -->

            <!-- tariff_value -->
            <v-col
              cols="12"
              sm="12"
              md="3"
              v-show="formExonerations.is_tariffed != true"
            >
              <h5 class="mb-0 mt-2 text-primary">
                Tarifa: ${{ this.tariff_value }}
              </h5>
            </v-col>
            <!-- tariff_value -->

            <!-- number_hour -->
            <v-col cols="12" sm="12" md="3">
              <base-input
                label="Evento/alquiler"
                v-model.number="$v.formExonerations.number_hour.$model"
                :validation="$v.formExonerations.number_hour"
                type="number"
                :disabled="this.formExonerations.number_people != ''"
              />
            </v-col>
            <!-- number_hour -->

            <!-- number_people -->
            <v-col cols="12" sm="12" md="3">
              <base-input
                label="Número de personas"
                v-model.number="$v.formExonerations.number_people.$model"
                :validation="$v.formExonerations.number_people"
                type="number"
                :disabled="this.formExonerations.number_hour != ''"
              />
            </v-col>
            <!-- number_people -->

            <v-col cols="12" sm="12" md="3">
              <!-- totalTariffNumberPeople -->
              <h5
                class="mb-0 text-primary"
                v-if="
                  this.formExonerations.number_people &&
                  formExonerations.is_tariffed != true
                "
              >
                Total por personas: ${{ totalTariffNumberPeople }}
              </h5>
              <!-- totalTariffNumberPeople -->
              <!-- totalTariffNumberHour -->
              <h5
                class="mb-0 text-primary"
                v-if="
                  this.formExonerations.number_hour &&
                  formExonerations.is_tariffed != true
                "
              >
                Total por evento/alquiler: ${{ totalTariffNumberHour }}
              </h5>
              <!-- totalTariffNumberHour -->
              <!-- totalNonTariffNumberPeople -->
              <h5
                class="mb-0 text-primary"
                v-if="
                  this.formExonerations.number_people &&
                  formExonerations.is_tariffed != false
                "
              >
                Total por personas: ${{ totalNonTariffNumberPeople }}
              </h5>
              <!-- totalNonTariffNumberPeople -->
              <!-- totalNonTariffNumberHour -->
              <h5
                class="mb-0 text-primary"
                v-if="
                  this.formExonerations.number_hour &&
                  formExonerations.is_tariffed != false
                "
              >
                Total por evento/alquiler: ${{ totalNonTariffNumberHour }}
              </h5>
              <!-- totalNonTariffNumberHour -->
            </v-col>
          </v-row>
          <!-- Igual a convenio -->

          <!-- exonerated_description -->
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <base-text-area
                label="Descripción"
                v-model.trim="$v.formExonerations.exonerated_description.$model"
                :validation="$v.formExonerations.exonerated_description"
                :rows="7"
                validationTextType="default"
                :max="500"
                :min="1"
                :validationsInput="{
                  required: true,
                  minLength: true,
                  maxLength: true,
                }"
              ></base-text-area>
              <!-- <div style="display: flex; justify-content: flex-end">
                <span class="">(Máximo 500 caracteres)</span>
              </div> -->
            </v-col>
          </v-row>
          <!-- exonerated_description -->

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
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">
                    Espacio
                  </th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">
                    ¿Tarifado?
                  </th>
                  <th v-if="editedItem.type_instrument_name != 'Convenio'">
                    Concepto
                  </th>
                  <th v-if="editedItem.type_instrument_name != 'Convenio'">
                    Cantidad
                  </th>
                  <th>Descripción</th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">
                    Descripción Tarifada/No tarifada
                  </th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">
                    Monto Tarifado/No tarifado
                  </th>
                  <th v-if="editedItem.type_instrument_name == 'Convenio'">
                    Cant. Horas/Personas
                  </th>

                  <th v-if="editedItem.type_instrument_name != 'Convenio'">
                    Precio estimado
                  </th>
                  <th>Total</th>
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
                  <td v-if="assigned.service_place_name">
                    {{ assigned.service_place_name }}
                  </td>
                  <td v-if="assigned.is_tariffed">
                    {{ assigned.is_tariffed }}
                  </td>
                  <td v-if="assigned.concept">
                    {{ assigned.concept }}
                  </td>
                  <td v-if="assigned.quantity">
                    {{ assigned.quantity }}
                  </td>
                  <td v-if="assigned.exonerated_description">
                    {{ assigned.exonerated_description }}
                  </td>
                  <td v-if="assigned.estimated_price">
                    {{ assigned.estimated_price }}
                  </td>
                  <td v-if="assigned.tariff_type_charge">
                    {{ assigned.tariff_type_charge }}
                  </td>
                  <td v-if="assigned.non_tariff_concept">
                    {{ assigned.non_tariff_concept }}
                  </td>
                  <td v-if="assigned.tariff_amount">
                    {{ assigned.tariff_amount }}
                  </td>
                  <td v-if="assigned.non_tariff_amount">
                    {{ assigned.non_tariff_amount }}
                  </td>
                  <td v-if="assigned.number_hour">
                    {{ assigned.number_hour }}
                  </td>
                  <td v-if="assigned.number_people">
                    {{ assigned.number_people }}
                  </td>
                  <td v-if="assigned.total_amount">
                    {{ assigned.total_amount }}
                  </td>
                  <td>
                    <v-icon @click="deleteAssignedExoneration(index) ">
                      delete
                    </v-icon>
                  </td>
                </tr>
              </tbody>
              <!-- body -->
            </v-simple-table>
            <!-- total table -->
            <div
              style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 16px;
              "
            >
              <span colspan="6" class="fw-bold">
                Total de exoneraciones por instrumento:
              </span>
              <span class="fw-bold">$ {{ total_value.toFixed(2) }}</span>
            </div>
            <!-- total table -->
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

    <!-- close confirm -->
    <v-dialog v-model="dialogCloseConfirm" max-width="500px">
      <v-card class="h-100">
        <v-container>
          <h3 class="black-secondary text-center mt-3 mb-3">
            ¿Esta seguro de salir?
          </h3>
          <h5 class="black-secondary text-center mt-3 mb-3">
            Si sales antes de guardar los cambios se perderán.
          </h5>
          <v-row>
            <v-col align="center">
              <v-btn
                color="btn-normal no-uppercase mt-3 mb-3 pr-5 pl-5 mx-auto"
                rounded
                @click="dialogExonerationCloseConfirm"
                >Confirmar</v-btn
              >
              <v-btn
                color="btn-normal-close no-uppercase mt-3 mb-3"
                rounded
                @click="dialogExonerationCloseCancel"
              >
                Cancelar
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- close confirm -->
  </div>
</template>

<script>
import instrumentApi from "../apis/instrumentApi";
import entityApi from "../apis/entityApi";
import typeApi from "../apis/typeApi";
import directionApi from "../apis/directionApi";
import dependenceApi from "../apis/dependenceApi";
import sectorApi from "../apis/sectorApi";
import exonerationApi from "../apis/exonerationApi";
import roleApi from "../apis/roleApi";
import userApi from "../apis/userApi";
import lib from "../libs/function";
import { required, minLength,maxLength, requiredIf,} from "vuelidate/lib/validators";
import axios from "axios";
import BaseInput from "./base-components/BaseInput.vue";
import { title } from "process";
import moment from "moment";
import { log } from 'util';
import Toast, { TYPE } from '../../../node_modules/vue-toastification';
import '../../sass/_variablesToast.scss';

const options = {
	toastDefaults: {
		[TYPE.SUCCESS]: {
			timeout: 2000,
			closeOnClick: false,
			icon: {
				iconClass: 'material-icons',
				iconChildren: 'check_circle',
				iconTag: 'span',
			},
			maxToasts: 1,
		},

		[TYPE.WARNING]: {
			timeout: 2000,
			closeOnClick: false,
			icon: {
				iconClass: 'material-icons',
				iconChildren: 'warning',
				iconTag: 'span',
			},
			maxToasts: 1,
		},
	},
};

Vue.use(Toast, options);

export default {
  components: { BaseInput },
  data() {
    return {
      search: "",
      dialog: false,
      dialogExoneration: false,
      dialogCloseConfirm: false,
      dialogDelete: false,
      //hasNewData: false,
      headers: [
        { text: "INSTRUMENTO", value: "instrument_name" },
        { text: "TIPO DE INSTRUMENTO", value: "type_instrument_name" },
        { text: "ENTIDAD", value: "entity_name" },
        { text: "SECTOR", value: "sector_name" },
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
        sector_name: "",
        assignedDependencies: [],
        assignedExonerations: [],
      },
      defaultItem: {
        instrument_name: "",
        description: "",
        type_instrument_name: "",
        entity_name: "",
        national_direction_name: "",
        sector_name: "",
        assignedDependencies: [],
        assignedExonerations: [],
      },
      formDependencies: {
        dependence_name: {},
      },
      formExonerations: {
        concept: "",
        quantity: "",
        estimated_price: "",
        dependence_name: "",
        date_event: moment().format("YYYY-MM-DD"),
        service_place_name: "",
        is_tariffed: false,
        non_tariff_concept: "",
        non_tariff_amount: 0,
        tariff_type_charge: "",
        tariff_amount: 0,
        number_hour: "",
        number_people: "",
        total_amount: 0,
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
      detalle: [],
      redirectSessionFinished: false,
      alertTimeOut: 0,
      debounce: 0,
      selectedTab: 0,
      tariff_value: 0,
      total_value: 0,
      total_value_view: 0,
      actualUser: {},
      dialogVerExoneration: false,
      dialogEditInstrument: false,
      exonerationSeleccionado: null,

    };
  },

  //Validations
  validations: {
    editedItem: {
      instrument_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(700),
      },
      type_instrument_name: {
        required,
      },
      description: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
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
      assignedDependencies: {
        required,
      },
      assignedExonerations: {
        // required,
      },
    },
    formDependencies: {
      dependence_name: {
        required,
      },
    },
    formExonerations: {
      concept: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name != "Convenio";
        }),
        minLength: minLength(1),
        maxLength: maxLength(200),
      },
      quantity: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name != "Convenio";
        }),
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      estimated_price: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name != "Convenio";
        }),
        minLength: minLength(1),
      },
      dependence_name: {},
      date_event: { required },
      service_place_name: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name == "Convenio";
        }),
      },
      is_tariffed: {},
      non_tariff_concept: {
        required: requiredIf(function (editedItem, formExonerations) {
          return (
            this.editedItem.type_instrument_name == "Convenio" &&
            this.formExonerations.tariff_type_charge == ""
          );
        }),
      },
      non_tariff_amount: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name == "Convenio";
        }),
      },
      tariff_type_charge: {
        required: requiredIf(function (editedItem) {
          return (
            this.editedItem.type_instrument_name == "Convenio" &&
            this.formExonerations.non_tariff_concept == ""
          );
        }),
      },
      tariff_amount: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name == "Convenio";
        }),
      },
      number_hour: {
        required: requiredIf(function (editedItem, formExonerations) {
          return (
            this.editedItem.type_instrument_name == "Convenio" &&
            this.formExonerations.number_people == ""
          );
        }),
      },
      total_amount: {
        required: requiredIf(function (editedItem) {
          return this.editedItem.type_instrument_name == "Convenio";
        }),
      },
      number_people: {
        required: requiredIf(function (editedItem) {
          return (
            this.editedItem.type_instrument_name == "Convenio" &&
            this.formExonerations.number_hour == ""
          );
        }),
      },
      exonerated_description: {
        required,
        /* required: requiredIf(function(editedItem){
        return this.editedItem.type_instrument_name == "Convenio"
      }), */
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
    },
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
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
    totalNonTariffNumberPeople: function () {
      if (
        !isNaN(parseFloat(this.formExonerations.non_tariff_amount)) &&
        !isNaN(parseFloat(this.formExonerations.number_people))
      ) {
        return (
          parseFloat(this.formExonerations.non_tariff_amount) *
          parseFloat(this.formExonerations.number_people)
        );
      } else {
        return 0;
      }
    },
    totalNonTariffNumberHour: function () {
      if (
        !isNaN(parseFloat(this.formExonerations.non_tariff_amount)) &&
        !isNaN(parseFloat(this.formExonerations.number_hour))
      ) {
        return (
          parseFloat(this.formExonerations.non_tariff_amount) *
          parseFloat(this.formExonerations.number_hour)
        );
      } else {
        return 0;
      }
    },
    thereAreData() {
      return this.editedItem.assignedExonerations.length > 0;
    },
    /* thereAreSavedData() {
      return this.editedItem.assignedExonerations.some(assignedExonerations => assignedExonerations.id);
    },
    canSaveChanges() {
      return this.thereAreData || this.thereAreSavedData;
    }, */
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
        sectorApi.get(),
        userApi.post("/actualUser"),
        dependenceApi.get(),
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
      this.sectors = responses[4].data.sectors;
      this.actualUser = responses[5].data.user;
      this.dependences = responses[6].data.dependences;

      this.recordsFiltered = this.records;
      this.loading = false;
    },

    addRecord() {
      this.editedIndex = -1;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.clearAssignedDependency();
      this.$v.$reset();
      this.dialog = true;
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
          this.redirectSessionFinished = lib.verifySessionFinished(
            res.status,
            200
          );
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

        if (res.data.status == "success") {
          this.redirectSessionFinished = lib.verifySessionFinished(
            res.status,
            200
          );
          this.updateAlert(true, res.data.message, "success");
        }
      }

      this.close();
      this.initialize();
      return;
    },

    editItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    editItemDependency(item) {
      /* this.editedIndex = this.recordsFiltered.indexOf(item); */
      this.editedItem = Object.assign({}, item);
      
      const selectedDependence = this.editedItem.assignedDependencies;
      console.log(selectedDependence);
			const existsIndex = this.editedItem.assignedDependencies.findIndex(
				(det) => det.dependence_name === selectedDependence
			);

			if (existsIndex !== -1) {
				this.editedItem.assignedDependencies.splice(existsIndex, 1);
			}

      this.dialogEditInstrument = true;
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
        this.clearAssignedDependency();
      });
    },

    closeExoneration() {
      this.dialogCloseConfirm = true;
    },

    dialogExonerationCloseConfirm() {
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
      });

      this.dialogCloseConfirm = false;
      this.$v.formExonerations.$reset();
      this.dialogExoneration = false;
    },

    dialogExonerationCloseCancel() {
      this.dialogCloseConfirm = false;
    },

    async viewExonerationItem(item) {
      try {
				const { data } = await instrumentApi.get('/getInstrument/' + item.id);
				this.exonerationSeleccionado = data;
				this.dialogVerExoneration = true;
			} catch (error) {}
		},

		closeViewExoneration() {
			this.dialogVerExoneration = false;
		},

    addNewExoneration(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.calculateTotalValue();
      this.dialogExoneration = true;
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

    closeReset() {
      this.dialogExoneration = false;
    },

    /* hasExistingExonerations() {
      // Verificar si existen exoneraciones en la base de datos
      return this.editedItem.assignedExonerations.some(exoneration => exoneration.id !== null);
    }, */

    async addExoneration() {
      this.$v.editedItem.assignedExonerations.$touch();

      if (this.$v.editedItem.assignedExonerations.$invalid) {
        return;
      }
      const originalRecordsCount  = this.editedItem.assignedExonerations.filter(assigned => assigned.id).length;

      const currentRecordsCount = this.editedItem.assignedExonerations.length;

      const recordsDeleted = currentRecordsCount < originalRecordsCount ;

      if(currentRecordsCount === originalRecordsCount  && !recordsDeleted){
        return;
      }

      const res = await exonerationApi
        .post(null, this.editedItem, this.editedItem.assignedExonerations)
        .catch((error) => {
          this.updateAlert(true, "No fue posible crear el registro.", "fail");
        });

      if (res.data.status == "success") {
        this.updateAlert(true, "Registro almacenado correctamente.", "success");
      }
      this.closeReset();
      this.initialize();
      return;
    },

    assingExoneration() {
      this.$v.formExonerations.$touch();

      if (this.$v.formExonerations.$invalid) {
        return;
      }

      if (this.editedItem.type_instrument_name == "Convenio") {
        //setting tariff value if true/false
        if (this.formExonerations.is_tariffed == true) {
          this.formExonerations.is_tariffed = "No";
        } else {
          this.formExonerations.is_tariffed = "Sí";
        }

        if (this.formExonerations.is_tariffed == "Sí") {
          //set tariff value
          this.formExonerations.tariff_amount = this.tariff_value;

          //total by number hour/people
          if (this.formExonerations.number_hour !== "") {
            this.formExonerations.total_amount =
              this.formExonerations.tariff_amount *
              this.formExonerations.number_hour;
          } else {
            this.formExonerations.total_amount =
              this.formExonerations.tariff_amount *
              this.formExonerations.number_people;
          }
        } else {
          //total non tariff by number hour/people
          if (this.formExonerations.number_hour !== "") {
            this.formExonerations.total_amount =
              this.formExonerations.non_tariff_amount *
              this.formExonerations.number_hour;
          } else {
            this.formExonerations.total_amount =
              this.formExonerations.non_tariff_amount *
              this.formExonerations.number_people;
          }
        }
      }

      if (this.editedItem.type_instrument_name != "Convenio") {
        // total by quantity * estimated price
        this.formExonerations.total_amount =
          this.formExonerations.quantity *
          this.formExonerations.estimated_price;
      }

      //push
      this.editedItem.assignedExonerations.push({
        ...this.formExonerations,
      });
      
      this.calculateTotalValue();

      //this.hasNewData = true;

      //clear inputs
      this.formExonerations.concept = "";
      this.formExonerations.quantity = "";
      this.formExonerations.estimated_price = "";
      this.formExonerations.dependence_name = "";
      this.formExonerations.service_place_name = "";
      this.formExonerations.is_tariffed = false;
      this.formExonerations.non_tariff_concept = "";
      this.formExonerations.non_tariff_amount = 0;
      this.formExonerations.tariff_type_charge = "";
      this.formExonerations.tariff_amount = 0;
      this.formExonerations.number_hour = "";
      this.formExonerations.number_people = "";
      this.formExonerations.exonerated_description = "";
      this.tariff_value = 0;

      //reset alerts
      this.$v.formExonerations.$reset();
    },

    deleteAssignedExoneration(index) {
      const exoneration = this.editedItem.assignedExonerations[index];

      if (this.actualUser.role === 'Administrador') {
        // Si el usuario es administrador, se permite eliminar la exoneración sin restricciones
        this.editedItem.assignedExonerations.splice(index, 1);
        this.calculateTotalValue();
      } else if (this.actualUser.role != 'Administrador' && exoneration.id == null) {
        // Si el usuario es usuario normal y la exoneración no está guardada (id == null),
        // se permite eliminar la exoneración
        this.editedItem.assignedExonerations.splice(index, 1);
        this.calculateTotalValue();
      } else {
        // Si el usuario es usuario normal y la exoneración está guardada, se muestra una alerta
        this.updateAlert(true, "No tienes permiso para eliminar esta exoneración.", "fail");
      }
    },

    assignDependency() {
      this.$v.formDependencies.$touch();

      if (this.$v.formDependencies.$invalid) {
        return;
      }

      const selectedDependence = this.formDependencies.dependence_name;

			const dependenceSelectedArray = this.dependences.find((dependence) => {
				return dependence.dependence_name == selectedDependence;
			});

			const exists = this.editedItem.assignedDependencies.find((det) => det.dependence_name === selectedDependence);
      
			if (dependenceSelectedArray && !exists) {
				this.editedItem.assignedDependencies.push({
					dependence_name: this.formDependencies.dependence_name,
				});
				this.$toast.success('Dependencia agregada');
			} else {
				this.$toast.warning('Ya ingresó ' + selectedDependence + ' al registro.');
			} 

     /*  this.editedItem.assignedDependencies.push(
        this.formDependencies.dependence_name
      ); */
      this.formDependencies.dependence_name = {};
      this.$v.formDependencies.$reset();
    },

    deleteAssignedDependency(index) {
      this.editedItem.assignedDependencies.splice(index, 1);
      this.formDependencies.dependence_name = "";
    },

    clearAssignedDependency() {
      this.editedItem.assignedDependencies.splice(0);
    },

    calculateTotalValue() {
      this.total_value = 0;
      this.editedItem.assignedExonerations.forEach((element) => {
        parseFloat((this.total_value += element.total_amount));
      });
    },

    updateAlert(show = false, text = "Alerta", event = "success") {
      this.showAlert = show;
      this.textAlert = text;
      this.alertEvent = event;
    },

    updateTimeOut(event) {
      this.redirectSessionFinished = event;
    },
  },
};
</script>