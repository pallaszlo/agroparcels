<template>
	<v-layout align-center justify-center>
  		<v-flex sm7 md5 lg3>
    		<transition name="fade" mode="out-in">
      			<router-view></router-view>
    		</transition>
  		</v-flex>
  	</v-layout>
</template>

<script>
	export default {
  }
</script>
