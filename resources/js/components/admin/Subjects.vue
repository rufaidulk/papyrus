<template>
 	<div>
		<b-container fluid>
			<b-card title="Subjects" header-tag="header" footer-tag="footer">
				<template #header>
					<b-button type="button" v-b-modal.create-form-modal class="float-right" variant="primary">New Subject</b-button>
      			</template>
				<b-table striped hover :items="items" :fields="fields">
					<template #cell(Action)="data">
						<b-button size="sm" variant="info" @click="showEditForm(data.item)" class="mr-1">
							<font-awesome-icon :icon="['fas', 'edit']" />
						</b-button>
						<b-button size="sm" variant="danger" @click="deleteSubject(data.item)" class="mr-1">
							<font-awesome-icon :icon="['fas', 'trash']" />
						</b-button>
					</template>
				</b-table>
				<template #footer>
				<b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" first-text="First" 
					prev-text="Prev" next-text="Next" last-text="Last"></b-pagination>
      			</template>
			</b-card>
		</b-container>

		<b-modal id="create-form-modal" ref="modal" title="New Subject">
			<b-alert v-model="showErrorMsg" variant="danger" dismissible>{{ error }}</b-alert>
				<validation-observer ref="observer">
					<b-form>
						<validation-provider name="Name" rules="required|alpha_spaces" v-slot="validationContext">
							<b-form-group id="input-group-1" label="Name:" label-for="input-1">
								<b-form-input id="input-1" v-model="form.name" type="text" :state="getValidationState(validationContext)"></b-form-input>
								<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
							</b-form-group>
						</validation-provider>
					</b-form>
				</validation-observer>
				<validation-provider name="Status" :rules="{ required: true }" v-slot="validationContext">
					<b-form-group id="input-group-2" label="Status:" label-for="input-2">
						<b-form-select v-model="form.status" :options="subjectStatuses" :state="getValidationState(validationContext)"></b-form-select>
						</b-form-select>
						<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>
				<template #modal-footer>
        			<div class="w-100">
						<b-button type="button" class="float-right" variant="danger" @click="resetForm()">Reset</b-button>		
						<b-button type="button" class="float-right mr-1" variant="primary" @click="createSubject()">Submit</b-button>
        			</div>
      			</template>
    	</b-modal>
		
		<b-modal id="edit-form-modal" ref="modal" title="Edit Subject">
			<b-alert v-model="showErrorMsg" variant="danger" dismissible>{{ error }}</b-alert>
				<validation-observer ref="observer">
					<b-form>
						<validation-provider name="Name" rules="required|alpha_spaces" v-slot="validationContext">
							<b-form-group id="input-group-1" label="Name:" label-for="input-1">
								<b-form-input id="input-1" v-model="form.name" type="text" :state="getValidationState(validationContext)"></b-form-input>
								<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
							</b-form-group>
						</validation-provider>
					</b-form>
				</validation-observer>
				<validation-provider name="Status" :rules="{ required: true }" v-slot="validationContext">
					<b-form-group id="input-group-2" label="Status:" label-for="input-2">
						<b-form-select v-model="form.status" :state="getValidationState(validationContext)">
							<option v-for="(status, index) in subjectStatuses" :key="index" :value="status.value">
								{{ status.text }}
							</option>
						</b-form-select>
						<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>
				<template #modal-footer>
        			<div class="w-100">
						<b-button type="button" class="float-right" variant="danger" @click="resetForm()">Reset</b-button>		
						<b-button type="button" class="float-right mr-1" variant="primary" @click="updateSubject()">Submit</b-button>
        			</div>
      			</template>
    	</b-modal>
  	</div>
</template>

<script>

