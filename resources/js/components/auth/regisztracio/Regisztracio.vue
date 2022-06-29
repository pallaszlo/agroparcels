<template>
  <v-layout align-center justify-center>
    <v-card class="elevation-12">
      <v-toolbar dense dark color="lime darken-4">
        <v-toolbar-title>Regisztráció</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <form-wizard
          ref="regForm"
          @on-complete="onComplete"
          shape="tab"
          color="#9E9D24"
          title
          subtitle
          :loading="loading"
          back-button-text="Vissza!"
          next-button-text="Tovább!"
          finish-button-text="Regisztrál"
        >

          <tab-content
            title="Szülői adatok"
            icon="ti-user"
            :before-change="() => validateStep('regstep1')"
          >
            <regstep1
              ref="regstep1"
              @on-validate="mergePartialModels"
            ></regstep1>
          </tab-content>

          <tab-content
            title="Iskolás adatok"
            icon="ti-user"
            :before-change="() => validateStep('regstep2')"
          >
            <regstep2
              ref="regstep2"
              @on-validate="mergePartialModels"
            ></regstep2>
          </tab-content>

          <tab-content title="Regisztráció" icon="ti-check">
            <v-card class="mx-auto pa-2" outlined>
              <v-container>
                <v-row>
                  <v-col cols="0" lg="6" md="6" sm="12">
                    <b>Szülői adatok:</b>
                    <ul>
                      <li>Név: {{ finalModel.szulo.nev }}</li>
                      <li>Email cím: {{ finalModel.szulo.email }}</li>
                      <li>Telefonszám: {{ finalModel.szulo.telefonszam }}</li>
                    </ul>
                  </v-col>
                  <v-col cols="0" lg="6" md="6" sm="12">
                    <b>Iskolás adatok:</b>
                    <ul>
                      <li>Vezetéknév: {{ finalModel.gyerek.vnev }}</li>
                      <li>Keresztnév: {{ finalModel.gyerek.knev }}</li>
                      <li v-if="this.finalModel.iskola.value != null">
                        Iskola: {{ finalModel.iskola.text }}
                      </li>
                      <li v-if="this.finalModel.iskola.value == null">
                        Iskola: {{ finalModel.iskola }}
                      </li>
                      <li>Helység: {{ finalModel.helyseg }}</li>
                      <li>
                        Születési dátum: {{ finalModel.gyerek.szuldatum }}
                      </li>
                      <li>
                        Nem: {{ finalModel.gyerek.nem == 1 ? "Fiú" : "Lány" }}
                      </li>
                    </ul>
                  </v-col>
                </v-row>
              </v-container>
              <v-flex xs10 class="mx-auto" v-if="loading">
                <v-progress-circular
                  indeterminate
                  :size="50"
                  :width="8"
                  color="green"
                >
                </v-progress-circular>
              </v-flex>
            </v-card>

            <v-checkbox
              name="feltetel"
              v-model="selected"
              label="Feltételek elfogadása"
              type="checkbox"
              v-validate="'required'"
              :error-messages="errors.collect('feltetel')"
              value="1"
              color="#b2b266"
              required
            >
              <template v-slot:label>
                <div @click.stop>
                  <a href="javascript:;" @click.stop="terms = true"
                    >Feltételek</a
                  >
                  elfogadása
                </div>
              </template>
            </v-checkbox>

            <v-dialog v-model="terms" width="50%">
              <v-card>
                <v-card-title class="title">Feltételek</v-card-title>
                <v-card-text>{{ content }}</v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn text color="#b2b266" @click="terms = false">Ok</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </tab-content>
        </form-wizard>
      </v-card-text>
    </v-card>
  </v-layout>
</template>
<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      finalModel: {
        szulo: {
          nev: null,
          email: null,
          telefonszam: null,
          password: null,
          password_confirm: null,
        },
        gyerek: {
          vnev: null,
          knev: null,
          //iskola: null,
          //helyseg: null,
          szuldatum: null,
          nem: null,
        },
        iskola: {
          text: null,
          value: null,
        },
        helyseg: null,
      },
      //errors: [],
      errorMessage: "",
      validationErrors: "",
      success: "",
      selected: null,
      terms: false,
      loading: false,
      alert: false,
      content:
        "Hozzájárulok ahhoz, hogy a felügyeletem alatt álló fent megnevezett kiskorú gyermekem a KURUTTY RAJZMŰHELY SULIDESI Rajzpályázatára beküldött alkotása a  https://kurutty.ro honlapon, valamint Kurutty Oktatási és Művelődési Társaság által szervezett kiállításokon és kiadványokban a név, életkor, iskolai intézmény és helység megjelölésével nyilvánosságra kerüljön.",
    };
  },
  methods: {
    validateStep(name) {
      var refToValidate = this.$refs[name];
      var t = refToValidate.validate();
      return t;
    },

    mergePartialModels(model, isValid) {
      if (isValid) {
        // merging each step model into the final model
        this.finalModel = Object.assign({}, this.finalModel, model);
      }
    },

    async onComplete() {
      const result = await this.$validator.validate();
      console.log(result);
      if (result) {
        this.sendData();
      }
    },

    sendData: function () {
      let data = new FormData();
      data.append("szulo_nev", this.finalModel.szulo.nev);
      data.append("email", this.finalModel.szulo.email);
      data.append("szulo_telefonszam", this.finalModel.szulo.telefonszam);
      data.append("szulo_password", this.finalModel.szulo.password);
      data.append("gyerek_vnev", this.finalModel.gyerek.vnev);
      data.append("gyerek_knev", this.finalModel.gyerek.knev);
      if (this.finalModel.iskola.value == null) {
        data.append("iskola_id", -1);
        data.append("iskola_nev", this.finalModel.iskola);
      } else {
        data.append("iskola_id", this.finalModel.iskola.value);
      }
      data.append("helyseg", this.finalModel.helyseg);
      data.append("gyerek_szuldatum", this.finalModel.gyerek.szuldatum);
      data.append("gyerek_nem", this.finalModel.gyerek.nem);

      this.loading = true;
      axios
        .post("api/register", data)
        .then((response) => {
          console.log(response.data);
          this.$store.commit("snackbar/setSnack", response.data);
          this.resetForm();
          this.$router.push({ name: "login" });
        })
        .catch((e) => {
          this.$store.commit("snackbar/setSnack", e.response.data);
          //this.resetForm();
        })
        .then(() => {
          this.loading = false;
        });
    },
    resetForm: function () {
      this.$refs.regForm.navigateToTab(0);
      this.$refs.regForm.reset();
      this.finalModel.szulo.nev = null;
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
