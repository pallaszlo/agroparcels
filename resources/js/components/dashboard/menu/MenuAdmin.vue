<template>
    <v-list class = 'list' rounded>
        <!-- <v-list-item
          @click="$router.push({ name: 'admin_dashboard' })">
          <v-list-item-action class = 'nav-button'>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>

          <v-list-item-content>
            <v-list-item-title>Vezérlőpult</v-list-item-title>
          </v-list-item-content>
        </v-list-item> -->
        <v-list-item @click="$router.push({name:'persons'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-account-settings</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Felhasználók</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item
        v-if="role == 'super-admin'"
        @click="$router.push({name:'companies'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-domain</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Cégek</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'groups'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-account-multiple</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Csoportok</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'parcels'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-map</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Parcellák</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'layers'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Rétegek</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'works'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-hammer-wrench</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Munkálatok</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'treatments'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-sprinkler-fire</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Kezelések</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'operations'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-tire</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Munkavégzések</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'cultures'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-sprout</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Kultúrák</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'fitoproducts'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-beaker-outline</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Növényvédők</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({name:'diseases'})"  class = 'nav-button'>
            <v-list-item-action>
                <v-icon>mdi-virus-outline</v-icon>
            </v-list-item-action>

            <v-list-item-content>
                <v-list-item-title>Betegségek</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
    </v-list>
</template>
<script>

export default{
    data() {
        return {
            role: null,
        }
    },
    created() {
        this.role = this.$store.state.auth.role;
    },
}
</script>

