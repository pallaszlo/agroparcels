<template>
  <v-layout align-center justify-center>
    <v-card>
      <v-toolbar dark dense color="lime darken-4" flat>
        <v-toolbar-title>Jelszó változtatás</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <div class="fullwidth">
          <p>
            Az alább megadott email címre küldött linken keresztül lehet az új
            jelszót beállítani.
          </p>
          <v-form ref="form" @submit.prevent="submit" lazy-validation>
            <v-text-field
              name="email"
              v-model="form.email"
              label="Email cím"
              id="email"
              v-validate="'required|email'"
              :error-messages="errors.collect('email')"
              :disabled="loading"
              type="email"
            ></v-text-field>

            <v-layout row class="mt-2 mr-2">
              <v-spacer></v-spacer>

              <v-btn
                text
                :disabled="loading"
                :to="{ name: 'login', query: { email: form.email } }"
                color="grey darken-2"
                exact
              >
                Mégse
              </v-btn>

              <v-btn dark @click="validate" :loading="loading" color="lime darken-4">
                Küldés
              </v-btn>
            </v-layout>
          </v-form>
        </div>
      </v-card-text>
    </v-card>
  </v-layout>
</template>

<script>
import { mapActions } from "vuex";

export default {
  data() {
    return {
      form: {
        email: null,
      },
      loading: false,
    };
  },
  created() {
    this.form.email = this.$route.query.email || null;
  },
  methods: {
    submit: function () {
      this.loading = true;
      axios
        .post("api/password/email", this.form)
        .then((res) => {
          this.$store.commit("snackbar/setSnack", res.data);
          this.$router.push({ name: "login" });
        })
        .catch((e) => {
          this.$store.commit("snackbar/setSnack", e.response.data);
          this.form.email = "";
          this.$validator.reset();

          //console.log(e);
        })
        .then(() => {
          this.loading = false;
        });
    },
    async validate() {
      const result = await this.$validator.validate();
      if (result) {
        this.submit();
      }
    },
  },
};
</script>
