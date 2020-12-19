<template>
	<div>
		<b-navbar toggleable="lg" type="dark" class="navbar">
			<b-navbar-brand>Papyrus</b-navbar-brand>
		</b-navbar>
		<b-container class="register-container">
			<b-row>
				<b-col>
					<b-card title="Create an Account" class="register-card mx-auto">
						<b-form @submit.prevent="submit" @reset.prevent="reset" >
							<b-form-group id="input-group-2" label="Name:" label-for="input-2">
								<b-form-input id="input-2" v-model="form.name" type="text" required></b-form-input>
							</b-form-group>
							<b-form-group id="input-group-1" label="Email:" label-for="input-1">
								<b-form-input id="input-1" v-model="form.email" type="email" required></b-form-input>
							</b-form-group>
							<b-form-group id="input-group-3" label="Password:" label-for="input-3">
								<b-form-input id="input-3" v-model="form.password" type="password" required></b-form-input>
							</b-form-group>

							<b-button type="submit" variant="primary">Register</b-button>
							<b-button type="reset" variant="danger">Reset</b-button>
							<b-link to="/login" class="float-right pt-2">Login</b-link>
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
  	name: "Register",
  	components: {},
  	data() {
    	return {
      		form: {
				email: "",
				name: "",
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
			this.form.name = '';
			this.form.password = '';
		}
  	},
};

</script>

<style scoped>

.register-container {
	margin-top: 125px;
}
.register-card {
	max-width: 500px;;
}
.navbar {
	background-color: rebeccapurple;
}

</style>
