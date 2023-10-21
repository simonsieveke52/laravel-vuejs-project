<template>

	<div :class="'product-container rounded-lg ' + viewClass" :data-id="product.id">
		<div class="card position-relative m-0 h-100 border-0 position-relative rounded-lg">

			<div class="rounded-lg d-flex h-100 align-items-center justify-content-center">
	            <a @click.prevent="showProduct()" :href="productLink" class="text-center d-flex align-items-center justify-content-center min-h-180px rounded-lg">
		            <img
			            data-error="/storage/notfound.png"
			            data-loading="/storage/notfound.png"
			            src="/storage/notfound.png"
		                :data-src="'/storage/notfound.png'"
			            v-lazy="'/storage/' + productImage"
		                :class="productClass"
		                :alt="product.name"
		            >
		        </a>
			</div>

	        <div class="mt-auto mb-0">
		        <div class="px-0 d-flex flex-column pb-0 max-h-300px">
					<div class="d-flex flex-column justify-content-end">
						<div class="px-4 pt-4 d-flex flex-column" style="min-height: 85px;">
							<h3 class="text-left card-title font-weight-bold h6 mb-auto mt-0">
			                    <a @click.prevent="showProduct()" :href="productLink" class="text-dark-2 d-block mb-2 font-open-poppins font-size-1-7rem" :title="product.name">
			                        {{ product.name | truncate(45) }}
			                    </a>
			                </h3>

							<!-- <div class="d-flex flex-row align-items-center mb-2">
								
								<star-rating 
									:increment="0.5" 
									:read-only="true" 
									:show-rating="false" 
									:star-size="15" 
									active-color="#FEB731" 
									v-model="product.review_avg"
								>
								</star-rating>
								&nbsp;&nbsp;
								<span v-if="product.review_count > 0" class="text-muted-3 mt-1 font-family-open-sans small">
									({{ product.review_count }})
								</span>

							</div> -->
		                	<div class="mb-0 mt-2 d-flex flex-row align-items-center justify-content-between font-family-open-sans">
		                		<span class="text-dark-3 font-weight-bold h6">
		                			{{ product.description | truncate(200, '...') }}
		                		</span>
								<!-- <strike class="text-secondary-7 small" v-if="product.original_price != product.price && product.original_price > 0">
									{{ product.original_price | currency }}
								</strike> -->
		                	</div>
			                <div v-if="product.is_free_shipping">
								<span class="text-danger small" v-if="product.free_shipping_option == 'Free 2-day'">+ FREE 2-DAY SHIPPING</span>
								<span class="text-danger small" v-else>+ FREE SHIPPING</span>
							</div>
						</div>
					</div>
		        </div>

	        </div>

	        <div class="px-2 pb-4 text-center d-flex flex-row mt-auto mb-0 align-self-end align-items-center justify-content-between w-100">
	        	<div class="w-100 d-flex flex-column max-w-180px ml-2">
	                <!-- <button type="button" @click="$root.$emit('showProductChildren', product, product.children)" class="btn btn-highlight px-4 py-2 text-uppercase text-nowrap shadow">
						<small class="py-1">Add to cart</small>
					</button> -->

					<div class="btn-group mt-2">
						<a 
		        			:href="productLink" 
		        			class="btn btn-outline-green px-4 py-2 text-uppercase max-w-169px mx-auto"
		        		>
			        		<small class="py-1 font-weight-border text-nowrap">View Product</small>
		        		</a>
						<!-- <add-to-favorites 
		                	class="btn btn-outline-muted-4 border-left-0 text-uppercase max-w-169px mx-auto py-0"
		                    context="short"
		                    icon="fa-star"
		                    defaultFilled="true"
		                    :product="product"
		                >
		                </add-to-favorites> -->
					</div>

	        	</div>
            </div>

		</div>
	</div>

</template>

<script>

	export default {

		props: {
			product: {
				type: Object,
				default: function() {
					return {}
				}
			},

			productClass: {
				type: String,
				default: function() {
					return 'img-fluid rounded-lg img-responsive w-auto d-block m-auto max-h-280px h-auto'
				}	
			},

			viewType: {
				type: String,
				default: function() {
					return 'grid'
				}
			},
		},

		data() {
			return {
				timeout: null
			}
		},

		created() {
			try {
				if (this.product.main_image == 'storage/notfound.png' || this.product.main_image == '/storage/notfound.png') {
	            	this.product.main_image = 'notfound.png'
	            }
			} catch (e) {

			}
		},

		mounted() {
			try {
				if(localStorage.scrollPosition !== null && localStorage.scrollPosition !== 0 && localStorage.scrollPosition != '0') {
					if (parseInt(localStorage.scrollPosition) <= $(document).height()) {
		                $("html, body").animate({ scrollTop: localStorage.scrollPosition }, 100);
		                localStorage.setItem('scrollPosition', 0)
					}
	            }
			} catch (e) {

			}
		},

		methods: {
			showProduct() {

				let elm = $(this.$el).find('.card')
				let url = route('product.show', this.product.slug).url()

				try {
			      	localStorage.setItem("scrollPosition", $(document).scrollTop());
				} catch (e) {

				}

				$(elm).busyLoad('hide')
				$(elm).busyLoad('show')

				if (this.timeout !== null && this.timeout !== undefined) {
					clearTimeout(this.timeout)
				}

				try {
					window.dataLayer.push({
					    'event': 'productClick',
					    'ecommerce': {
					      'click': {
					        	'actionField': {'list': 'Product click'},
					        	'products': [{
							        'id': this.product.id,
							        'name': this.product.name,
							        'price': this.product.price,
							        'position': 1
					         	}]
					        }
					    },
					    'eventCallback': function() {
					       	$(elm).busyLoad('hide')
					       	document.location = url
					    }
				  	});
				  	this.timeout = setTimeout(function(){
				  		$(elm).busyLoad('hide')
				  		document.location = url
				  	}, 600)
				} catch(e) {
					$(elm).busyLoad('hide')
					document.location = url
				}
			}
		},

		computed:{

			inStock(){
				return parseInt(this.product.availability_id) !== 0;
			},

			viewClass() {

				if (this.viewType === 'custom') {
					return ''
				}

				if (this.viewType === 'grid') {
					return 'mb-4 col-lg-4 col-md-6 col-sm-6 col-12'
				}

				if (this.viewType === 'grid-large') {
					return 'mb-4 col-sm-6 col-12'
				}
				
				return 'mb-4 col-12'
			},

			productLink() {
				let slug = this.product.slug;

				if (slug === '' || slug.length === '') {
					slug = this.product.id;
				}

				return route('product.show', slug).url()
			},

			productImage() {
				try {
					let img = this.product.main_image;

					if (img == 'storage/notfound.png' || img == '/storage/notfound.png') {
		            	img = 'notfound.png'
		            }

		            return img.replace('//', '/');
				} catch (e) {
					return this.product.main_image;
				}
			}
		}
	}

</script>
<style lang="scss" scoped>
	.product-container {
		.card {
		    transition: box-shadow .3s ease-in-out;
			box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
		}
	}
</style>