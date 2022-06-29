<template>
  <div>
    <v-navigation-drawer v-model="drawer" app dark class="green-gr">
      <v-app-bar color="#292929" dark>
        <v-toolbar-title class='title'>Agroparcels</v-toolbar-title>
      </v-app-bar>
        <menu-admin v-if="role == 'admin' || role == 'super-admin'"></menu-admin>
        <menu-user v-if="role == 'user'"></menu-user>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn block dark color="#292929" @click="handleLogout">
            Kijelentkezés
          </v-btn>
        </div>
        <p class="copy">©Székely M.-Zsolt 2022</p>
      </template>
    </v-navigation-drawer>

    <v-app-bar app color="#292929" dark>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <div class="flex-grow-1"></div>
      <div class="mr-2">{{ username }}</div>
      <v-menu
        offset-y
        origin="center center"
        :nudge-bottom="0"
        transition="scale-transition"
      >
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon>mdi-menu-down</v-icon>
          </v-btn>
        </template>
        <v-list-item-group>
          <v-list class="pa-0" dense>
            <v-list-item v-on:click="handleProfile">
              <v-list-item-icon>
                <v-icon>mdi-account</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Profil</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-item v-on:click="handleLogout">
              <v-list-item-icon>
                <v-icon>mdi-logout</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Kilépés</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-list-item-group>
      </v-menu>
    </v-app-bar>

    <v-main class="mainheight">
      <v-container fluid text-xs-center>
        <router-view></router-view>
      </v-container>
    </v-main>
  </div>
</template>
<script>
import MenuAdmin from "./menu/MenuAdmin";
import MenuUser from "./menu/MenuUser";
import { mapActions } from "vuex";

export default {
  data: () => ({
    drawer: null,
    clipped: false,
    username: null,
    role: null,
    items: [
      {
        icon: "account_circle",
        href: "#",
        title: "Profil",
        //click: this.handleProfile
      },
      {
        icon: "fullscreen_exit",
        href: "#",
        title: "Logout",
        //click: this.handleLogut
      },
    ],
  }),
  components: {
    MenuAdmin,
    MenuUser,
  },
  created() {
    this.$vuetify.theme.dark = false;
    if (this.$store.state.auth.user.iskolas == null) {
      this.username = this.$store.state.auth.user.name;
    } else {
      this.username = this.$store.state.auth.user.iskolas;
    }
    this.role = this.$store.state.auth.role;
  },
  methods: {
    handleProfile() {
      this.$router.push({ name: "profil" });
    },
    handleLogout() {
      axios
        .get("api/logout")
        .then((response) => {
          //this.$store.commit("auth/setAuthentication", false);
          this.$store.commit("auth/clearUserData");
          //this.$store.dispatch('logout');
          this.$router.push({ name: "home" });
        })
        .catch((error) => {
          console.log("Logout - error");
          //console.log(error);
          //location.reload();
          //this.$router.push({ name: "login" });
          //if (error.response.status === 401) {
          //    this.$router.replace({name: 'login'})
          //}
        });
    },
  },
};
</script>
<style scoped>
    .mainheight{
        min-height: 100vh;
        background-color: #E1E1E1;
    }
    .container {
        max-width: 1540px !important;
    }
    .green-gr{
        background: linear-gradient(274.35deg, #186948 3.26%, #145D4C 96.22%);
    }
    .list > *{
        margin: .25em .5em;
        padding: .25em;
        border-radius: 6px;
    }
    .list > *:hover {
        background: linear-gradient(89.18deg, #238F63 0.72%, #145D4C 168.8%);
    }
    .title{
        font-family: 'Roboto', sans-serif;
        font-size: 30px !important;
        font-weight: 700;

    }
    .copy{
        color: white;
        font-size: 12px;
        font-weight: 400;
        margin-top: 10px;
        margin: auto;
        text-align: center;
    }
</style>
