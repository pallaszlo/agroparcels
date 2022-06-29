<template>
    <div>
        <section-header
            :text="'Feladat: ' + operation.work_name + ' a ' + operation.parcel_name + ' parcella ' + operation.layer_name + ' rétegén'" />
        <div class="op-container">
            <div class="op-texts">
                <div class="op-status">
                    <div class="op-status-text">
                        <h5>
                            <v-icon left>mdi-information-outline</v-icon>Státusz
                        </h5>
                        <p>{{ operation.status_name }}</p>
                    </div>
                </div>
                <div class="op-details">
                    <h5>
                        <v-icon left>mdi-clipboard-text-outline</v-icon>Leírás
                    </h5>
                    <p>{{ operation.description }}</p>
                </div>
                <div class="op-treatment" v-if="operation.treatment_uuid != null">
                    <h5>
                        <v-icon left>mdi-beaker-outline</v-icon>Kezelés
                    </h5>
                    <p>Kultúra: {{ operation.culture_name }}</p>
                    <p>Betegség: {{ operation.disease_name }}</p>
                    <p>Növényvédő: {{ operation.fitoproduct_name }}</p>
                    <p>Felhasznált mennyiség: {{ operation.treatment_quantity }}{{ operation.fitoproduct_sign }}</p>
                </div>
                <div class="op-hours">
                    <h5>
                        <v-icon left>mdi-clock-outline</v-icon>Ledolgozott idő
                    </h5>
                    <p>{{ operation.hours_worked }} óra</p>
                </div>
                <div class="op-season">
                    <h5>
                        <v-icon left>mdi-calendar-text</v-icon>Évszak
                    </h5>
                    <p>{{ operation.season_name }}</p>
                    <p>Időszak: {{ operation.season_from }} és {{ operation.season_to }} között</p>
                </div>
                <div class="controlls">
                    <v-btn class="text-none edit" tile color="#C98907" outlined @click="showHours(operation)">
                        <v-icon dark left>mdi-clock-time-five-outline</v-icon>Óra
                    </v-btn>
                    <v-btn v-if="operation.treatment_uuid != null" class="text-none edit" tile color="#186948" outlined
                        @click="showTreatment(operation)">
                        <v-icon dark left>mdi-beaker-outline</v-icon>Növényvédő
                    </v-btn>
                </div>
            </div>
            <div class="map">
                <layers-map :coordinates="coordinates" :drawActive="false" :editActive="false" />
            </div>
        </div>
        <v-dialog v-model="show_hours_dialog" width="750px">
            <v-card>
                <v-toolbar dark dense color="#145d4c">
                    <v-toolbar-title>Órák követése</v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field v-model="operation_hours.hours" label="Ledolgozott óra" type="number"
                                    min="0" step="1" required></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="red darken-1" outlined dark @click.native="close">Bezár</v-btn>
                    <v-btn :loading="loading" class="text-none new" tile dark color="#145d4c"
                        @click.native="validateHours">Mentés</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="show_treatment_dialog" width="750px">
            <v-card>
                <v-toolbar dark dense color="#145d4c">
                    <v-toolbar-title>Növényvédő követése</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <p>Felhasznált szer: {{ this.operation_treatment.fitoproduct_name }}</p>
                            <p>Betegség: {{ this.operation_treatment.disease_name }}</p>
                            <p>Kultúra: {{ this.operation_treatment.culture_name }}</p>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field v-model="operation_treatment.quantity"
                                    :label="'Felhasznált mennyiség: (' + this.operation_treatment.fitoproduct_sign + ')'"
                                    type="number" min="0" step="1" required></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="red darken-1" outlined dark @click.native="close">Bezár</v-btn>
                    <v-btn :loading="loading" class="text-none new" tile dark color="#145d4c"
                        @click.native="validateTreatment">Mentés</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import SectionHeader from '../admin/compnents/SectionHeader.vue';
import LayersMap from '../admin/compnents/LayersMap.vue';

export default {
    components: {
        SectionHeader,
        LayersMap,
    },
    data() {
        return ({
            uuid: this.$route.params.uuid,
            operation: {},
            coordinates: [],
            show_hours_dialog: false,
            show_treatment_dialog: false,
            operation_hours: {
                uuid: '',
                hours: 0,
            },
            operation_treatment: {
                uuid: '',
                disease_name: '',
                culture_name: '',
                fitoproduct_name: '',
                fitoproduct_sign: '',
            },
        })
    },
    created() {
        this.loadItems();
    },
    methods: {
        loadItems() {
            axios.get('api/operations/' + this.uuid).then(response => {
                this.operation = response.data;
                this.coordinates = [{ type: "Feature", "geometry": JSON.parse(this.operation.layer_coordinates), properties: null, id: this.operation.layer_uuid }];
            }).catch(error => {
                console.log(error);
            });

            axios.get('api/operations/' + this.uuid + '/possible_persons').then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        },
        showHours() {
            this.operation_hours.uuid = this.operation.operation_uuid;
            this.operation_hours.hours = this.operation.hours_worked;
            this.show_hours_dialog = true;
        },
        showTreatment() {
            this.operation_treatment.fitoproduct_name = this.operation.fitoproduct_name;
            this.operation_treatment.fitoproduct_sign = this.operation.fitoproduct_sign;
            this.operation_treatment.disease_name = this.operation.disease_name;
            this.operation_treatment.culture_name = this.operation.culture_name;
            this.operation_treatment.uuid = this.operation.treatment_uuid;
            this.operation_treatment.operation_uuid = this.operation.operation_uuid;
            this.operation_treatment.quantity = this.operation.treatment_quantity;
            this.show_treatment_dialog = true;
        },
        close() {
            this.show_add_dialog = false;
            this.show_hours_dialog = false;
            this.show_treatment_dialog = false;
        },
        validateHours() {
            axios.post('/api/operations/' + this.operation_hours.uuid + '/' + this.$store.state.auth.user.person_uuid, {
                'hours': this.operation_hours.hours
            }).then(response => {
                this.show_hours_dialog = false;
                this.loadItems();
            }).catch(error => {
                console.log(error);
            });
        },
        validateTreatment() {
            axios.put('/api/operations/' + this.operation_treatment.operation_uuid + '/treatment_quantity', {
                'quantity': this.operation_treatment.quantity
            }).then(response => {
                this.show_treatment_dialog = false;
                this.loadItems();
            }).catch(error => {
                console.log(error);
            });
        },
    }
}
</script>
<style scoped>
.op-container {
    display: flex;
    padding: 20px;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    margin-top: 20px;
    background: rgba(35, 143, 99, 0.1);
    border-radius: 30px;
}

@media screen and (max-width: 800px) {
    .op-container {
        flex-direction: column;
    }
}

.map {
    width: 50%;
    height: 100%;
}
</style>
