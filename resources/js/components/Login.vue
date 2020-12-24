<template>
	<div>
		<b-navbar toggleable="lg" type="dark" class="navbar">
			<b-navbar-brand>{{ appName }}</b-navbar-brand>
		</b-navbar>
		<b-container class="login-container">
			<b-row>
				<b-col>
					<b-card title="Login" class="login-card mx-auto">
						<b-alert v-model="showErrorMsg" variant="danger" dismissible>{{ error }}</b-alert>
						<validation-observer ref="observer" v-slot="{ handleSubmit }">
							<b-form @submit.prevent="handleSubmit(submit)" @reset.prevent="reset" >
								<validation-provider name="Email" :rules="{ required: true, email: true }" v-slot="validationContext">
									<b-form-group id="input-group-1" label="Email:" label-for="input-1">
										<b-form-input id="input-1" v-model="form.email" type="email" :state="getValidationState(validationContext)"></b-form-input>
										<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
									</b-form-group>
								</validation-provider>

								<validation-provider name="Password" :rules="{ required: true }" v-slot="validationContext">
									<b-form-group id="input-group-3" label="Password:" label-for="input-3">
										<b-form-input id="input-3" v-model="form.password" type="password" :state="getValidationState(validationContext)"></b-form-input>
										<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
									</b-form-group>
								</validation-provider>

								<b-button type="submit" variant="primary">Login</b-button>
								<b-button type="reset" variant="danger">Reset</b-button>
								<b-link to="/register" class="float-right pt-2">Register</b-link>
							</b-form>
						</validation-observer>
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
  	data() {
    	return {
			appName: 'sdfs',
      		form: {
				email: "",
				password: "",
      		},
			showErrorMsg: false,
			error: ''
    	};
	},
	created () {
		this.appName = this.$appName;
	},
  	methods: {
		...mapActions(['login']),
		async submit () {
			this.$Progress.start();
			const body = {
				email: this.form.email,
				password: this.form.password
			};
			axios.post('/admin/login', body).then( async (res) => {
				const user = {
					name: res.data.data.name,
					email: res.data.data.email,
					role: res.data.data.role,
					accessToken: res.data.data.access_token
				};
				await this.login(user);
				this.$successFlash('Welcome to papyrus!');
				this.$router.push({name: 'dashboard'});
				this.$Progress.finish();
			})
			.catch((error) => {
				if (error.response && (error.response.status == 422 || error.response.status == 401)) {
					this.error = error.response.data.errors;
				}
				else {
					this.$errorFlash('Something is not right!');
				}
				this.showErrorMsg = true;
				this.$Progress.fail();
			});
		},
		reset() {
			this.form.email = '';
			this.form.password = '';

			this.$nextTick(() => {
				this.$refs.observer.reset();
			});
		},
		getValidationState ({ dirty, validated, valid = null }) {
			return dirty || validated ? valid : null;
		},
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
