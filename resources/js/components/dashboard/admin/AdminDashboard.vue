<template>
  <div>

    <h4>Kedves {{ username }}, {{ role }}, {{ fullname }}</h4>
    <p>Köszöntünk a SULIDESI online rajzküldő felületén</p>
    <v-divider></v-divider>

    <br />
    <h4 class="text-center">
      Üdvözöljük a Kurutty, SULIDESI rajzpályázat felületén
    </h4>
    <v-row justify="center">
      <v-img max-width="100" :src="getURL('/storage/doksi/logo.png')"> </v-img>
    </v-row>

    <v-divider></v-divider>

    <h5>Milyen lehetőségeket kinál a felület?</h5>
    <ul>
      <li>
        Esemény létrehozása, szerkesztése (1.Forduló, 2.Forduló)
        <v-icon>mdi-arrow-right</v-icon>
        <v-btn
          x-small
          dark
          outlined
          color="#37474F"
          @click="$router.push({ name: 'esemenyek' })"
          >Események</v-btn
        >
      </li>
      <li>
        Pályázatok megtekintése, jóváhagyása <v-icon>mdi-arrow-right</v-icon>
        <v-btn
          x-small
          dark
          outlined
          color="#37474F"
          @click="$router.push({ name: 'palyazatok' })"
          >Pályázatok</v-btn
        >
      </li>
      <li>
        Bírálók meghívása <v-icon>mdi-arrow-right</v-icon>
        <v-btn
          x-small
          dark
          outlined
          color="#37474F"
          @click="$router.push({ name: 'biralok' })"
          >Bírálók</v-btn
        >
      </li>
      <li>
        Eredmények megtekintése, engedélyezése <v-icon>mdi-arrow-right</v-icon>
        <v-btn
          x-small
          dark
          outlined
          color="#37474F"
          @click="$router.push({ name: 'eredmenyek' })"
          >Eredmények</v-btn
        >
      </li>
      <li>
        Jelszó változtatás <v-icon>mdi-arrow-right</v-icon>
        <v-btn
          x-small
          dark
          outlined
          color="#37474F"
          @click="$router.push({ name: 'profil' })"
          >Profil</v-btn
        >
      </li>
    </ul>
  </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      username: null,
      role: null,
      fullname: null,
    };
  },

  created() {
    this.username = this.$store.state.auth.user.name;
    this.role = this.$store.state.auth.role;
    this.fullname = this.$store.state.auth.user.name_full;
  },
  methods: {
    getURL(doksi) {
      return this.$baseURL + doksi;
    },
    startCallBack: function (x) {
      console.log(x);
    },
    endCallBack: function (x) {
      console.log(x);
    },
  },
};
</script>
<style scoped>
h4,
h5 {
  color: #78909c;
}
</style>
