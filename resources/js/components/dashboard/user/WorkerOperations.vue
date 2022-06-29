<template>
    <div>
        <section-header text="Feladatok" />
        <div class="states_container">
            <v-row>
                <v-col cols="4" md="4" v-for="(row, i) in statuses" :key="'row' + i " class="row">
                    <h4>{{row.name}}</h4>
                    <div v-on:drop="drop($event)" v-on:dragover="allowDrop" class="draggable-list" tag="div"
                        :id='row.id' :list="row.operations" :group="{ name: 'operations', pull: 'clone', put: true }"
                        :stage-id="row.id">
                        <div v-for="(operation) in row.operations" draggable="true" v-on:dragstart="drag" ondrop=""
                            ondragover="" :key="operation.uuid" :id="operation.uuid" class="op-card">
                            <div class="op-card-header">
                                <h5>{{operation.work_name}} a {{operation.layer_name}} parcellán</h5>
                            </div>
                            <div class="op-card-body">
                                <div class="op-card-body-text">
                                    <span>{{operation.description}}</span>
                                </div>
                                <div class="op-card-body-text">
                                    <p>{{operation.persons}} személy {{operation.worked_hours}} óra munka</p>
                                </div>
                            </div>
                            <div class="op-card-footer">
                                <v-btn class="text-none edit" tile color="#f0ab1f" outlined
                                    @click="showHours(operation)">
                                    <v-icon dark left>mdi-clock-time-five-outline</v-icon>Óra
                                </v-btn>
                                <v-btn v-if="operation.treatment_uuid != null" class="text-none edit" tile
                                    color="#186948" outlined @click="showTreatment(operation)">
                                    <v-icon dark left>mdi-beaker-outline</v-icon>Növényvédő
                                </v-btn>
                                <v-btn class="text-none edit" tile outlined @click="toDetails(operation.uuid)">
                                    <v-icon dark left>mdi-expand-all-outline</v-icon>Részletek
                                </v-btn>

                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
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
                            <p>Felhasznált szer: {{this.operation_treatment.fitoproduct_name}}</p>
                            <p>Betegség: {{this.operation_treatment.disease_name}}</p>
                            <p>Kultúra: {{this.operation_treatment.culture_name}}</p>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field v-model="operation_treatment.quantity"
                                    :label="'Felhasznált mennyiség: (' + this.operation_treatment.fitoproduct_sign +')'"
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
    import axios from 'axios';
    import SectionHeader from '../admin/compnents/SectionHeader.vue';
    import draggable from 'vuedraggable';
    export default {
        components: {
            SectionHeader,
            draggable
        },
        data(){
            return{
                operations: [],
                statuses: [],
                show_add_dialog: false,
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
                newItem: [],
                new_treatments: [],
                new_works: [],
                new_statuses: [],
                new_seasons: [],
                new_layers: [],
                loading: false,
            }
        },
        created(){
            this.loadItems();
        },
        methods:{
            loadItems(){
                axios.get('/api/operations').then(response => {
                    this.operations = response.data.operations;
                    this.statuses = response.data.statuses;

                   //categorize operations into statuses if operations.status_id = status.id
                    this.operations.forEach(operation => {
                        this.statuses.find(status => {
                            if(status.id == operation.status_id){
                                if(status.operations == undefined){
                                    status.operations = [];
                                }
                                status.operations.push(operation);
                            }
                        });
                    })
                }).catch(error => {
                    console.log(error);
                });
            },
            handleChange(event){
                console.log(event);
            },
            onEnd(event){
                //console.log(event);
            },
            allowDrop(event){
                //console.log(event);
                event.preventDefault();
            },
            drag(event){
                event.dataTransfer.setData("text", event.target.id);
            },
            drop(event){

                let status = event.target.closest('.draggable-list').getAttribute('stage-id');
                let operation = event.dataTransfer.getData("text");
                let op = this.operations.find(opr => {
                    return opr.uuid == operation;
                });

                if(status != op.status_id){
                    this.statuses[op.status_id-1].operations.splice(this.statuses[op.status_id-1].operations.indexOf(op), 1);
                    op.status_id = status;
                    if(this.statuses[status-1].operations == undefined){
                        this.statuses[status-1].operations = [];
                    }
                    this.statuses[status-1].operations.push(op);

                    this.$forceUpdate();
                    axios.post('/api/operations/' + operation + '/status/' + status).then(response => {

                    }).catch(error => {
                        console.log(error);
                    })
                }

            },
            showNewItem(){
                this.show_add_dialog = true;
            },
            showHours(operation){
                this.operation_hours.uuid = operation.uuid;
                this.operation_hours.hours = operation.person_worked;
                this.show_hours_dialog = true;
            },
            showTreatment(operation){
                axios.get('/api/treatments/' + operation.treatment_uuid).then(response => {
                    this.operation_treatment.fitoproduct_name = response.data.fitoproduct_name;
                    this.operation_treatment.fitoproduct_sign = response.data.fitoproduct_sign;
                    this.operation_treatment.disease_name = response.data.disease_name;
                    this.operation_treatment.culture_name = response.data.culture_name;
                    this.operation_treatment.uuid = operation.treatment_uuid;
                    this.operation_treatment.operation_uuid = operation.uuid;
                    this.operation_treatment.quantity = operation.treatment_quantity;
                    this.show_treatment_dialog = true;
                }).catch(error => {
                    console.log(error);
                });
            },
            close(){
                this.show_add_dialog = false;
                this.show_hours_dialog = false;
                this.show_treatment_dialog = false;
            },
            validateHours(){
                axios.post('/api/operations/' + this.operation_hours.uuid + '/'+ this.$store.state.auth.user.person_uuid, {
                    'hours' : this.operation_hours.hours
                }).then(response => {
                    this.show_hours_dialog = false;
                    this.loadItems();
                }).catch(error => {
                    console.log(error);
                });
            },
            validateTreatment(){
                axios.put('/api/operations/' + this.operation_treatment.operation_uuid + '/treatment_quantity', {
                    'quantity' : this.operation_treatment.quantity
                }).then(response => {
                    this.show_treatment_dialog = false;
                    this.loadItems();
                }).catch(error => {
                    console.log(error);
                });
            },
            toDetails(uuid){
                this.$router.push('/dashboard/user_dashboard/' + uuid);
            },
        }

    }
</script>
<style scoped>
    .containers-wrapper{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .draggable-list{
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        height: 100%;
    }
    .op-card{
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        background-color: #fff;
        border-radius: 5px;
        padding: 10px 20px;
        margin-bottom: 20px;
    }
</style>
