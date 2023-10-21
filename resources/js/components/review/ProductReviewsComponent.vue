<template>
	<div>
		<div>
			<product-review-form :product="product"></product-review-form>
		</div>
		<div>
			<div class="d-flex flex-column h-100">
				<div class="d-flex flex-row align-items-center justify-content-between w-100">
					<h1 class="font-weight-bold text-dark line-height-1-1 h5 mb-0">
						Customer Reviews
					</h1>
					<span v-if="response.total !== undefined && response.total !== null && response.total !== 0" class="text-muted-4">
						{{ response.total }} Reviews
					</span>
				</div>
				<hr class="border-muted-1 w-100 mt-2">
			</div>

			<div class="d-flex flex-row flex-wrap align-items-center justify-content-between w-100">
				<div>
					<div class="input-group font-family-open-sans min-w-md-240px mb-0">
						<input 
							v-model="searchText"
							type="text" 
							class="form-control rounded-0 border-right-0 bg-secondary border-secondary" 
							placeholder="Search Reviews" 
							aria-label="Search Reviews"
						>
						<div class="input-group-append">
							<button class="btn bg-secondary border-left-0 rounded-0 border-secondary" type="button" @click.prevent="search()">
								<i class="fas fa-search text-muted-4"></i>
							</button>
						</div>
					</div>
				</div>
				<div>
					<div class="input-group font-family-open-sans min-w-md-300px mb-0 d-flex flex-row align-items-center">
						<label class="text-muted-4 mb-0 text-nowrap mr-3" for="sort-by">Sort By</label>
						<select
							v-model="sortBy"
							id="sort-by" 
							@change="getResults(currentPage)"
							name="sortBy" 
							class="form-control min-w-md-240px border-secondary text-center text-dark rounded-0 bg-secondary-"
						>
							<option value="most-recent">Most recent</option>
							<option value="popular-reviews">Popular reviews</option>
						</select>
					</div>
				</div>
			</div>

			<div v-if="response.total !== undefined && response.total !== null && response.total !== 0">

				<div class="py-4 mt-3">
					<product-review 
						v-for="review in reviews"
						:review-data="review" 
						:key="review.id"
					>
					</product-review>
				</div>
				
			</div>
			<div v-else>
				<div class="alert bg-secondary mt-4 rounded-0">
					<span class="lead font-family-open-sans">No customer reviews for this product.</span>
				</div>
			</div>

		</div>
		<div>
			<pagination :limit="3" :data="response" align="right" class="flex-wrap" @pagination-change-page="getResults"></pagination>
		</div>
	</div>
</template>

<script>
	export default {
		
		props: {
			product: {
				type: Object,
			},
			startPage: {
				type: Number,
				default: 1
			}
		},

		data() {
			return {
				searchText: '',
				sortBy: 'most-recent',
				reviews: {},
				response: {},
				currentPage: 0,
			}
		},

		watch: {
			product(newValue, oldValue) {
				this.currentPage = 1;
				this.getResults(this.currentPage)
			}
		},

		mounted() {
			let page = this.startPage > 0 ? this.startPage : 1;
			let self = this

			this.getResults(page)

			this.$root.$on('reviewCreated', function(review) {
				self.reviews.unshift(review)
			})
		},

		methods: {

			search() {
				if (this.searchText === '' || this.searchText.length  === 0) {
					return false;
				}

				this.getResults(1, {
					sortBy: this.sortBy,
					searchText: this.searchText
				})
			},

			getResults(page = 1, data = undefined, callback = undefined) {

				let self = this
				let $parent = $(this.$el)

				if (data === undefined) {
					data = {
						sortBy: this.sortBy,
					}
				}

				$parent.busyLoad('show')

				let vars = location.search
					.split('&')
					.filter(function(e) {
						return e.search('page=') === -1
					})
					.filter(function(e) {
						return e.length > 0;
					})

				let delimiter = vars.length > 0 ? '&' : '?'
				let link = '/review/' + this.product.id + delimiter + 'page=' + page;

				$.ajax({
					url: link,
					type: 'GET',
					dataType: 'json',
					data: data,
				})
				.done(function(response) {
					self.response = response
					self.reviews = response.data
					self.currentPage = response.current_page;
				})
				.always(function() {
					$parent.busyLoad('hide')

					try {
						if (callback !== undefined) {
							callback()
						}
					} catch (e) {

					}
				});
			}
		}
	}
</script>