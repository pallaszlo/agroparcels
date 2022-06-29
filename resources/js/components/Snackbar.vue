<template>
  <v-snackbar v-model="show" :top="top" :color="color">
    {{message}}
    <template v-slot:action="{ attrs }">
      <v-btn text color="white" v-bind="attrs" @click.native="show = false">
        <v-icon>close</v-icon>
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      top: true,
      message: "",
      color: "",
      timeout: 5000
    };
  },
  created: function() {
    this.$store.watch(
      state => state.snackbar.snack,
      () => {
        const message = this.$store.state.snackbar.snack.message;
        const status = this.$store.state.snackbar.snack.status;
        if (message) {
          this.show = true;
          this.message = message;
          if (status == "success")
            //this.color = this.$store.state.snackbar.snack.color;
            this.color = "success";
          else this.color = "error";
          this.$store.commit("snackbar/setSnack", {});
        }
      }
    );
  }
};
</script>