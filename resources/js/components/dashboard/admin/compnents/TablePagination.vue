<template>
     <div class="text-center pt-2 paginate-container">
        <div class = "showing">
            <p>{{ (page-1) * itemsPerPage + 1}}. és {{page * itemsPerPage}}. közti találatok</p>
        </div>
            <v-pagination
                :value="page"
                :length="pageCount"
                @input="$emit('change-page', parseInt($event, 10))"
                class="pagination"
            ></v-pagination>
            <v-text-field
                :value="itemsPerPage"
                label="Találatok egy oldalon"
                type="number"
                min="-1"
                max="15"
                @input="$emit('change-per-page',parseInt($event, 10))"
                class="per-page"
            ></v-text-field>
        </div>
</template>
<script>
export default {
    props: ['page', 'pageCount', 'itemsPerPage']
}
</script>
<style>
    .v-pagination>li>button{
        border: none !important;
        box-shadow: none !important;
        background-color: rgba(0,0,0,0) !important;
    }
    .v-pagination>li>.v-pagination__item--active{
        background-color: white !important;
        color: black !important;
    }
    .paginate-container{
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    .showing{
        max-width: 20%;
    }
    .paginate{
        max-width: 50%;
    }
    .per-page{
        max-width: 10%;
    }

</style>
