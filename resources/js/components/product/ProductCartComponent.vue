<template>
	<div>
        <div v-if="product.availability !== null" class="d-flex flex-row align-items-center justify-content-between w-100">
            <div class="btn-group mr-3 mr-md-2 mr-lg-3">
                
                <button 
                	type="button" 
                	@click="reduceQuantity" 
                	class="btn text-hover-darker rounded-0 px-0"
                >
                    <i class="fas fa-minus"></i>
                </button>

                <input 
                	step="1" 
                	type="text" 
                	value="1" 
                	class="form-control text-center border-0 font-size-1-3rem mb-0 p-0 w-44px" 
                	v-model.number="selectedQuantity" :style="invalidQuantity ? 'background-color: #ffa4a4' : ''"
                >

                <button 
                	type="button" 
                	@click="raiseQuantity" 
                	class="btn text-hover-darker rounded-0 px-0"
                >
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="d-flex flex-nowrap align-items-center justify-content-between">
            	<div class="mr-3 pr-1 min-w-90px">
            		<money-component 
	            		v-if="product.original_price != product.price && product.original_price > 0" 
						class="text-decoration-line-through text-muted font-weight-bolder font-family-open-sans mb-0 h5" 
						:value="originalTotal"
					>
					</money-component>
					&nbsp;
            		<money-component class="text-dark font-weight-bolder font-family-open-sans mb-0 h3" :value="total"></money-component>
            	</div>
            	<div class="text-nowrap">
					<add-to-cart-component :quantity="selectedQuantity" :product-id="product.id">
						<button class="btn btn-highlight py-2 text-uppercase btn-lg min-w-180px min-w-sm-210px min-h-44px">
							<small class="py-1 d-block font-weight-bold font-size-0-9rem">Add To Cart</small>
						</button>
					</add-to-cart-component>
	            </div>
            </div>
        </div>
        <div v-else class="d-block w-100">
        	<div class="alert alert-danger">
				<p class="m-0">Out of Stock</p>
        	</div>
		</div>

	</div>
</template>

<script>
	export default{
		
		props: [
			'product'
		],
		
		methods: {
			reduceQuantity() {
				if(this.quantity > 1) {
					this.quantity -= 1;
				} else {
					this.quantity = 1;
				}
			},
			raiseQuantity() {
				this.selectedQuantity++;
			}
		},
		
		data(){
			return {
				quantity: 1,
				invalidQuantity: false
			}
		},

		watch: {
			selectedQuantity(newValue) {
				this.$emit('selectedQuantityUpdated', newValue)
			}
		},

		computed: {

			originalTotal() {
				return this.selectedQuantity * this.product.original_price
			},

			total() {
				return this.selectedQuantity * this.product.price
			},

			selectedQuantity: {
			    get: function () {
			      	return this.quantity
			    },
			    // setter
			    set: function (newValue) {
			      	if(Number.parseInt(newValue) < 999 && Number.parseInt(newValue) > 0) { 
						this.quantity = Number.parseInt(newValue);
						this.invalidQuantity = false
					} else {
						this.invalidQuantity = true
						toast('Invalid <span class="text-danger">product quantity</span>, please set a valid quantity then continue.')
					}
			    }
			}
		}
	}
</script>