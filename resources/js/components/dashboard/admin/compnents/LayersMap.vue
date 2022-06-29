<template>
    <div>
        <vl-map data-projection="EPSG:4326" style="height: 600px">
            <vl-view :zoom.sync="zoom" :center.sync="center" :rotation.sync="rotation"></vl-view>

            <vl-layer-tile>
                <vl-source-bingmaps api-key="AgKRryXiwPai9CAt_nBwTnZp50UoEgge2wtQ5l9qIzxgPRXm-MZTtoM3nXXZQaZK"
                    imagery-set="AerialWithLabelsOnDemand"></vl-source-bingmaps>
            </vl-layer-tile>

            <vl-feature>
                <!-- <vl-geom-polygon :coordinates="coordinates"></vl-geom-polygon> -->

            </vl-feature>
            <VlLayerVector id="draw-pane" :visible="layerActive">
                <VlSourceVector v-bind:ident="'draw-target-' + name" :features.sync="data" />
            </VlLayerVector>
            <VlInteractionDraw :active="active" v-bind:source="'draw-target-' + name" :type="drawType" />
            <VlInteractionModify :active="editActive" v-bind:source="'draw-target-' + name" />
        </vl-map>
        <v-btn v-if="resetBtn" @click="reset">Reset</v-btn>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props: ['name', 'coordinates', 'drawActive', 'editActive', 'resetBtn'],
    data() {
        return {
            //coordinates: [],
            data: [],
            zoom: 16,
            center: [25.8825874, 45.8858261],
            rotation: 0,
            drawType: 'Polygon',
            active: false,
            layerActive: true,
        };
    },
    created() {
        this.data = this.coordinates;
        this.active = this.drawActive;

    },
    methods: {
        reset: function () {
            this.data = [];
            this.active = true;
        },
    },
    watch: {
        coordinates: function (newVal, oldVal) {
            this.data = newVal;

            this.center = this.data[0].geometry.coordinates[0][0];

        },
        'selected': function (newVal, oldVal) {
            this.coordinates = [{ type: "Feature", "geometry": JSON.parse(newVal[0].json), properties: null, id: newVal.uuid }];
            console.log(this.coordinates.type)
            // this.center = this.coordinates[0][0];
        },
        data: function (newVal, oldVal) {
            if (this.active == true && newVal.length >= 1) {
                this.active = false;
            }
            this.$emit('update:coordinates', newVal);
        },
    }
};
</script>
<style>
</style>
