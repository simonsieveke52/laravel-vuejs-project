<template>
	<button type="button" @click.prevent="validateCheckout()">
		<slot></slot>
	</button>
</template>

<script>
	export default {

		props: {
			validationRoute: {
				type: String
			},
			checkoutRoute: {
				type: String
			}
		},

		data() {
			return {
				isValid: false
			}
		},

		methods: {

			validateCheckout() {

				$.busyLoadFull('hide')
				$.busyLoadFull('show')

				$.ajax({
					url: this.validationRoute,
					type: 'POST',
					dataType: 'json',
					data: $(this.$el).parents('form').serialize(),
				})
				.done(function() {
					console.log("success");
				})
				.fail(function(response) {
					alert(response.responseJSON.message)
				})
				.always(function() {
					$.busyLoadFull('hide')
				});
				
			}
		}
	}
</script>