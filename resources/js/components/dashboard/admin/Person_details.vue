<template>
    <div>
        <section-header :text="person.name" />
        <section class="details-wrapper">
            <div class="pers-details">
                <h3>Összefoglaló</h3>
                <div class="pers-details-item">
                    <p class="pers-details">
                        Beosztás: <span>{{ person.roll }}</span>
                    </p>
                    <p class="pers-details">
                        Email: <span>{{ person.email }}</span>
                    </p>
                    <p class="pers-details">
                        Username: <span>{{ person.username }}</span>
                    </p>
                </div>
            </div>
            <div class="chart">
                <Bar :chart-data="chartData" :chart-options="options" />
            </div>
        </section>
        <section class="data-wrapper">
            <v-container fluid>
                <v-row align-left>
                    <v-col class="d-flex" cols="12" sm="12">
                        <v-select name="table-select" :items="displayDataOptions" item-text="text" item-value="value"
                            v-model="displayData" @change="tableChange" />
                    </v-col>
                </v-row>
            </v-container>
            <v-data-table :headers="headers" :items="items" :search="search" :no-data-text="nodata" :page.sync="page"
                :items-per-page="itemsPerPage" @page-count="pageCount = $event" hide-default-footer class="table">
                <template v-slot:top class="toolbar">
                    <div class="toolbar">
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Keresés" dense hide-details solo
                            single-line class="search"></v-text-field>
                        <v-btn class="text-none new" tile dark @click="showNewItem()">
                            <v-icon dark left>mdi-plus</v-icon>{{ currentSettings.buttonText }}
                        </v-btn>
                    </div>
                    <table-pagination :pageCount='pageCount' :page="page" :itemsPerPage="itemsPerPage"
                        @change-per-page="(perPage) => { itemsPerPage = perPage; page = ((page == null && perPage > 0) ? 1 : page) }"
                        @change-page="(changePage) => page = changePage" />
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon v-if="currentSettings.edit" small color="teal" class="mr-2" @click="editItem(item)">edit
                    </v-icon>
                    <v-icon small color="red" class="mr-2" @click="deleteItem(item)">mdi-delete</v-icon>
                </template>
                <template v-slot:footer>
                    <table-pagination :pageCount='pageCount' :page="page" :itemsPerPage="itemsPerPage"
                        @change-per-page="(perPage) => { itemsPerPage = perPage; page = ((page == null && perPage > 0) ? 1 : page) }"
                        @change-page="(changePage) => page = changePage" />
                </template>
            </v-data-table>
        </section>
        <v-dialog v-model="show_new_work" width="750px">
            <v-card>
                <v-toolbar dense dark color="#292929">
                    <v-toolbar-title>Munkálatok hozzáadása</v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="5">
                                <v-select v-model="newItem.workId" :items="worksToAdd" item-text="name"
                                    item-value="uuid" Label="Munkálatok" filled chips multiple
                                    :error-messages="errors.collect('munkalatok')"></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="red darken-1" outlined @click.native="close">Bezár</v-btn>
                    <v-btn :loading="loading" class="text-none new" tile dark @click.native="validateWork">Hozzáadás
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import { Bar } from 'vue-chartjs/legacy'
import SectionHeader from './compnents/SectionHeader.vue';
import TablePagination from './compnents/TablePagination.vue';
import axios from 'axios';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
export default {
    components: {
        SectionHeader,
        Bar,
        TablePagination
    },
    data() {
        return {
            displayData: "",
            displayDataOptions: [
                {
                    text: "Munkálatok",
                    value: "works",
                    edit: false
                },
                {
                    text: "Munkavégzések",
                    value: "operations",
                    edit: true
                },
                {
                    text: "Munkaeszközök",
                    value: "work_machines",
                    edit: false
                }
            ],
            edit: false,
            headers: [],
            nodata: "Nincsenek adatok",
            items: [],
            newWorks: {},
            worksToAdd: {},
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            show_new_work: false,
            show_up_work: false,
            show_new_operation: false,
            show_up_operation: false,
            show_new_work_machine: false,
            show_up_work_machine: false,
            showedIndex: -1,
            showedItem: {},
            newItem: {},
            tableHeaders: {
                works: [
                    { text: "Név", value: "work_name", },
                    { text: "Akciók", value: "action", align: "right" },
                ],
                work_machines: [
                    { text: "Név", value: "work_machine_name" },
                    { text: "Akciók", value: "action", align: "right" },
                ],
                operations: [
                    { text: "Réteg név", value: "layer_name",},
                    { text: "Munka név", value: "work_name"},
                    { text: "Évszak", value: "season_name", },
                    { text: "Ledolgozott idő", value: "hours_worked",},
                    { text: "Akciók", value: "action", align: "right" },
                ],
            },
            displaySettings: {
                works: {
                    edit: false, buttonText: "Munkálatok hozzáadása", route1: "persons/", route2: "/works/"
                },
                work_machines: {
                    edit: false, buttonText: "Új munkaeszköz", route1: "works/", route2: "/workmachines/"
                },
                operations: {
                    edit: true, buttonText: "Új munka végzés", route1: "persons/", route2: "/operations/"
                }
            },
            currentSettings: {},
            person: {},
            search: "",
            user: {},
            works: [],
            work_machines: [],
            operations: [],
            barLabels: [],
            barData: [],
            chartData: {},
            hoursWorked: 0,
            options: {
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                },
                plugins: {
                    title: {
                        display: true,
                        text: `0 óra munka`
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    created() {
        this.loadItems();
    },
    methods: {
        loadItems: function () {
            axios.get('/api/persons/' + this.$route.params.uuid)
                .then(response => {
                    this.person = response.data;
                    this.user = response.data.user;
                    this.works = response.data.works;
                    this.work_machines = response.data.work_machines;
                    this.operations = response.data.operations;
                    this.BarSetup();
                    this.tableChange('operations');
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('api/persons/' + this.$route.params.uuid + '/works')
                .then(response => {
                    console.log(response.data);
                })
        },
        deleteItem: function (item) {
            console.log(item);
            axios.delete('/api/' + this.displaySettings[this.displayData].route1 + this.person.uuid + this.displaySettings[this.displayData].route2 + item.uuid)
                .then(response => {
                    this.loadItems();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        BarSetup: function () {
            this.barLabels = [];
            this.barData = [];
            this.operations.forEach(operation => {
                let work = this.works.find(work => work.uuid == operation.work_uuid);
                let index = this.barLabels.indexOf(work.work_name);
                console.log(this.barLabels + " " + work.work_name + " " + index);
                if (index == -1) {
                    this.barLabels.push(work.work_name);
                    this.barData.push(operation.hours_worked);
                    this.hoursWorked += operation.hours_worked;
                } else {
                    this.barData[index] += operation.hours_worked;
                    this.hoursWorked += operation.hours_worked;
                }

            });
            console.log(this.barData);
            this.chartData = {
                labels: [...this.barLabels],
                datasets: [
                    {
                        label: '',
                        backgroundColor: '#f87979',
                        data: [...this.barData]
                    }
                ]
            };
            this.options.plugins.title.text = `${this.hoursWorked} óra munka`;
        },
        tableChange: function (val) {
            this.displayData = val;
            this.items = this.displayData == "works" ? this.works : this.displayData == "work_machines" ? this.work_machines : this.operations;
            this.headers = this.tableHeaders[this.displayData];
            this.currentSettings = this.displaySettings[this.displayData];
            this.page = 1;
        },
        changePerPage: function (perPage) {
            this.itemsPerPage = perPage
        },
        changePage: function (page) {
            this.page = page
        },
        setUpNewWork: function () {
            axios.get('/api/persons/' + this.$route.params.uuid + '/works/new')
                .then(response => {
                    this.worksToAdd = response.data;
                    this.show_new_work = true;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        showNewItem: function () {
            this.newItem = {};
            this.show_new_work = this.displayData == "works";
            this.show_new_operation = this.displayData == "operations";
            this.show_new_work_machine = this.displayData == "work_machines";

            if (this.show_new_work) this.setUpNewWork();
        },
        close: function () {
            this.show_new_work = false;
            this.show_new_operation = false;
            this.show_new_work_machine = false;
        },
    }

}
</script>
<style scoped>
.details-wrapper {
    display: flex;
    padding: 10px;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    margin-top: 20px;
    background: rgba(35, 143, 99, 0.1);
    border-radius: 30px;
}

.chart {
    height: 200px;
    width: 70%;
}

.chart>div {
    width: 100%;
    height: 100%;
}
</style>
