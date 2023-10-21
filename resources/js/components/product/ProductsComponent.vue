<template>
	<div>

		<div class="row" v-if="displayBreadcrumb === true && response !== null && response.category !== undefined">
			<div class="col-12">
				<section class="section section--breadcrumb text-left font-size-0-85rem">
				    <div class="text-dark-2">
				        <a href="/">Home</a>
	                	<a 
	                		v-for="(category, index) in response.parentCategories" 
	                		:href="route('category.filter', category.slug).url()" 
	                		:class="index == response.parentCategories.length - 1 ? 'text-dark' : ''"
	                	>
	                		<span class="px-1">/</span>{{ category.name }}
	                	</a>
				    </div>
				</section>
			</div>
		</div>

		<div 
			v-if="displayCategoryName === true && response !== null && response.category !== undefined && response.category !== null"
			class="row mt-3"
		>
			<div class="col-12 text-left mt-4 text-dark-dark-5 d-flex flex-row flex-wrap align-items-end justify-content-between">

				<h1 v-if="response.category.name !== undefined" class="h2 mb-0 font-size-1-5rem fon-size-md-2rem text-uppercase font-weight-light line-height-1-4">
					{{ response.category.name }}
				</h1>

				<h1 
					v-else-if="response.brandNames !== null && response.brandNames.length > 0" 
					class="flex-wrap mb-0 h3 mt-2 mt-lg-0 font-size-1-4rem fon-size-md-1-9rem fon-size-lg-2rem text-uppercase d-flex align-items-center justify-content-center font-weight-light"
				>
					<span v-for="(brandName, index) in response.brandNames" class="mb-1">
						{{ brandName }}
						<span v-if="index < response.brandNames.length - 1">, &nbsp;&nbsp;</span>
					</span>
				</h1>

				<div v-if="response !== null && response.category !== undefined">
					<div class="text-right">
						<span 
							v-if="response.products !== undefined"
							class="text-muted-4 font-weight-light"
						>
							<span>{{ response.products.total }} </span>
							<span v-if="response.products.total > 1">Items</span>
							<span v-else>Item</span>
						</span>
					</div>
				</div>
				
			</div>
			<div class="col-12">
				<hr class="mt-2 border-muted-1">
			</div>
		</div>

		<form :class="'row my-3 jq-filters-container ' + (displayFilters ? '' : 'justify-content-end')">

			<div v-if="displayFilters" class="col-12 col-sm-6 col-md-8 col-lg-10">
				<div class="form-group small d-flex flex-row align-items-center">
					<label class="font-weight-bold mb-0 text-capitalize text-nowrap text-black mr-1">Sort by</label>
					<select 
						@change="getResults()" 
						v-model="sortBy" 
						name="sortBy" 
						class="form-control form-control-sm max-w-130px text-center px-0 py-1 text-dark-3 rounded text-muted bg-white border-0"
					>
						<option value="relevance" >Relevance</option>
						<option value="h-t-l">Price Highest to Lowest</option>
						<option value="l-t-h">Price Lowest to Highest</option>
					</select>
				</div>
			</div>

			<div class="col-2 col-sm-6 col-md-4 col-lg-2 text-right">
				<div class="d-none d-sm-flex form-group">
					<div class="ml-auto mr-0 btn-group max-w-100px">

						<button 
							@click.prevent="viewType = 'grid'" 
							class="btn d-none d-md-block border-0 rounded-0" :class="viewType != 'grid' ? 'text-muted-1' : 'text-muted-4'"
						>
							<i class="fas fa-th"></i>
						</button>

						<button
							@click.prevent="viewType = 'grid-large'" 
							class="btn border-0 rounded-0" :class="viewType != 'grid-large' ? 'text-muted-1' : 'text-muted-4'"
						>
							<i class="fas fa-th-large"></i>
						</button>

						<button 
							@click.prevent="viewType = 'list'" 
							class="btn border-0 rounded-0" :class="viewType != 'list' ? 'text-muted-1' : 'text-muted-4'"
						>
							<i class="fas fa-bars"></i>
						</button>

					</div>
				</div>
			</div>
			
		</form>

		<div v-if="response !== null && response.category !== undefined" class="row">
			<product-component v-for="product in products.data" :product="product" :view-type="viewType" :key="product.id"></product-component>
		</div>
		<div class="row">
			<div class="col-12 mt-4" v-if="response !== null && (response.products === undefined || response.products.total === 0)">
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					No products in this category match your filters.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12 mt-3">
				<pagination :limit="3" :data="products" align="right" class="flex-wrap" @pagination-change-page="getResultsWithPagination"></pagination>
			</div>
		</div>
		
	</div>
</template>

