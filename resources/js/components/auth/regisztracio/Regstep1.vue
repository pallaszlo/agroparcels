<template>
  <v-card class="" outlined width="800px">
    <v-container>
      <v-row>
        <v-col cols="" lg="4" md="6" sm="12">
          <v-text-field
            name="nev"
            label="Név"
            v-model="szulo.nev"
            v-validate="'required'"
            :error-messages="errors.collect('nev')"
          ></v-text-field>
        </v-col>
        <v-col cols="" lg="4" md="6" sm="12">
          <v-text-field
            name="email"
            label="Email cím"
            v-model="szulo.email"
            v-validate="'required|email'"
            :error-messages="errors.collect('email')"
          ></v-text-field>
        </v-col>
        <v-col cols="" lg="4" md="6" sm="12">
          <v-text-field
            name="telefonszam"
            label="Telefonszám"
            v-model="szulo.telefonszam"
            v-validate="'required|numeric'"
            :error-messages="errors.collect('telefonszam')"
          ></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="" lg="4" md="6" sm="12">
          <v-text-field
            name="password"
            label="Jelszó"
            v-model="szulo.password"
            ref="password"
            v-validate="'required|min:6'"
            :error-messages="errors.collect('password')"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show1 ? 'text' : 'password'"
            @click:append="show1 = !show1"
          ></v-text-field>
        </v-col>

        <v-col cols="" lg="4" md="6" sm="12">
          <v-text-field
            name="password_confirm"
            label="Jelszó megerősítés"
            v-model="szulo.password_confirm"
            v-validate="'required|confirmed:password'"
            :error-messages="errors.collect('password_confirm')"
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show2 ? 'text' : 'password'"
            @click:append="show2 = !show2"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      szulo: {
        nev: null,
        email: null,
        telefonszam: null,
        password: null,
        password_confirm: null,
      },
      show1: false,
      show2: false,
    };
  },
  methods: {
    async validate() {
      var isValid = true;
      const result = await this.$validator.validate();
      console.log(result);
      if (!result) {
        console.log("step2");
        isValid = false;
      }
      this.$emit("on-validate", this.$data, isValid);
      return isValid;
    },
  },
};
</script>
