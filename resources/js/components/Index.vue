<template>
        <login></login>
</template>

<script>
import login from "./auth/login/Login.vue";
export default {
  components: {
    login,
  },

  data() {
    return {
      menu: [
        { icon: "home", title: "Link A" },
        { icon: "info", title: "Link B" },
        { icon: "warning", title: "Link C" },
      ],
    };
  },
  mounted() {
    //this.$router.push({name:'home'})
  },
};
</script>

<style scoped>
.mainheight {
  min-height: 100vh;
}
</style>
