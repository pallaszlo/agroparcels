<template>
	<v-app>
		<Snackbar />
      <router-view></router-view>
  	</v-app>
</template>
<script>
  import Snackbar from "./Snackbar";

  export default {
    components: { Snackbar }
  };
</script>
