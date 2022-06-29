<template>
  <div>
    <v-form ref="form" @submit.prevent="submit" lazy-validation>
      <v-alert
        dense
        outlined
        dismissible
        type="error"
        v-model="alert"
        v-if="errorMessage"
      >
        {{ errorMessage }}
      </v-alert>
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
      timeout: 5000,
      top: true,
      errorMessage: "",
      alert: false,
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
            this.$store.commit("snackbar/setSnack", "Link sikeresen elküldve!");
        })
        .catch((e) => {
          this.alert = true;
          this.errorMessage = "Az email cím nem található";
          //this.$store.commit("snackbar/setSnack", "Az email cím nem található");
          console.log(e);
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
