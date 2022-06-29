<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="users"
      :search="search"
      :no-data-text="nodata"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Felhasználók</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Keresés"
            single-line
            hide-details
          ></v-text-field>
          <v-btn
            class="mb-2 text-none"
            tile
            dark
            small
            color="blue-grey darken-1"
            @click="showNewUser()"
          >
            <v-icon dark left>mdi-plus</v-icon>Új felhasználó
          </v-btn>
        </v-toolbar>
      </template>

      <template v-slot:item.action="{ item }">
        <v-icon small color="teal" class="mr-2" @click="editItem(item)"
          >edit</v-icon
        >
        <!--
        <v-icon small color="red" class="mr-2" @click="deleteItem(item)"
          >mdi-delete</v-icon
        >
        -->
      </template>
    </v-data-table>

    <v-dialog v-model="show_dialog" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Felhasználó adatai</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  name="nev1"
                  v-model="showedItem.nev"
                  label="Név"
                  v-validate="'required'"
                  :error-messages="errors.collect('nev1')"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  name="email1"
                  v-model="showedItem.email"
                  label="Email cím"
                  v-validate="'required|email'"
                  :error-messages="errors.collect('email1')"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  name="telefonszam1"
                  v-model="showedItem.telefonszam"
                  label="Telefonszám"
                  v-validate="'numeric'"
                  :error-messages="errors.collect('telefonszam1')"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="showedItem.role"
                  label="Szerep"
                  disabled
                ></v-text-field>
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
            dark @click.native="validateUser"
            >Felhasználó frissítése</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="show_dialog2" width="750px">
      <v-card>
        <v-toolbar dense dark color="#292929">
          <v-toolbar-title>Új felhasználó</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="5">
                <v-text-field
                  name="nev"
                  v-model="newUser.nev"
                  label="Név"
                  v-validate="'required'"
                  :error-messages="errors.collect('nev')"
                >
                </v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="4">
                <v-text-field
                  name="email"
                  v-model="newUser.email"
                  label="Email cím"
                  v-validate="'required|email'"
                  :error-messages="errors.collect('email')"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="3">
                <v-select
                  name="role"
                  v-model="newUser.role"
                  label="Szerep"
                  :items="roles"
                  v-validate="'required'"
                  :error-messages="errors.collect('role')"
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
            dark @click.native="validateNewUser"
            >Felhasználó létrehozása</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      headers: [
        {
          text: "Név",
          align: "left",
          value: "nev",
        },
        { text: "Email cím", value: "email" },
        { text: "Telefonszám", value: "telefonszam" },
        { text: "Szerep", value: "role" },
        { text: "Műveletek", value: "action" },
      ],
      nodata: "Nincsenek adatok",
      items: [],
      users: [],
      roles: [],
      search: "",
      show_dialog: false,
      show_dialog2: false,
      isIskolas: false,
      menu: false,
      loading: false,
      showedIndex: -1,
      showedItem: {
        id: 0,
        nev: "",
        email: 0,
        telefonszam: 0,
        role: "",
      },
      newUser: {
        nev: "",
        email: "",
        role: "",
      },
    };
  },
  created() {
    this.loadUsers();
    this.loadRoles();
    //this.betoltIskolak();
  },
  methods: {
    loadUsers: function () {
      axios
        .get("api/users")
        .then((response) => {
          this.users = response.data;
        })
        .catch((e) => {});
    },
    loadRoles: function () {
      axios
        .get("api/roles")
        .then((response) => {
          this.roles = response.data;
        })
        .catch((e) => {});
    },
    updateUser: function () {
      axios
        .post("api/users/edit", {
          id: this.showedItem.id,
          nev: this.showedItem.nev,
          email: this.showedItem.email,
          telefonszam: this.showedItem.telefonszam,
          role: this.showedItem.role,
        })
        .then((response) => {
          this.close();
          this.$store.commit("snackbar/setSnack", response.data);
          window.location.reload();
        })
        .catch((e) => {
          console.log(e);
          this.$store.commit("snackbar/setSnack", e.response.data);
        });
    },
    addNewUser: function () {
        this.loading = true;
      axios
        .post("api/users", {
          nev: this.newUser.nev,
          email: this.newUser.email,
          role: this.newUser.role,
        })
        .then((response) => {
          this.close();
          this.$store.commit("snackbar/setSnack", response.data);
          this.loading = false;
          this.$validator.reset();
          window.location.reload();
        })
        .catch((e) => {
          this.$store.commit("snackbar/setSnack", e.response.data);
          this.loading = false;
        });
    },
    editItem(item) {
      this.showedIndex = this.users.indexOf(item);
      this.showedItem = Object.assign({}, item);
      this.show_dialog = true;
    },
    showNewUser() {
      this.show_dialog2 = true;
    },
    deleteItem(item) {},
    close() {
      this.show_dialog = false;
      this.show_dialog2 = false;
      this.isIskolas = false;
      this.$validator.reset();
    },
    save(date) {
      this.$refs.menu.save(date);
    },
    async validateUser() {
      const result = await this.$validator.validate();
      if (result) {
        this.updateUser();
      }
    },
    async validateNewUser() {
      const result = await this.$validator.validate();
      if (result) {
        this.addNewUser();
      }
    },
  },

  mounted() {},
};
</script>
