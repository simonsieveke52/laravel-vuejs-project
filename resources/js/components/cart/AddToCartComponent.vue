<template>
	<div :class="cssClass" @click="addToCart()">
		<slot v-if="loading === false"></slot>
        <button v-else disabled="true" class="btn btn-highlight py-2 text-uppercase btn-lg min-w-180px min-w-sm-210px min-h-44px">
            <i class="fas fa-spinner fa-pulse mr-2"></i>
        </button>
	</div>
</template>

<script>
export default {
    props: [
        'productId',
        'cssClass',
        'quantity'
    ],
    data() {
        return {
            loading: false
        }
    },
    methods: {
        addToCart() {
            try {
                if (this.loading) {
                    return false
                }

                let self = this
                self.loading = true

                $.ajax({
                    url: '/cart',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: this.productId,
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
            } catch (e) {
                self.loading = false
            }
        }
    }
};
</script>