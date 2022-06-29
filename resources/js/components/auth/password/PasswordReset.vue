<template>
  <v-card raised>
    <v-toolbar dark dense color="lime darken-4" flat>
      <v-toolbar-title>Jelszó változtatás</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <div class="fullwidth">
        <p>Új jelszó megadása</p>

        <password-reset-form @success="success"></password-reset-form>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
  import PasswordResetForm from './PasswordResetForm'

	export default {
		components: {
			PasswordResetForm
		},

		methods: {
			success(form) {
				this.$router.push({ name: 'login', query: {email: form.email} })
			}
		}
	}
</script>
