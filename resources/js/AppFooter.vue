<template>
	<div class="layout-footer" style="position: fixed;">
		<span class="footer-text" style="margin-right: 5px">{{ appName }}</span>
		<span class="footer-text" style="margin-left: 5px">&#169;2020 All rights reserved</span>
	</div>
</template>

<script>
	export default {
		name: "AppFooter",
		props: {
			appName: String
		}
	}
</script>

<style scoped>

.layout-footer {
	position: absolute;
   	bottom: 0;
   	width: 100%;
}

</style>