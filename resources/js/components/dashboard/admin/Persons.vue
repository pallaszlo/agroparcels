<template>
  <div>
    <section-header text="Személyek" />
    <v-data-table
      :headers="headers"
      :items="persons"
      :search="search"
      :no-data-text="nodata"
      :page.sync="page"
      :items-per-page="itemsPerPage"
      @page-count = "pageCount = $event"
      @dblclick:row = 'toDetails'
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
            <v-icon dark left>mdi-plus</v-icon>Új Személy
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
          <v-toolbar-title>Személy adatai</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="name"
                  v-model="showedItem.name"
                  label="Név"
                  v-validate="'required'"

                >
                </v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="username2"
                  v-model="showedItem.username"
                  label="Felhasználónév"
                  v-validate="'required'"

                >
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="email2"
                  v-model="showedItem.email"
                  label="Email"
                  v-validate="'required'"

                >
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-select
                  name="roles"
                  :items = "roles"
                  item-text="name"
                  item-value="id"
                  v-model="showedItem.role"
                  label="Szerepkör"
                  v-validate="'required'"

                >
                </v-select>
              </v-col>
              <v-col cols="12" sm="12" md="5">
                <v-select
                  name="groups"
                  :items = "groups"
                  item-text="name"
                  item-value="uuid"
                  v-model="showedItem.group"
                  label="Csoport"
                  v-validate="'required'"

                >
                </v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="10">
                <v-select
                  name="works"
                  :items = "works"
                  item-text="name"
                  item-value="uuid"
                  v-model="showedItem.works"
                  label="Munkálatok"
                  filled
                  chips
                  multiple

                  :error-messages="errors.collect('role')"
                >
                </v-select>
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
            >Személy frissítése</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="show_dialog2" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Új Személy</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="name"
                  v-model="newItem.name"
                  label="Név"
                  v-validate="'required'"
                  :error-messages="errors.collect('nev')"
                >
                </v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="username"
                  v-model="newItem.username"
                  label="Felhasználónév"
                  v-validate="'required'"
                  :error-messages="errors.collect('username')"
                >
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="email"
                  v-model="newItem.email"
                  label="Email"
                  v-validate="'required'"
                  :error-messages="errors.collect('email')"
                >
                </v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="password"
                  v-model="newItem.password"
                  label="Jelszó"
                  v-validate="'required'"
                  :error-messages="errors.collect('password')"
                >
                </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-select
                  name="roles"
                  :items = "roles"
                  item-text="name"
                  item-value="id"
                  v-model="newItem.role"
                  label="Szerepkör"
                  v-validate="'required'"
                  :error-messages="errors.collect('role')"
                >
                </v-select>
              </v-col>
              <v-col cols="12" sm="12" md="5">
                <v-select
                  name="groups"
                  :items = "groups"
                  item-text="name"
                  item-value="uuid"
                  v-model="newItem.group"
                  label="Csoport"
                  v-validate="'required'"
                  :error-messages="errors.collect('grup')"
                >
                </v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="10">
                <v-select
                  name="works"
                  :items = "works"
                  item-text="name"
                  item-value="uuid"
                  v-model="newItem.works"
                  label="Munkálatok"
                  filled
                  chips
                  multiple
                  v-validate="'required'"
                  :error-messages="errors.collect('role')"
                >
                </v-select>
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
            >Személy létrehozása</v-btn
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
        {
            text: "Csoport",
            value: 'group_name',
        },
        {
            text: "email",
            value: 'email',
        },

        { text: "Akciók", value: "action", align: "right" },
      ],
      nodata: "Nincsenek adatok",
      items: [],
      groups: [],
      roles: [],
      works: [],
      persons: [],
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
        .get("api/persons")
        .then((response) => {
          this.persons = response.data;
        })
        .catch((e) => {});
      axios.get('api/persons/create')
        .then((response) => {
          console.log(response.data);
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
        .put("api/persons/" + this.showedItem.uuid, {
          name: this.showedItem.name,
          username: this.showedItem.username,
            email: this.showedItem.email,
            role: this.showedItem.role,
            works: this.showedItem.works,
            group_uuid: this.showedItem.group,
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
        .post("api/persons", {
          name: this.newItem.name,
          username: this.newItem.username,
          email: this.newItem.email,
            password: this.newItem.password,
            roll: this.newItem.role,
            group_uuid: this.newItem.group,
            works: this.newItem.works,
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
      this.showedIndex = this.persons.indexOf(item);
      this.showedItem = Object.assign({}, item);
      this.show_dialog = true;
      axios.get('api/persons/' + item.uuid + '/edit')
        .then((response) => {
          this.works = response.data.works;
          this.groups = response.data.groups;
          this.roles = response.data.roles;
          this.showedItem.group = response.data.group_uuid;
          this.showedItem.works = response.data.attached_works;
          this.showedItem.username = response.data.username;
            this.showedItem.role = response.data.role;
        })
        .catch((e) => {});
    },
    showNewItem() {
        this.show_dialog2 = true;
        axios.get('api/persons/create')
            .then((response) => {
                this.roles = response.data.roles;
                this.works = response.data.works;
                this.groups = response.data.groups;
            })
            .catch((e) => {});
    },
    deleteItem(item) {
        axios
            .delete("api/persons/" + item.uuid)
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
    },
    toDetails(event, {item}) {
        this.$router.push('/dashboard/persons/' + item.uuid);
    }
  },

  mounted() {},
};
</script>
<style>

</style>
