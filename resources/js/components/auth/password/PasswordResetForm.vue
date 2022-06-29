<template>
  <v-form ref="form" @submit.prevent="submit" lazy-validation>
    <v-text-field
      name="password"
      label="Jelszó"
      v-model="form.password"
      ref="password"
      v-validate="'required|min:6'"
      :error-messages="errors.collect('password')"
      :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
      :type="show1 ? 'text' : 'password'"
      @click:append="show1 = !show1"
    ></v-text-field>

    <v-text-field
      name="password_confirmation"
      label="Jelszó megerősítés"
      v-model="form.password_confirmation"
      v-validate="'required|confirmed:password'"
      :error-messages="errors.collect('password_confirmation')"
      :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
      :type="show2 ? 'text' : 'password'"
      @click:append="show2 = !show2"
    ></v-text-field>

    <v-layout row class="mt-2 mr-2">
      <v-spacer></v-spacer>

      <v-btn @click="validate" :loading="loading" color="lime darken-4" dark>
        Küldés
      </v-btn>
    </v-layout>
  </v-form>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data: () => ({
    form: {
      token: null,
      email: null,
      password: null,
      password_confirmation: null,
    },
    show1: false,
    show2: false,
    loading: false,
  }),

  created() {
    this.form.email = this.$route.query.email;
    this.form.token = this.$route.params.token;
  },

  methods: {
    submit: function () {
      this.loading = true;
      axios
        .post("api/password/reset", this.form)
        .then((res) => {
          this.$store.commit("snackbar/setSnack", res.data);
          this.$emit("success", this.form);
        })
        .catch((e) => {
          this.$store.commit("snackbar/setSnack", e.response.data);
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
