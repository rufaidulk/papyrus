<template>
	<div>
		<b-navbar toggleable="lg" type="dark" class="navbar">
			<b-navbar-brand>Papyrus</b-navbar-brand>
		</b-navbar>
		<b-container class="login-container">
			<b-row>
				<b-col>
					<b-card title="Login" class="login-card mx-auto">
						<b-form @submit.prevent="submit" @reset.prevent="reset" >
							<b-form-group id="input-group-1" label="Email:" label-for="input-1">
								<b-form-input id="input-1" v-model="form.email" type="email" required></b-form-input>
							</b-form-group>
							<b-form-group id="input-group-3" label="Password:" label-for="input-3">
								<b-form-input id="input-3" v-model="form.password" type="password" required></b-form-input>
							</b-form-group>

							<b-button type="submit" variant="primary">Login</b-button>
							<b-button type="reset" variant="danger">Reset</b-button>
							<b-link to="/register" class="float-right pt-2">Register</b-link>
						</b-form>
					</b-card>
				</b-col>
			</b-row>
		</b-container>
	</div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  	name: "Login",
  	components: {},
  	data() {
    	return {
      		form: {
				email: "",
				password: "",
      		}
    	};
  	},
  	methods: {
		...mapActions(['login']),
		async submit() {
			console.log(this.form.email);
			const user = {
				name: 'thomas',
				accessToken: 'sldfjowe23400890dusdlfjsa'
			};
			await this.login(user);
			this.$router.push({name: 'dashboard'});
		},
		reset() {
			this.form.email = '';
			this.form.password = '';
		}
  	},
};

</script>

<style scoped>

.login-container {
	margin-top: 125px;
}
.login-card {
	max-width: 500px;;
}
.navbar {
	background-color: #563d7c;
}

</style>
