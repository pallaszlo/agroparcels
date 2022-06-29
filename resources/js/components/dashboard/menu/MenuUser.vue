<template>
    <v-list class='list' rounded>
        <v-list-item @click="$router.push({ name: 'user_dashboard' })">
            <v-list-item-action class='nav-button'>
                <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Feladatok</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
         <v-list-item @click="$router.push({ name: 'user_personal_details' })">
            <v-list-item-action class='nav-button'>
                <v-icon>mdi-account</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Saját adatok</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
    </v-list>
</template>
