<template>
  <v-app>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <div class="text-md-center">
            <h1>404</h1>
            <h2 class="my-3 headline">A keresett oldal nem található</h2>
            <div>
              <v-btn color="primary" @click="goBack">Vissza!</v-btn>
              <v-btn color="primary" @click="goHome">Főoldal</v-btn>
            </div>
          </div>
        </v-layout>
      </v-container>
  </v-app>
</template>

<script>
export default {
  methods: {
    goBack () {
      this.$router.go(-1);
    },
    goHome () {

      this.$router.push({path: '/'})
    }
  }
}
</script>
<style scoped>
  h1 {
    font-size: 150px;
    line-height: 150px;
    font-weight: 700;
    color: #252932;
    text-shadow: rgba(61, 61, 61, 0.3) 1px 1px, rgba(61, 61, 61, 0.2) 2px 2px, rgba(61, 61, 61, 0.3) 3px 3px;
  }
</style>
