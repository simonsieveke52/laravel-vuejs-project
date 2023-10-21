<template>
	<span>
		<slot>{{ value | currency }}</slot>
	</span>
</template>
<script>
	export default {
		props: [
			'value'
		]
	}
</script>