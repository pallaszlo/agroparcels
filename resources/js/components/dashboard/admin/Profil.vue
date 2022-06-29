<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
            <v-card>
                <v-toolbar dark dense color="#827717" flat>
                    <v-toolbar-title>Jelszó változtatás</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            name="oldpassword"
                            label="Régi jelszó"
                            v-model="oldpassword"
                            ref="oldpassword"
                            v-validate="'required|min:6'"
                            :error-messages="errors.collect('oldpassword')"
                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show1 ? 'text' : 'password'"
                            @click:append="show1 = !show1"
                        ></v-text-field>
                        <v-text-field
                            name="password"
                            label="Új jelszó"
                            v-model="password"
                            ref="password"
                            v-validate="'required|min:6'"
                            :error-messages="errors.collect('password')"
                            :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show3 ? 'text' : 'password'"
                            @click:append="show3 = !show3"
                        ></v-text-field>

                        <v-text-field
                            name="password_confirm"
                            label="Új jelszó megerősítése"
                            v-model="password_confirm"
                            v-validate="'required|confirmed:password'"
                            :error-messages="errors.collect('password_confirm')"
                            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show2 ? 'text' : 'password'"
                            @click:append="show2 = !show2"
                        ></v-text-field>

                        <v-layout row class="mr-2 mt-2">
                            <v-spacer></v-spacer>

                            <v-btn dark :loading="loading" color="#827717" @click="validate"
                            >Elküld
                            </v-btn
                            >
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
import axios from "axios";
import {mapActions} from "vuex";

export default {
    data() {
        return {
            oldpassword: null,
            password: null,
            password_confirm: null,
            show1: false,
            show2: false,
            show3: false,
            loading: false,
        };
    },
    methods: {
        changePassword: function () {
            this.loading = true;
            axios
                .post("api/password/update", {
                    oldpassword: this.oldpassword,
                    password: this.password,
                    password_confirm: this.password_confirm,
                })
                .then((response) => {
                    this.loading = false;
                    this.$store.commit("snackbar/setSnack", response.data);
                    this.$router.push({name: "dashboard"});
                    this.resetForm();
                })
                .catch((e) => {
                    this.$store.commit("snackbar/setSnack", e.response.data);
                    this.loading = false;
                });
        },
        async validate() {
            const result = await this.$validator.validate();
            if (result) {
                this.changePassword();
            }
        },
    },
};
</script>
