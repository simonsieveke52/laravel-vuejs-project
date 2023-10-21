<template>
	<div class="border-bottom py-4">
		<div class="mb-3">
			<product-review-form :product-id="productId"></product-review-form>
		</div>
		<div v-if="reviews.length > 0">
			<product-review 
				v-for="review in reviews" 
				:review="review" 
				:key="review.id"
				class="list-group-item bg-light rounded-sides-pill p-3 border py-4 mb-2"
			>
			</product-review>
		</div>
		<div v-else class="d-flex justify-content-start align-items-start flex-column rounded-sides-pill bg-light p-5 min-h-306px">
			No publicly available reviews yet.
		</div>
		<div class="mt-3">
			<slot></slot>
		</div>
	</div>
</template>

<script>

	export default {
		props: [
			'reviews', 'productId'
		]
	}
</script>