<template>
  <div>
    <section-header text="Kezelések" />
    <v-data-table
      :headers="headers"
      :items="treatments"
      :search="search"
      :no-data-text="nodata"
      :page.sync="page"
      :items-per-page="itemsPerPage"
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
            <v-icon dark left>mdi-plus</v-icon>Új Kezelés
          </v-btn>
       </div>
        <table-pagination :pageCount='pageCount' :page="page" :itemsPerPage="itemsPerPage" @change-per-page="(perPage) => {itemsPerPage = perPage; page = ((page == null && perPage > 0 ) ? 1 : page)}" @change-page="(changePage) => page = changePage"/>
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

    <v-dialog v-model="show_dialog" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Kezelés adatai</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
              <v-col>
                  <v-select
                      :items="cultures"
                      item-text="name"
                      item-value="uuid"
                      v-model="showedItem.culture_uuid"
                      label="Kultúra"
                  ></v-select>
              </v-col>
           <v-col>
                  <v-select
                      :items="diseases"
                      item-text="name"
                      item-value="uuid"
                      v-model="showedItem.disease_uuid"
                      label="Betegség"
                  ></v-select>
              </v-col>
              <v-col>
                  <v-select
                      :items="fitoProducts"
                      item-text="name"
                      item-value="uuid"
                      v-model="showedItem.fitoproduct_uuid"
                      label="Növényvédő"
                  ></v-select>
              </v-col>
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
            >Kezelés frissítése</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="show_dialog2" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Új Kezelés</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col>
                  <v-select
                      :items="cultures"
                      item-text="name"
                      item-value="uuid"
                      v-model="newItem.culture_uuid"
                      label="Kultúra"
                  ></v-select>
              </v-col>
           <v-col>
                  <v-select
                      :items="diseases"
                      item-text="name"
                      item-value="uuid"
                      v-model="newItem.disease_uuid"
                      label="Betegség"
                  ></v-select>
              </v-col>
              <v-col>
                  <v-select
                      :items="fitoProducts"
                      item-text="name"
                      item-value="uuid"
                      v-model="newItem.fitoproduct_uuid"
                      label="Növényvédő"
                  ></v-select>
              </v-col>
            </v-row>
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
            >Kezelés létrehozása</v-btn
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
export default {
  components: { SectionHeader,TablePagination },
  data() {
    return {
    headers: [
        {
          text: "Kultúra",
          align: "left",
          value: "culture_name",
        },
        { text: "Betegség", value: "disease_name" },
        { text: "Növényvédő", value: "fitoproduct_name"},
        { text: "Akciók", value: "action", align: "right" },
      ],
      nodata: "Nincsenek adatok",
      items: [],
      treatments: [],
      diseases: [],
      fitoProducts: [],
      cultures: [],
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      search: "",
      show_dialog: false,
      show_dialog2: false,
      menu: false,
      loading: false,
      showedIndex: -1,
      showedItem: {
      },
      newItem: {
      },
    };
  },
  created() {
    this.loadItems();
  },
  methods: {
    loadItems: function () {
      axios
        .get("api/treatments")
        .then((response) => {
          this.treatments = response.data;
        })
        .catch((e) => {});
        axios
        .get("api/treatments/create")
        .then((response) => {
          this.diseases = response.data['diseases'];
          this.cultures  = response.data['cultures'];
          this.fitoProducts = response.data['fitoproducts'];
        })
        .catch((e) => {});
    },
    changePerPage: function (perPage){
        this.itemsPerPage = perPage
    },
    changePage: function (page){
        this.page = page
    },
    updateItem: function () {
      axios
        .put("api/treatments/" + this.showedItem.uuid, {
          culture_uuid: this.showedItem.culture_uuid,
          fitoproduct_uuid: this.showedItem.fitoproduct_uuid,
          disease_uuid: this.showedItem.disease_uuid,
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
        .post("api/treatments", {
          culture_uuid: this.newItem.culture_uuid,
          fitoproduct_uuid: this.newItem.fitoproduct_uuid,
          disease_uuid: this.newItem.disease_uuid,
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
      this.showedIndex = this.treatments.indexOf(item);
      this.showedItem = Object.assign({}, item);
      this.show_dialog = true;
    },
    showNewItem() {
      this.show_dialog2 = true;
    },
    deleteItem(item) {
        axios
            .delete("api/treatments/" + item.uuid)
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

  mounted() {},
};
</script>
<style>

</style>
