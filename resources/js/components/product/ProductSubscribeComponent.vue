<template>
	<button type="button" @click="submit()" :disabled="loading">
		<slot v-if="loading === false"></slot>
		<span v-else>
			<i class="fas fa-spinner fa-pulse mr-2"></i>
		</span>
	</button>
</template>

<script>
	export default {
		props: [
			'product', 'quantity'
		],

		data() {
			return {
				loading: false
			}
		},

		methods: {
			submit() {
            	if (this.loading) {
            		return
            	}

                let self = this

                this.loading = true

                $.ajax({
                    url: route('product-subscription.store'),
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: this.product.id,
                        quantity: this.quantity
                    }
                })
                .done(function(response) {
                    self.$root.$emit('cartItemAdded', response);
                })
                .fail(function(response) {
                    try {
                        alert(response.responseJSON.message)
                    } catch (e) {
                        console.log(e)
                    }
                })
                .always(function() {
					self.loading = false                	
                });
	            
	        }
		}
	}
</script>