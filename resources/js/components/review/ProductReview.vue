<template>
	<div class="row border-bottom font-family-open-sans py-4 mb-3">

		<div class="col-7 col-md-2 order-md-1 order-1">
			<div class="d-flex flex-column mb-4 mb-md-0">
				<div class="mb-1 mb-md-3 pb-1 text-nowrap">{{ review.formatted_date }}</div>
				<star-rating 
					:increment="0.5" 
					:read-only="true" 
					:show-rating="false" 
					active-color="#FEB731" 
					:star-size="20" 
					:rating="review.grade"
				>
				</star-rating>
			</div>

		</div>

		<div class="col-12 col-md-7 order-md-2 order-3">
			<div class="position-relative" style="margin-bottom: -55px; z-index: 1;">
				<h1 class="mb-1 mb-md-3 font-weight-normal h6 text-dark-3">{{ review.name }}</h1>
				<h2 class="mb-2 mb-md-3 font-weight-normal h5 text-dark-2">{{ review.title }}</h2>
				<p class="lead">
					<p v-if="expended === false" class="font-size-1rem line-height-1-5 h-90px m-0">
						{{ truncatedDescription }}
					</p>
					<p v-else class="font-size-1rem line-height-1-5 m-0" :class="isTruncated ? 'min-h-160px h-100 pb-3' : ''">
						{{ review.description }}
					</p>
				</p>
			</div>

			<div class="pt-3" style="background: linear-gradient(180deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 100%); transform: rotate(-180deg); height: 40px; z-index: 2; position: relative;">
			</div>

			<div v-if="expended === false && isTruncated" class="bg-white" style="z-index: 2; position: relative;">
				<div class="text-center">
					<span @click.prevent="expended = true;" class="cursor-pointer pt-1 pb-1 d-block text-decoration-underline text-dark-2">Read More</span>
				</div>
			</div>
			<div v-else-if="expended === true && isTruncated" class="bg-white text-center">
				<span @click.prevent="expended = false;" class="cursor-pointer pt-1 pb-1 d-block text-decoration-underline text-pink">Read Less</span>
			</div>

			<div class="bg-white mt-3 py-2 d-flex flex-row align-items-center justify-content-start">
				<div class="mr-4 font-size-0-9rem">
					Was this helpful?
				</div>

				<div class="mr-3 d-flex flex-row align-items-center small">
					<div @click.prevent="update('like')" class="text-center mr-1 border border-muted-4 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px;">

						<i 
							:class="(updatedCounter === 'like' ? 'text-success' : 'text-dark-3') + (isUpdated ? '' : ' cursor-pointer text-hover-success')" 
							class="fas fa-thumbs-up"
						>		
						</i>

					</div>
					<span class="text-muted-4 small">
						{{ review.like_counter }}
					</span>
				</div>

				<div class="mr-3 d-flex flex-row align-items-center small">
					<div @click.prevent="update('dislike')" class="text-center mr-1 border border-muted-4 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px;">

						<i 
							:class="(updatedCounter === 'dislike' ? 'text-danger' : 'text-dark-3') + (isUpdated ? '' : ' cursor-pointer text-hover-danger')" 
							class="fas fa-thumbs-down"
						>		
						</i>

					</div>
					<span class="text-muted-4 small">
						{{ review.dislike_counter }}
					</span>
				</div>
				<div class="d-flex flex-row align-items-center small">
					<div @click.prevent="update('report')" class="text-center mr-1 border border-muted-4 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px;">

						<i 
							:class="(updatedCounter === 'report' ? 'text-danger' : 'text-dark-3') + (isUpdated ? '' : ' cursor-pointer text-hover-danger')" 
							class="fas fa-flag"
						>		
						</i>

					</div>
					<span class="text-muted-4 small">
						{{ review.report_counter }}
					</span>
				</div>
				
			</div>
		</div>

		<div class="col-5 col-md-3 order-md-3 order-2">
			<div class="d-flex flex-row flex-nowrap opacity-0-85">
				<div class="border border-muted-5 text-center rounded-0 mr-2" style="width: 20px; height: 20px;" :style="isRecommended ? 'padding: 1px;' : ''">
					<i :class="isRecommended ? 'text-highlight fa-check' : 'text-danger fa-times'" class="fas"></i>
				</div>
				<span class="text-dark-2">
					<span v-if="isRecommended">Recommend</span>
					<span v-else>Did not recommend</span>
					this product
				</span>
			</div>
		</div>

	</div>
</template>

<script>

	export default {
		props: [
			'reviewData'
		],

		data() {
			return {
				expended: false,
				updatedCounter: '',
				isUpdated: false,
				review: {}
			}
		},

		mounted() {
			this.review = JSON.parse(JSON.stringify(this.reviewData));
			Vue.set(this.review, 'description', this.review.description)

			this.loadLocalStorage();
		},

		methods: {

			loadLocalStorage() {

				let stored = localStorage.getItem('r-' + this.review.id + '-r')

				if (stored !== null) {
					this.isUpdated = true
				}

				this.updatedCounter = stored
			},

			update(action) {

				if (this.isUpdated) {
					toast('<span class="font-family-open-sans">You already rated this review</span>')
					return false;
				}

				let elm = this.$el
				let self = this

				$(elm).busyLoad('show')

				$.ajax({
					url: '/review/' + this.review.id,
					type: 'PUT',
					dataType: 'json',
					data: {
						action: action
					},
				})
				.done(function(response) {
					self.$set(self.review, 'like_counter', response.like_counter)
					self.$set(self.review, 'report_counter', response.report_counter)
					self.$set(self.review, 'dislike_counter', response.dislike_counter)
					self.review = response
					self.isUpdated = true;
					self.updatedCounter = action

					try {
						localStorage.setItem('r-' + self.review.id + '-r', action)
					} catch(e) {

					}
				})
				.fail(function(response) {
					try {
						alert(response.responseJSON.message);
					} catch (e) {
						alert('Something went wrong, try again later.')
					}
				})
				.always(function() {
					$(elm).busyLoad('hide')
				});
			}
		},

		computed: {
			isRecommended() {
				return this.review.grade >= 2.5
			},

			truncatedDescription() {
				try {
					return truncate(this.review.description, 345)
				} catch (e) {
					return '';
				}
			},

			isTruncated() {

				if (this.review.description !== undefined) {
					try {
						return this.review.description.split('').length >= 345
					} catch (e) {
						console.log(e)
						return false;
					}
				}
			}
		}
	}
</script>