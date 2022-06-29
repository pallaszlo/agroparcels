<template>
  <div>
    <section-header text="Csoportok" />
    <v-data-table
      :headers="headers"
      :items="groups"
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
            <v-icon dark left>mdi-plus</v-icon>Új csoport
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

    <v-dialog v-model="show_dialog" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Csoport adatai</v-toolbar-title>
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
                  v-model="showedItem.active"
                  :label="`Aktiv`"
                  :error-messages="errors.collect('aktiv1')"
                ></v-checkbox>
              </v-col>
              <v-col>
                  <v-select
                      :items="companies"
                      item-text="name"
                      item-value="uuid"
                      v-model="showedItem.company_uuid"
                      label="Cég"
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
            dark @click.native="validateItem"
            >Csoport frissítése</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="show_dialog2" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Új Csoport</v-toolbar-title>
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
                  v-model="newItem.active"
                  :label="`Aktiv`"
                  :error-messages="errors.collect('aktiv1')"
                ></v-checkbox>
              </v-col>
              <v-col>
                  <v-select
                      :items="companies"
                      item-text="name"
                      item-value="uuid"
                      v-model="newItem.company_uuid"
                      label="Cég"
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
export default {
  components: { SectionHeader,TablePagination },
  data() {
    return {
    headers: [
        {
          text: "Név",
          align: "left",
          value: "name",
        },
        { text: "Cég név", value: "company[name]" },
        { text: "Aktív", value: "active" },
        { text: "Akciók", value: "action" },
      ],
      nodata: "Nincsenek adatok",
      items: [],
      groups: [],
      companies: [],
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
        uuid: 0,
        company_uuid: 0,
        name: "",
        active: 0,
      },
      newItem: {
        name: "",
        company_uuid: "",
        active: true,
      },
    };
  },
  created() {
    this.loadItems();
  },
  methods: {
    loadItems: function () {
      axios
        .get("api/groups")
        .then((response) => {
          this.groups = response.data;
        })
        .catch((e) => {});

        axios.get("api/groups/create")
        .then((response) => {
            this.companies = response.data
        })
        .catch((e)=>{});
    },
    changePerPage: function (perPage){
        this.itemsPerPage = perPage
    },
    changePage: function (page){
        this.page = page
    },
    updateItem: function () {
      axios
        .put("api/groups/" + this.showedItem.uuid, {
          name: this.showedItem.name,
          active: this.showedItem.active,
          company_uuid: this.showedItem.company_uuid
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
        .post("api/groups", {
          name: this.newItem.name,
          active: this.newItem.active,
          company_uuid: this.newItem.company_uuid
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
      this.showedIndex = this.groups.indexOf(item);
      this.showedItem = Object.assign({}, item);
      this.show_dialog = true;
    },
    showNewItem() {
      this.show_dialog2 = true;
    },
    deleteItem(item) {
        axios
            .delete("api/groups/" + item.uuid)
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
