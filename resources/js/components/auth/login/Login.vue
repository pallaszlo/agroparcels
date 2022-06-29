<template>
  <div class="login-container">
      <div class="form-wrapper">
          <h1>Agroparcels</h1>
          <div class="form">
            <v-form>
            <v-text-field class="input"
              prepend-icon="email"
              name="email"
              v-model="email"
              large
              label="Email cím"
              id="email"
              v-validate="'required|email'"
              :error-messages="errors.collect('email')"
              type="text"
              >></v-text-field
            >
            <v-text-field
              class="input"
              prepend-icon="lock"
              large = "true"
              name="password"
              v-model="password"
              label="Jelszó"
              id="password"
              v-validate="'required'"
              :error-messages="errors.collect('password')"
              type="password"
              ></v-text-field
            >
            <!-- <v-btn
            text
            :to="{ name: 'forgot', query: { email: email } }"
            color="grey darken-2"
            >Elfelejtett jelszó?</v-btn
            > -->

              <v-btn class="login" color="#F0AB1F" :loading="loading" @click="validate"
                >Belépés</v-btn
              >
              <p>Elfelejtett-e jelszavát?</p>
              <v-btn
                text
                :to="{ name: 'forgot', query: { email: email } }"
                color="grey darken-2"
                >Jelszó visszaállítása</v-btn
            >
          </v-form>
          </div>

      </div>
    <div class="image">
    </div>

  </div>
</template>
<script>
import axios from "axios";
import { mapActions } from "vuex";
export default {
  data() {
    return {
      email: null,
      password: null,
      megjegyzes: null,
      loading: false,
      errorMessage: "",
      alert: false,
    };
  },
  created() {
    this.email = this.$route.query.email || null;
  },
  methods: {
    login: function () {
      console.log("login");
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("api/login", {
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            //this.$store.commit("snackbar/setSnack", response.data);
            this.$store.commit("auth/setUser", response.data.user);
            //this.$store.commit("auth/setRole", response.data.user.roles[0]);
            //this.$store.commit("auth/setToken", response.data.access_token);
            //this.$store.commit("auth/setAuthentication", true);
            //this.$router.push({ name: "dashboard" });

            var role = response.data.user.roles[0];
            if (role == "super-admin") {
                this.$router.push({ name: "operations" });
            }
            else if(role == "admin"){
                this.$router.push({ name: "operations" });
            }
             else if(role == "user"){
                this.$router.push({ name: "user_dashboard" });
             }

          })
          .catch((e) => {
            this.$store.commit("snackbar/setSnack", e.response.data);
          })
          .then(() => {
            this.loading = false;
          });
      });
    },

    async validate() {
      const result = await this.$validator.validate();
      if (result) {
        this.login();
      }
    },
  },
};
</script>
<style scoped>
    .login-container {
        display: flex;
        flex-direction: row;
        height: 100vh;
        width: 100vw;
    }
    .form-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        flex-basis: 1.2;
        width: 100%;
        height: 100vh;
        background: linear-gradient(90deg, #145D4C 0%, #186948 100%);
        color: #BDD1DA;
    }
    .form-wrapper h1{
        font-family: 'Roboto';
        font-style: normal;
        text-transform: uppercase;
        font-weight: 700;
        font-size: 59px;
        line-height: 69px;
        letter-spacing: 0.11em;
        color: #BDD1DA;
    }

    .form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        padding: 29px 0;
        width: 80%;
        border-radius: 39px;
        color: black;
        text-align: center;
    }
    .login{
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 500;
        margin: auto;
        line-height: 50px;
        width: 100%;
        background-color: #F0AB1F;
        color: #FFFEFC;
    }
    .image{
        width: 100%;
        height: 100%;
        flex-basis: 0.8;
        background-image: url("/assets/login.png");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;

    }
</style>
