<template>
<div>
<section-header :text='layer.name.toUpperCase() + " réteg"' />
     <v-row class="details-row">
         <v-col cols="12" sm="12" md="6">
            <v-data-table
                v-model="select"
                :headers="fitoHeaders"
                :single-select="true"
                :items="fitoproducts"
                :search="search"
                :no-data-text="nodata"
                :page.sync="page"
                :items-per-page="itemsPerPage"
                item-key="uuid"
                @page-count = "pageCount = $event"
                hide-default-footer
                class="table"
                >
                <template v-slot:top class="toolbar">
                <div class="toolbar">
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Keresés"
                        dense
                        hide-details
                        solo
                        single-line
                        class="search"></v-text-field>
                </div>
                </template>
                <template v-slot:item.active="{ item }">
                <v-chip
                    :color="getColor(item.active)"
                    dark
                >
                    {{ item.active ? "Aktiv" : "Nem Aktiv" }}
                </v-chip>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon small color="teal" class="mr-2" @click="editItem(item)"
                    >edit</v-icon
                    >
                    <v-icon small color="red" class="mr-2" @click="deleteItem(item)"
                    >mdi-delete</v-icon
                    >
                </template>
                <template v-slot:footer>
                        <table-pagination :pageCount='pageCount' :page="page" :itemsPerPage="itemsPerPage" @change-per-page="(perPage) => {itemsPerPage = perPage; page = ((page == null && perPage > 0 ) ? 1 : page)}" @change-page="(changePage) => page = changePage"/>
                </template>
    </v-data-table>
          </v-col>
          <v-col cols="12" sm="12" md="6">
              <layers-map :coordinates="coordinates" :zoom="25" :center="coordinates[0]" :layer="layer" :drawActive="false" :editActive="false"/>
            </v-col>
     </v-row>
    <v-data-table
       v-model="selected"
      :headers="headers"
      :single-select="true"
      :items="works"
      :search="search"
      :no-data-text="nodata"
      :page.sync="page"
      :items-per-page="itemsPerPage"
      item-key="uuid"
      @page-count = "pageCount = $event"
      hide-default-footer
      class="table"
    >
      <template v-slot:top class="toolbar">
       <div class="toolbar">
           <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Keresés"
            dense
            hide-details
            solo
            single-line
            class="search"></v-text-field>
          <v-btn
            class="text-none new"
            tile
            dark
            @click="showNewItem()">
            <v-icon dark left>mdi-plus</v-icon>Új réteg
          </v-btn>
       </div>
        <table-pagination :pageCount='pageCount' :page="page" :itemsPerPage="itemsPerPage" @change-per-page="(perPage) => {itemsPerPage = perPage; page = ((page == null && perPage > 0 ) ? 1 : page)}" @change-page="(changePage) => page = changePage"/>
      </template>
    <template v-slot:item.active="{ item }">
    <v-chip
        :color="getColor(item.active)"
        dark
    >
        {{ item.active ? "Aktiv" : "Nem Aktiv" }}
    </v-chip>
    </template>
      <template v-slot:item.action="{ item }">
        <v-icon small color="teal" class="mr-2" @click="editItem(item)"
          >edit</v-icon
        >
        <v-icon small color="red" class="mr-2" @click="deleteItem(item)"
          >mdi-delete</v-icon
        >
      </template>
    <template v-slot:footer>
            <table-pagination :pageCount='pageCount' :page="page" :itemsPerPage="itemsPerPage" @change-per-page="(perPage) => {itemsPerPage = perPage; page = ((page == null && perPage > 0 ) ? 1 : page)}" @change-page="(changePage) => page = changePage"/>
    </template>
    </v-data-table>

    <v-dialog v-model="show_dialog" fullscreen>
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Réteg adatai</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="name1"
                  v-model="showedItem.name"
                  label="Név"
                  v-validate="'required'"
                  :error-messages="errors.collect('nev')"
                >
                </v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="4">
               <v-checkbox
                  name="active1"
                  v-model="showedItem.default"
                  :label="`Default`"
                  :error-messages="errors.collect('aktiv1')"
                ></v-checkbox>
              </v-col>
              <v-col>
                  <v-select
                      :items="parcels"
                      item-text="name"
                      item-value="uuid"
                      v-model="showedItem.parcel_uuid"
                      label="Parcella"
                  ></v-select>
              </v-col>
              <v-col>
                  <v-select
                      :items="seasons"
                      item-text="name"
                      item-value="uuid"
                      v-model="showedItem.season_uuid"
                      label="Évszak"
                  ></v-select>
              </v-col>
            </v-row>
            <layers-map
                :coordinates="showedItem.coordinates"
                :name="`edit`"
                :editActive="true"
                :drawActive="false"
                :resetBtn="true"
                @update:coordinates ="(newCoords) => {this.showedItem.coordinates = newCoords;}"/>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="red darken-1" outlined @click.native="close"
            >Bezár</v-btn
          >
           <v-btn :loading="loading"
            class="text-none new"
            tile
            dark @click.native="validateItem"
            >Réteg frissítése</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="show_dialog2" fullscreen>
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Új réteg</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="name1"
                  v-model="newItem.name"
                  label="Név"
                  v-validate="'required'"
                  :error-messages="errors.collect('nev')"
                >
                </v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="4">
               <v-checkbox
                  name="active1"
                  v-model="newItem.default"
                  :label="`Default`"
                  :error-messages="errors.collect('aktiv1')"
                ></v-checkbox>
              </v-col>
              <v-col>
                  <v-select
                      :items="parcels"
                      item-text="name"
                      item-value="uuid"
                      v-model="newItem.parcel_uuid"
                      label="Parcellák"
                  ></v-select>
              </v-col>
              <v-col>
                  <v-select
                      :items="seasons"
                      item-text="name"
                      item-value="uuid"
                      v-model="newItem.season_uuid"
                      label="Évszakok"
                  ></v-select>
              </v-col>
            </v-row>
            <layers-map :coordinates="newItem.coordinates" :name="`new`" :editActive="true" :drawActive="true" :resetBtn="true" @update:coordinates ="(newCoords) => {this.newItem.coordinates = newCoords;}"/>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="red darken-1" outlined @click.native="close"
            >Bezár</v-btn
          >
          <v-btn :loading="loading"
            class="text-none new"
            tile
            dark @click.native="validateNewItem"
            >Csoport létrehozása</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>


  </div>