<script>

	export default {

		props: {
			startPage: {
				type: Number,
				default: 1
			},
			initialLoad: {
		      	type: Boolean,
		      	default: true
		    },
		    displayCategoryName: {
		      	type: Boolean,
		      	default: true
		    },
		    displayBreadcrumb: {
		      	type: Boolean,
		      	default: true
		    },
		    displayFilters: {
		    	type: Boolean,
		      	default: true	
		    }
		},

		data(){
			return {
				response: null,
				products: {},
				viewType: 'grid',
				sortBy: 'relevance',
				perPage: 24,
				currentPage: 1,
			}
		},

		mounted() {

			let self = this

			this.loadLocalStorage()

			this.$root.$on('refresh_products', function(response) {
				self.response = response
				self.products = response.products

        document.getElementById("site_title").textContent='Natural House | '+response.category.name;
        document.getElementById("category_title").textContent='Natural House | '+response.category.name;
        document.getElementById("category_description").textContent=response.category.description;
        document.getElementById("category_og:title").textContent= 'Natural House | '+ response.category.name;
        document.getElementById("category_og:description").textContent= response.category.description;
			})

			this.$root.$on('refreshProducts', function(page) {
				self.getResults(page, function() {
					$("html").animate({ scrollTop: 0 }, 100);
				})
			})

			if (this.initialLoad === true) {
				let page = this.startPage > 0 ? this.startPage : 1;
				this.getResults(page)
			}

			if (window.initProductsComponent !== undefined) {
				window.initProductsComponent()
			}

		},

		watch: {
			viewType(newValue) {
				try {
                	localStorage.setItem('viewType', JSON.stringify(newValue))
                } catch (e) {
                }
			}
		},

		methods: {

			loadLocalStorage() {
				let item = null;
				try {
                	item = localStorage.getItem('viewType')
                	item = JSON.parse(item)
                	if (item !== undefined && item !== null) {
						this.viewType = item
                	}
                } catch (e) {
                	console.log(e)
                }

                try {
                	item = localStorage.getItem('sortBy')
                	item = JSON.parse(item)
                	if (item !== undefined && item !== null) {
						this.sortBy = item
                	}
                } catch (e) {
                	console.log(e)
                }

                try {
                	item = localStorage.getItem('perPage')
                	item = JSON.parse(item)
                	if (item !== undefined && item !== null) {
						this.perPage = item
                	}
                } catch (e) {
                	console.log(e)
                }
			},

			saveLocalStorage() {
                try {
                	localStorage.setItem('viewType', JSON.stringify(this.viewType))
                } catch (e) {
                }

                try {
                	localStorage.setItem('sortBy', JSON.stringify(this.sortBy))
                } catch (e) {
                }

                try {
                	localStorage.setItem('perPage', JSON.stringify(this.perPage))
                } catch (e) {
                }
            },

			getResults(page = 1, callback = undefined) {

				let self = this

				$('#app').busyLoad('show')

				let vars = location.search
					.split('&')
					.filter(function(e) {
						return e.search('page=') === -1
					})
					.filter(function(e) {
						return e.length > 0;
					})

				let delimiter = vars.length > 0 ? '&' : '?'
				let link = delimiter + 'page=' + page;
				let url = location.protocol + '//' + location.hostname + location.pathname + vars.join('&') + link;

				$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					data: {
						sortBy: this.sortBy,
						perPage: this.perPage,
					},
				})
				.done(function(response) {

					self.response = response
					self.products = response.products
					self.currentPage = response.products.current_page;

					self.saveLocalStorage()
				})
				.always(function() {
					$('#app').busyLoad('hide')
					try {
						if (callback !== undefined) {
							callback()
						}
					} catch (e) {

					}
				});
			},

			getResultsWithPagination(page = 1) {
				this.triggerHistory(page)
			},

			triggerHistory(page) {
				let state = History.getState()
				let vars = location.search
					.split('&')
					.filter(function(e) {
						return e.search('page=') === -1
					})
					.filter(function(e) {
						return e.length > 0;
					})

				let delimiter = vars.length > 0 ? '&' : '?'
				let link = delimiter + 'page=' + page;

				if (page === 1) {
					link = '';
				}

				let url = location.protocol + '//' + location.hostname + location.pathname + vars.join('&') + link;

				History.pushState({
						event: 'refreshProducts',
						page: page,
						state: 'pagination'
					}, 
					document.title,
					url
				);

				state = History.getState()

				if (Object.keys(state.data).length === 0 && state.data.constructor === Object) {
	                this.getResults(page, function() {
						$("html").animate({ scrollTop: 0 }, 100);
					})
	            }
			}
		}
	}

</script>