export default {
  	data () {
    	return {
			fields: [
				{
					key: 'name',
					sortable: true
				},
				{
					key: 'status_txt',
					label: 'Status'
				},
				{
					key: 'Action'
				}
			],
			items: [],
			currentPage: '1',
			rows: '',
			perPage: '',
      		form: {
				id: '',
				name: '',
				status: '',
			},
			subjectStatuses: [
				{
					value: null,
					text: 'Select status'
				},
				{
					value: 1,
					text: 'Active'
				},
				{
					value: 2,
					text: 'Inactive'
				}
			],
			showErrorMsg: false,
			error: '',
    	};
	},
	created () {
		this.fetchSubjects(1);
	},
	methods: {
		fetchSubjects (page) {
			this.$Progress.start();
			axios.get('/admin/subjects', {
				headers: { 'Authorization': 'Bearer ' + this.$store.getters.user.accessToken },
				params: { page: page}
			})
			.then((res) => {
				this.rows = res.data.data.meta.total;
				this.perPage = res.data.data.meta.per_page;
				this.currentPage = res.data.data.meta.current_page;
				this.items = res.data.data.subjects;
				this.$Progress.finish();
			})
			.catch((error) => {
				if (error.response && error.response.status == 422) {
					this.error = error.response.data.errors;
					this.showErrorMsg = true;
				}
				else {
					this.$errorFlash('Something is not right!');
				}
				this.$Progress.fail();
			});
		},
		async createSubject () {
			const valid = await this.$refs.observer.validate();
			if (! valid) {
				return;
			}
			
			this.$Progress.start();
			const body = {
				name: this.form.name,
				status: this.form.status
			};
			
			axios.post('/admin/subjects', body, {
				headers: { 'Authorization': 'Bearer ' + this.$store.getters.user.accessToken }
			})
			.then( async (res) => {
				this.items.pop();
				this.items.unshift(res.data.data);
				this.resetForm();
				this.$bvModal.hide('create-form-modal');
				this.$Progress.finish();
				this.$successFlash('Subject created successfully!');
			})
			.catch((error) => {
				if (error.response && error.response.status == 422) {
					this.error = error.response.data.errors;
					this.showErrorMsg = true;
				}
				else {
					this.$errorFlash('Something is not right!');
				}
				this.$Progress.fail();
			});
		},
		showEditForm (data) {
			this.form.id = data.id;
			this.form.name = data.name;
			this.form.status = data.status;
			this.$bvModal.show('edit-form-modal');
		},
		async updateSubject () {
			const valid = await this.$refs.observer.validate();
			if (! valid) {
				return;
			}
			
			this.$Progress.start();
			const body = {
				name: this.form.name,
				status: this.form.status
			};
			axios.put('/admin/subjects/' + this.form.id, body, {
				headers: { 'Authorization': 'Bearer ' + this.$store.getters.user.accessToken }
			})
			.then( async (res) => {
				this.items.forEach( (item) => {
					if (res.data.data.id == item.id) {
						item.name = res.data.data.name;
						item.status = res.data.data.status;
						item.status_txt = res.data.data.status_txt;
						return;
					}
				});
				this.$bvModal.hide('edit-form-modal');
				this.$Progress.finish();
				this.$successFlash('Subject updated successfully!');
			})
			.catch((error) => {
				if (error.response && error.response.status == 422) {
					this.error = error.response.data.errors;
					this.showErrorMsg = true;
				}
				else {
					this.$errorFlash('Something is not right!');
				}
				this.$Progress.fail();
			});
		},
		resetForm () {
			this.form.name = '';
			this.form.status = '';

			this.$nextTick(() => {
				this.$refs.observer.reset();
			});
		},
		getValidationState ({ dirty, validated, valid = null }) {
			return dirty || validated ? valid : null;
		},
		deleteSubject (data) {
			this.$confirm({
				message: `To Do`,
				button: {
					no: 'No',
					yes: 'Yes'
				},
				callback: confirm => {
					if (confirm) 
					{
						//
					}
				}
			});
		}
	},
	watch: {
		currentPage: function(val) {
			this.fetchSubjects(val);
		}
	}
};
</script>