</template>
<script>
import axios from 'axios';
import SectionHeader from './compnents/SectionHeader.vue';
import TablePagination from './compnents/TablePagination.vue';
import LayersMap from './compnents/LayersMap.vue';
export default {
    components: { SectionHeader,TablePagination, LayersMap },
  data() {
    return {
        uuid: this.$route.params.uuid,
        headers: [
            {
                text: "Munkálat neve",
                align: "left",
                value: "work_name",
            },
            { text: "Évszak neve", value: "season_name", groupable: true},
            { text: "Kultúra", value: "culture_name"},
            { text: "Növényvédő", value: "treatment_name"},
            { text: "Mennyiség", value: "quantity"},
            { text: "Állapot", value: "status_name"},
            { text: "Munkások száma", value: "people_count"},
            { text: "Eltöltött órák", value: "worked_hours"},
        ],
        fitoHeaders: [
            {
                text: "Növénvyvédő neve",
                align: "left",
                value: "name",
            },
            {
                text: "Mennyiség",
                align: "left",
                value: "quantity",
            },
            {
                text: "Mértékegység",
                value: 'unit',
            }
        ],

      nodata: "Nincsenek adatok",
      items: [],
      works: [],
      layer: [],
      parcels: [],
      seasons: [],
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      search: "",
      show_dialog: false,
      show_dialog2: false,
      menu: false,
      loading: false,
      selected: [],
      showedIndex: -1,
      showedItem: {
        uuid: 0,
        parcel_uuid: 0,
        season_uuid: 0,
        name: "",
        coordinates: [],
      },
      newItem: {
        name: "",
        company_uuid: "",
        active: true,
      },
      fitoproducts: [],
      coordinates: [],
      data: [],
      rotation: 0,
      drawType: 'Polygon',
      drawnFeatures: [],
      drawActive: true,
      layerActive: true,
    };
  },
  created() {
    this.loadItems();
  },
  methods: {
    loadItems: function () {
      axios
        .get("api/layers/" + this.uuid)
        .then((response) => {
          this.layer = response.data;
          this.works = response.data.operations;
          this.fitoproducts = Object.values(response.data.fitoproducts);
          //this.layer.json = JSON.parse(this.layer.json);
          this.coordinates = [{type: "Feature", "geometry": JSON.parse(this.layer.json), properties: null, id: this.layer.uuid}];
        })
        .catch((e) => {});
    axios.get("api/layers/create").then((response) => {
      this.parcels = response.data.parcels;
      this.seasons = response.data.seasons;
    }).catch((e) => {});
    },
    changePerPage: function (perPage){
        this.itemsPerPage = perPage
    },
    changePage: function (page){
        this.page = page
    },
    updateItem: function () {
        console.log(typeof(this.showedItem.coordinates[0]));
      axios
        .put("api/layers/" + this.showedItem.uuid, {
            name: this.showedItem.name,
            default: this.showedItem.default,
            parcel_uuid: this.showedItem.parcel_uuid,
            season_uuid: this.showedItem.season_uuid,
            coordinates: this.showedItem.coordinates[0].geometry,
            fitoproducts: this.showedItem.fitoproducts,
        })
        .then((response) => {
          this.close();
          this.$store.commit("snackbar/setSnack", response.data);
          this.loadItems();
        })
        .catch((e) => {
          console.log(e);
          this.$store.commit("snackbar/setSnack", e.response.data);
        });
    },

    addNewItem: function () {
        this.loading = true;
      axios
        .post("api/layers", {
            name: this.newItem.name,
            default: this.newItem.default,
            parcel_uuid: this.newItem.parcel_uuid,
            season_uuid: this.newItem.season_uuid,
            coordinates: this.newItem.coordinates[0].geometry,
        })
        .then((response) => {
          this.close();
          this.$store.commit("snackbar/setSnack", response.data);
          this.loading = false;
          this.$validator.reset();
          //window.location.reload();
          this.loadItems();
        })
        .catch((e) => {
          this.$store.commit("snackbar/setSnack", e.response.data);
          this.loading = false;
        });
    },
    editItem(item) {
      this.showedIndex = this.works.indexOf(item);
      this.showedItem = Object.assign({}, item);
      this.showedItem.coordinates = [{type: "Feature", "geometry": JSON.parse(this.showedItem.json), properties: null, id: this.showedItem.uuid}];
      console.log(this.showedItem.coordinates.geometry);
      this.show_dialog = true;
    },
    showNewItem() {
      this.show_dialog2 = true;
    },
    deleteItem(item) {
        axios
            .delete("api/layers/" + item.uuid)
            .then((response) => {
                this.close();
                this.$store.commit("snackbar/setSnack", response.data);
                this.loading = false;
                this.$validator.reset();
                //window.location.reload();
                this.loadItems();
            })
    },
    close() {
      this.show_dialog = false;
      this.show_dialog2 = false;
      this.$validator.reset();
    },
    save(date) {
      this.$refs.menu.save(date);
    },
    async validateItem() {
      const result = await this.$validator.validate();
      if (result) {
        this.updateItem();
      }
    },
    async validateNewItem() {
      const result = await this.$validator.validate();
      if (result) {
        this.addNewItem();
      }
    },
    getColor: function (aktiv) {
        return aktiv ? 'green' : 'red';
    }
  },
  watch: {
        'drawnFeatures': function(newVal, oldVal){
            if(newVal.length > 0){
                this.drawActive = false;
            }
        },
        'selected': function(newVal, oldVal){
            console.log(newVal)
            this.coordinates = newVal.length != 0 ? [{type: "Feature", "geometry": JSON.parse(newVal[0].json), properties: null, id: newVal.uuid}] : [];
            axios
                .get("api/layers/"  + newVal[0].uuid)
                .then((response) => {
                    console.log(this.response.data);
                })
                .catch((e) => {});
            // this.center = this.coordinates[0][0];
        }
    }
};
</script>
<style>
.details-row{
    margin-bottom: 40px;
}
</style>
