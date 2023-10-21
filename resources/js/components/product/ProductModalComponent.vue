<template>

	<div class="modal" tabindex="-1" data-keyboard="true" role="dialog" aria-hidden="true" id="product-modal-component" data-backdrop="static">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header pb-0 border-0">
					<button type="button" class="close" aria-label="Close" @click.prevent="close()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body pt-0 pb-3 min-h-md-420px">

					<div class="px-3 row align-items-center justify-content-star">

						<div class="col-lg-4 col-xl-6 mx-auto mb-0 w-100 text-center align-self-center align-items-center d-flex" v-if="selectedProduct.id !== undefined">
							<img 
								data-error="/storage/notfound.png"
					            data-loading="/images/px.png"
					            src="/images/px.png"
				                :data-src="'/storage/' + selectedProduct.main_image"
					            v-lazy="'/storage/' + selectedProduct.main_image"
								class="d-block h-auto mx-auto img-fluid w-100"
							>
						</div>

						<div class="col-lg-8 col-xl-6 align-self-start" v-if="selectedProduct.id !== undefined">

							<h1 class="d-none d-sm-block font-weight-light text-dark mb-3 line-height-1-2 h3">{{ selectedProduct.name }}</h1>

							<h2 
								v-if="selectedProduct.brand !== undefined && selectedProduct.brand !== null && selectedProduct.brand.name !== undefined" 
								class="h6 font-weight-bold" style="min-height: 20px;"
							>
								<span>
									{{ selectedProduct.brand.name }}
								</span>
							</h2>

							<div class="d-flex flex-row align-items-center mb-3">
								<star-rating 
									:increment="0.5" 
									:read-only="true" 
									:show-rating="false" 
									:star-size="15" 
									active-color="#FEB731"
									v-model="selectedProduct.review_avg"
								>
								</star-rating>
								&nbsp;
								<span 
									@click="scrollTo('#reviews')" 
									v-if="selectedProduct.review_count > 0" 
									class="text-muted-4 mt-1 font-family-open-sans small"
								>
									(
										{{ selectedProduct.review_count }}
										<span v-if="selectedProduct.review_count === 1">Review</span>
										<span v-else>Reviews</span>
									)
								</span>
							</div>

							<div v-if="selectedProduct.description_text !== '' && selectedProduct.description_text !== null" class="text-left">
								<div class="font-family-open-sans lead line-height-1-1 font-weight-light" v-if="product !== null">
									<small>
										{{ selectedProduct.description_text | truncate(220) }} <a class="font-weight-bold text-decoration-underline text-nowrap" :href="route('product.show', selectedProduct.slug) + '#product-description-' + selectedProduct.id">See more</a>
									</small>
								</div>
							</div>

							<div class="my-4 align-items-center text-left">
								<div class="form-group">
									<zipcode-component></zipcode-component>
								</div>
							</div>

							<div class="my-4 text-left">
								<div class="form-group">
									<span class="font-weight-bolder text-dark mb-1 d-block">Availability:</span>
									<div class="text-muted-3 font-weight-bolder font-family-open-sans">

										<span v-if="selectedProduct.availability !== null && selectedProduct.availability.id === 1" class="text-green">
											<i class="fas fa-check-circle"></i> In stock.
										</span>
										<span v-else-if="selectedProduct.availability !== null && selectedProduct.availability.id === 0" class="text-danger">
											<i class="fas fa-times-circle"></i> Out of stock
										</span>
										<span v-else class="text-danger">
											<i class="fas fa-times-circle"></i> Out of stock
										</span>
										
									</div>
								</div>
							</div>

							<div class="my-4 text-left">
								<div class="form-group">
									<span class="font-weight-bolder text-dark mb-1 d-block">Estimated Arrival Date: </span>
									<div class="text-muted-3">
										<span 
											class="cursor-pointer text-decoration-underline font-family-open-sans"
										>
											Arrives by {{ arrivalDate }}
										</span>
									</div>
								</div>
							</div>

							<div v-if="products.length > 0" class="my-4 text-left">
								<div class="form-group">
									<span class="font-weight-bolder text-dark mb-1 d-block">Count: </span>
									<div class="btn-group-toggle" data-toggle="buttons">

										<label class="btn font-family-open-sans btn-white rounded px-3 active" @click="selectedProduct = product">
									    	<input 
									    		v-model="selectedProduct" 
									    		name="option" 
									    		type="radio" 
									    		:value="product"
									    	> 
									    		{{ product.option_name }}
									  	</label>

									  	<label v-for="productItem in products" class="btn font-family-open-sans btn-white rounded px-3" @click="selectedProduct = productItem">
									    	<input 
									    		v-model="selectedProduct" 
									    		name="option" 
									    		type="radio" 
									    		:value="productItem"
									    	> 
									    		{{ productItem.option_name }}
									  	</label>

									</div>
								</div>
							</div>

							<div class="my-4" v-if="loading">
								<i class="fas fa-spinner fa-pulse fa-2x"></i>
							</div>

							<hr class="border-dark">
							
							<div class="d-flex justify-content-end mt-auto mb-0">	
								<product-cart-component class="d-flex flex-row align-items-center justify-content-center mr-auto ml-0" :product="selectedProduct"></product-cart-component>
								<add-to-favorites :product="selectedProduct"></add-to-favorites>
							</div>

							<hr class="border-dark">

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
	export default {
		methods: {
			reduceQuantity() {
				if(this.quantity > 1) {
					this.quantity -= 1;
				}
			},
			raiseQuantity() {
				if(this.quantity < this.selectedProduct.quantity) {
					this.quantity += 1;
				}
			}
		},
		data(){
			return {
				loading: false,
				quantity: 1,
				product: [],
				selectedProduct: [],
				products: []
			}
		},

		mounted(){

			this.$root.$on('showProductChildren', (product, products) => {
				this.product = product
				this.products = products

				if (this.products !== undefined && this.products !== null && this.products.length === 0) {
					this.getProductOptions(this.product);
				}

				this.selectedProduct = this.product
				setTimeout(function() {
					$('#product-modal-component').modal('show')
				}, 250)
			});

			this.$root.$on('cartItemAdded', () => {

				$('#product-modal-component').modal('hide')
				let self = this;

				setTimeout(function() {
					self.product = []
					self.products = []
					self.selectedProduct = []
				}, 250)
			});
		},

		methods: {
			getProductOptions(product) {
				let self = this;
				self.loading = true

				$.ajax({
					url: route('product.index', product.id),
					type: 'POST',
					dataType: 'json',
				})
				.done(function(response) {
					self.products = response
				})
				.fail(function(response) {
					self.products = []
				})
				.always(function() {
					self.loading = false
				});
			},

			close() {

				$('#product-modal-component').modal('hide')

				let self = this;

				setTimeout(function() {
					self.loading = false;
					self.quantity = 1;
					self.product = [];
					self.selectedProduct = [];
					self.products = [];
				}, 250)
			}
		},

		computed:{
			currentProduct(){
				if (this.selectedProduct === undefined || this.selectedProduct.id === this.product.id || this.selectedProduct.length === 0) {
					return this.product
				}

				return this.selectedProduct;
			},

			arrivalDate() {
				let date = new Date();
				let result = new Date();
				result.setDate(date.getDate() + 5)
				return result.toLocaleString('en-us', {
					weekday: 'long',
					month: 'short',
					day: 'numeric'
				})
			}
		}
	}
</script>