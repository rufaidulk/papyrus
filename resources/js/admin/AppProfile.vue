<template>
	<div class="layout-profile">
		<div>
			<img src="images/admin/profile.png" alt="" />
		</div>
		<button class="p-link layout-profile-link" @click="onClick">
			<span class="username">{{ user.name }}</span>
			<i class="pi pi-fw pi-cog"></i>
		</button>
        <transition name="layout-submenu-wrapper">
            <ul v-show="expanded">
                <li>
					<button class="p-link">
						<font-awesome-icon :icon="['fas', 'user-circle']" />
						<b-link to="/profile" class="profile-links">Profile</b-link>
					</button>
				</li>
                <li>
					<button class="p-link">
						<font-awesome-icon :icon="['fas', 'sign-out-alt']" />
						<b-link to="/logout" class="profile-links">Logout</b-link>
					</button>
				</li>
            </ul>
        </transition>
		
	</div>
</template>

<script>
	export default {
		data() {
			return {
				expanded: false
			}
		},
		methods: {
			onClick(event){
				this.expanded = !this.expanded;
				event.preventDefault();
			}
		},
		computed: {
			user() {
				return this.$store.getters.user;
			}
		}
	}
</script>

<style scoped>

.profile-links {
	color: #fff;
}
</style>