<template>
	<div>
		<div class="col-12 d-block d-sm-none">
			<slot name="breadcrumb"></slot>
		</div>

		<div class="col-12 d-block d-sm-none">
			<h2 class="h2 mt-3 font-size-1-3rem line-height-1-8rem">{{ selectedProduct.name }}</h2>
		</div>
		
		<div class="col-md-5 col-lg-6">

			<div v-if="selectedProduct.status !== 1">
				<div v-if="selectedProduct.status === 0">
					<div class="alert alert-danger text-center">
						<strong>Currenty Disabled</strong>
					</div>
				</div>
				<div v-else-if="selectedProduct.status === 2">
					<div class="alert alert-warning text-center">
						Draft Product, not published yet.
					</div>
				</div>
			</div>

			<div class="product--image__wrapper alert alert-light text-center d-flex flex-column align-items-center">
				<product-images-component :product="selectedProduct"></product-images-component>
			</div>

			<div class="mt-3 mb-2 mb-md-0 text-center">
				<ul class="list-inline small">
					<li class="list-inline-item px-1">
						<a 
							target="_blank" 
							class="text-muted-2" 
							:href="'https://www.facebook.com/sharer/sharer.php?u=' + route('product.show', selectedProduct.slug)"
						>
							<i class="fab fa-2x fa-facebook-square"></i>
						</a>
					</li>
					<li class="list-inline-item px-1">
						<a 
							target="_blank" 
							class="text-muted-2" 
							:href="'https://twitter.com/intent/tweet?text=' + route('product.show', selectedProduct.slug)"
						>
							<i class="fab fa-2x fa-twitter-square"></i>
						</a>
					</li>
					<li class="list-inline-item px-1">
						<a 
							target="_blank" 
							class="text-muted-2" 
							:href="'http://pinterest.com/pin/create/button/?url=' + route('product.show', selectedProduct.slug) + '&media=' + ( mainImageUrl ) + '&description=' + selectedProduct.name"
						>
							<i class="fab fa-2x fa-pinterest-square"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="col-md-7 col-lg-6 min-h-md-500px">

			<div class="d-none d-sm-block">
				<slot name="breadcrumb"></slot>
			</div>

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
					class="text-muted-4 mt-1 font-family-open-sans small cursor-pointer text-decoration-underline"
				>
					(
						{{ selectedProduct.review_count }}
						<span v-if="selectedProduct.review_count === 1">Review</span>
						<span v-else>Reviews</span>
					)
				</span>
			</div>

			<div v-if="selectedProduct.description_text !== '' && selectedProduct.description_text !== null">
				<div class="font-family-open-sans lead line-height-1-1 font-weight-light" v-if="product !== null">
					<small>
						{{ selectedProduct.description_text | truncate(220) }}
						&nbsp;<a class="font-weight-bold text-decoration-underline" href="#" @click.prevent="scrollTo('#product-description-' + product.id)">See more</a>
					</small>
				</div>
			</div>

			<div class="my-4">
				<div class="form-group">
					<zipcode-component></zipcode-component>
				</div>
			</div>

			<div class="my-4">
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
		
			<div class="my-4">
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

			<slot name="shipping-label"></slot>

			<div v-if="options.length > 0" class="my-4">
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

					  	<label v-for="productItem in options" class="btn font-family-open-sans btn-white rounded px-3" @click="selectedProduct = productItem">
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

			<div class="my-4">
				<div class="pt-3 d-flex flex-row flex-nowrap align-items-center justify-content-end">
					<div class="h6 mb-0 line-height-1-1 mr-3">
						<slot name="subscription-label"></slot>
					</div>
					<product-subscribe-component 
						class="btn btn-outline-muted-4 py-2 text-uppercase btn-lg min-w-180px min-w-sm-210px min-h-44px" 
						:quantity="selectedQuantity"
						:product="selectedProduct"
					>
						<small class="py-1 font-weight-border font-size-0-85rem font-size-sm-0-9rem">subscribe and save</small>
					</product-subscribe-component>
				</div>
			</div>

			<hr class="border-dark">

			<div class="my-4">
				<div class="d-flex flex-row flex-wrap align-items-center justify-content-end">
					<product-cart-component 
						class="d-flex flex-row align-items-center justify-content-end w-100" 
						:product="selectedProduct"
						@selectedQuantityUpdated="selectedQuantity = $event"
					>
					</product-cart-component>
				</div>
			</div>

			<hr class="border-dark">
			
			<div class="d-flex align-items-center justify-content-end">
				<add-to-favorites :product="selectedProduct"></add-to-favorites>
			</div>

		</div>

		<div class="col-12 min-h-md-400px">
			<ul class="nav border-bottom mb-4 pb-1 flex-nowrap">
				<li class="nav-item">
					<a 
						class="nav-link px-0 text-dark-active box-shadow-green-active py-3 active text-uppercase mr-2 mr-sm-3 mr-md-4 mr-lg-5 text-center font-size-0-88rem line-height-1-6 font-size-sm-0-95rem"
						data-toggle="tab" 
						href="#how-it-works" 
						aria-selected="true" 
						role="tab"
					>
						How it works
					</a>
				</li>
				
				<li class="nav-item">
					<a 
						class="nav-link px-0 text-dark-active box-shadow-green-active py-3 text-uppercase mr-2 mr-sm-3 mr-md-4 mr-lg-5 text-center font-size-0-88rem line-height-1-6 font-size-sm-0-95rem" 
						data-toggle="tab" 
						href="#how-to-use" 
						aria-selected="false" 
						role="tab"
					>
						How to use
					</a>
				</li>
				
				<li class="nav-item">
					<a 
						class="nav-link px-0 text-dark-active box-shadow-green-active py-3 text-uppercase mr-2 mr-sm-3 mr-md-4 mr-lg-5 text-center font-size-0-88rem line-height-1-6 font-size-sm-0-95rem" 
						data-toggle="tab" 
						href="#ingredients" 
						aria-selected="false" 
						role="tab"
					>
						Ingredients
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link px-0 text-dark-active box-shadow-green-active py-3 text-uppercase mr-2 mr-sm-3 mr-md-4 mr-lg-5 text-center font-size-0-88rem line-height-1-6 font-size-sm-0-95rem" 
						data-toggle="tab" 
						href="#reviews-tab" 
						aria-selected="false" 
						role="tab"
					>
						Reviews
					</a>
				</li>
			</ul>

			<div class="tab-content font-weight-norma pt-3" :id="'product-description-' + product.id">
			  	
			  	<div class="tab-pane fade show active" id="how-it-works" role="tabpanel" aria-labelledby="how-it-works">
			  		<div>
						<div v-if="selectedProduct.description !== undefined && selectedProduct.description !== null && selectedProduct.description !== ''">
							<h2 class="font-size-1-3rem font-weight-bold mb-3">Description</h2><div class="font-weight-normal line-height-1-4 font-size-0-95rem" v-html="selectedProduct.description"></div></div>
						<div v-else>
				  			Nothing to show under this section yet.
				  		</div>
					</div>
			  	</div>

			  	<div class="tab-pane fade" id="how-to-use" role="tabpanel" aria-labelledby="how-to-use-tab">
			  		<div v-if="selectedProduct.how_to_use !== undefined && selectedProduct.how_to_use !== null && selectedProduct.how_to_use !== ''">
			  			<h2 class="font-size-1-3rem font-weight-bold mb-3">How to use</h2><div class="font-weight-normal line-height-1-4 font-size-0-95rem" v-html="selectedProduct.how_to_use"></div></div>
			  		<div v-else>
			  			Nothing to show under this section yet.
			  		</div>
			  	</div>

			  	<div class="tab-pane fade" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
			  		<div v-if="selectedProduct.ingredients !== undefined && selectedProduct.ingredients !== null && selectedProduct.ingredients !== ''">
			  			<h2 class="font-size-1-3rem font-weight-bold mb-3">Ingredients</h2><div class="font-weight-normal line-height-1-4 font-size-0-95rem" v-html="selectedProduct.ingredients"></div></div>
			  		<div v-else>
			  			Nothing to show under this section yet.
			  		</div>
			  	</div>

			  	<div class="tab-pane fade" id="reviews-tab" role="tabpanel" aria-labelledby="reviews">
			  		<div id="reviews">
			  			<product-reviews-component :product="selectedProduct" :start-page="startPage"></product-reviews-component>
			  		</div>
			  	</div>

			</div>

		</div>

	</div>
</template>

<script>
	export default {

		props: [
			'product', 'options', 'startPage'
		],

		data() {
			return {
				selectedProduct: this.product,
				selectedQuantity: 1,
			}
		},

		methods: {
			scrollTo(elementId) {

				if ($(elementId).length === 0) {
					return false;
				}

				$([document.documentElement, document.body]).animate({
			        scrollTop: $(elementId).offset().top
			    }, 20);
			}
		},

		computed: {
			arrivalDate() {
				let date = new Date();
				let result = new Date();
				result.setDate(date.getDate() + 5)
				return result.toLocaleString('en-us', {
					weekday: 'long',
					month: 'short',
					day: 'numeric'
				})
			},

			mainImageUrl() {
				let url = window.location.protocol + '//' + window.location.host
				let img = '' + this.selectedProduct.main_image;
				img = img.replace(url, '');
				let join = img[0] === '/' ? '' : '/'

				return url + join + img;
			}
		}
	}
</script>

<style scoped>
	.tab-content.font-weight-normal b, .tab-content.font-weight-normal strong{
		font-weight: 500 !important;
	}
</style>
