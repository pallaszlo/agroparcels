<template>
  <v-card class="mx-auto" outlined width="800px">
    <v-container>
      <v-row>
        <v-col cols="0" lg="6" md="6" sm="12">
          <v-text-field
            name="vnev"
            label="Vezetéknév"
            v-model="gyerek.vnev"
            v-validate="'required'"
            :error-messages="errors.collect('vnev')"
          ></v-text-field>
        </v-col>

        <v-col cols="0" lg="6" md="6" sm="12">
          <v-text-field
            name="knev"
            label="Keresztnév"
            v-model="gyerek.knev"
            v-validate="'required'"
            :error-messages="errors.collect('knev')"
          ></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="0" lg="12" md="12" sm="12">
          <v-combobox
            name="iskola"
            label="Iskola kiválasztás vagy megadás"
            v-model="iskola"
            :items="iskolak"
            v-validate="'required'"
            @change="valami"
            :error-messages="errors.collect('iskola')"
          ></v-combobox>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="0" lg="4" md="4" sm="12">
          <v-text-field
            name="helyseg"
            label="Helység"
            v-model="helyseg"
            v-validate="'required'"
            :error-messages="errors.collect('helyseg')"
          ></v-text-field>
        </v-col>

        <v-col cols="0" lg="4" md="4" sm="12">
          <v-menu
            ref="menu"
            v-model="menu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                name="szuldatum"
                v-model="gyerek.szuldatum"
                label="Születési dátum"
                v-validate="'required'"
                :error-messages="errors.collect('szuldatum')"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              ref="picker"
              v-model="gyerek.szuldatum"
              locale="hu"
              first-day-of-week="1"
              :max="new Date().toISOString().substr(0, 10)"
              min="2008-01-01"
              color="#9E9D24"
              @change="save"
            ></v-date-picker>
          </v-menu>
        </v-col>

        <v-col cols="0" lg="4" md="4" sm="12">
          <v-radio-group
            name="nem"
            v-model="gyerek.nem"
            v-validate="'required'"
            :error-messages="errors.collect('nem')"
            row
          >
            <v-radio dense label="Fiú" value="1"></v-radio>
            <v-radio dense label="Lány" value="0"></v-radio>
          </v-radio-group>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      gyerek: {
        vnev: null,
        knev: null,
        szuldatum: null,
        nem: null,
      },
      iskola: null,
      helyseg: "",
      menu: false,
      iskolak: [],
    };
  },
  methods: {
    async validate() {
      var isValid = true;
      const result = await this.$validator.validate();
      if (!result) {
        isValid = false;
      }
      this.$emit("on-validate", this.$data, isValid);
      return isValid;
    },

    save(date) {
      this.$refs.menu.save(date);
    },

    betoltIskolak: function () {
      axios
        .get("api/iskolak")
        .then((response) => {
          this.iskolak = response.data;
          this.$validator.reset();
        })
        .catch((e) => {
          //this.errors.push(e);
        });
    },
    valami: function () {
      //this.iskola.helyseg = "Tusnad";
      var nev = this.iskola.text;
      if (nev == null) this.helyseg = "";
      else {
        console.log(nev);
        var ind1 = nev.indexOf("(") + 1;
        var ind2 = nev.indexOf(")");
        this.helyseg = nev.substring(ind1, ind2);
      }
    },
  },
  watch: {
    menu(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
  },
  mounted() {
    this.betoltIskolak();
  },
};
</script>
