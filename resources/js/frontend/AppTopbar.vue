<template>
	<div class="layout-topbar" style="background: rebeccapurple;">
		<button class="p-link layout-menu-button" @click="onMenuToggle">
			<font-awesome-icon :icon="['fas', 'bars']" size="lg" />
		</button>
		<div class="layout-topbar-icons">
		</div>
	</div>
</template>

<script>
export default {
    methods: {
        onMenuToggle(event) {
            this.$emit('menu-toggle', event);
        }
    }
}
</script>