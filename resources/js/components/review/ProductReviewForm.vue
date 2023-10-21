<template>
	<div class="mb-5 pb-5" v-if="reviewed === false">
		<div class="mb-4 d-flex flex-column h-100">

			<div class="mb-3">
				<h1 class="font-weight-bold text-dark mb-2 line-height-1-1 h5">
					Review This Product
				</h1>

				<p class="text-muted-3 lead font-size-1rem font-family-open-sans">
					Share your thoughts with other customers.
				</p>
			</div>

			<div class="mb-3">
				<p class="text-muted-3 font-family-open-sans mb-1">
					Overall rating
				</p>
				<star-rating 
					:increment="0.5" 
					:show-rating="false" 
					active-color="#FEB731" 
					:star-size="25" 
					:rating="4.5" 
					v-model="review.grade"
				>
				</star-rating>
			</div>

			<div class="max-w-500px mb-1">
				<hr class="border-muted-1">
			</div>

			<div class="max-w-500px">

				<div class="form-group">
					<label class="text-dark font-size-0-9rem mb-2">Add a headline</label>
					<input 
						:disabled="formSubmitted" 
						:style="formSubmitted ? 'opacity: 0.7;' : ''" 
						class="form-control rounded-0"
						v-model="review.title" 
						type="text" 
						max="99" 
						required 
					>
				</div>
			</div>

			<div class="d-flex flex-row w-100 mt-2 mb-3">
				<div class="w-100 max-w-500px mr-4">
					<div class="form-group">
						<label class="text-dark font-size-0-9rem mb-2">Add your name</label>
		            	<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control rounded-0" v-model="review.name" type="text" max="99" required>
		            </div>
				</div>
				<div class="w-100 max-w-500px">
					<div class="form-group mb-0">
						<label class="text-dark font-size-0-9rem mb-2">Add your email</label>
		            	<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control rounded-0" v-model="review.email" type="email" max="99" required>
		            </div>
				</div>
			</div>

			<hr class="border-muted-1 mt-0 mb-4 w-100">

			<div class="pt-2">
				<div class="form-group">
					<label class="text-dark font-size-0-9rem mb-2">Write your review</label>
					<textarea 
						:disabled="formSubmitted" 
						v-model="review.description" 
						class="form-control rounded-0 p-3" 
						cols="30" 
						rows="10" 
						maxlength="600" 
						placeholder="Review summary"
					>
					</textarea>
					<div class="font-family-open-sans mt-2 text-right">
						<span style="color: #F2994A;">{{ review.description.length }}</span> of <span class="text-dark">600</span> characters
					</div>
				</div>
			</div>

		    <div class="text-right mt-4">
		    	<button 
		    		class="btn btn-highlight px-4 py-2 text-uppercase btn-lg min-h-44px" 
		    		type="button" 
		    		@click.prevent="submit()" 
		    		:disabled="formSubmitted"
		    	>
					<small class="px-2 py-1 text-uppercase d-block font-weight-bold font-size-0-9rem">Submit review</small>
				</button>
		    </div>

			<div class="w-100">
				<div v-if="formSubmitted">
					<div class="alert alert-success mt-3">
						Thank you.
					</div>
				</div>

				<div v-if="hasErrors">
					<div class="alert alert-danger mt-3">

						<strong>{{ errors.message }}</strong>

						<ul class="mb-0">
				        	<li v-for="(items, index) in errors.errors">
				        		<p class="m-0" v-for="(item, key) in items">
				        			{{ item }}
				        		</p>
				        	</li>
			        	</ul>

					</div>
				</div>
			</div>

	    </div>
	</div>
</template>

<script>

	export default {

		props: [
			'product'
		],

		data(){
			return {
				review: {
					title: '',
					name: '',
					grade: 4.5,
					email: '',
					description: '',
				},
				formSubmitted: false,
				errors: [],
				reviewed: false,
			}
		},

		mounted() {
			let self = this
			self.reviewed = false;

			let item = localStorage.getItem('rp-' + this.product.id)

			if (item !== null && parseInt(item) === parseInt(this.product.id) && ! isNaN(parseInt(item))) {
				this.reviewed = true
			}
		},

		methods: {
			submit(){

				if ( !this.hasValidData) {
					alert('Name and email fields are required');
					return false;
				}

				let self = this;

				$.ajax({
					url: '/review/' + this.product.id,
					type: 'POST',
					dataType: 'json',
					data: this.review,
				})
				.done(function(response) {
					self.errors = []
					self.formSubmitted = true
					self.$root.$emit('reviewCreated', response)
					localStorage.setItem('rp-' + self.product.id, self.product.id)
					self.reviewed = true
				})
				.fail(function(response) {
					try {
						self.errors = response.responseJSON.errors
					} catch (e) {

					}
				});
			}
		},

		computed: {
			hasErrors(){
	            return this.errors.length !== 0
	        },

	        hasValidData(){
	        	var emailRegix = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	        	var isEmailValid = this.review.email.split('').length > 3 && emailRegix.test(this.review.email)

	        	if (! isEmailValid) {
	        		return false;
	        	}

	        	if (this.review.title.split('').length < 3) {
	        		return false;
	        	}

	        	if (this.review.name.split('').length < 3) {
	        		return false;
	        	}

	        	if (this.review.description.split('').length < 3) {
	        		return false;
	        	}

	        	return true;
	        }
		}
	}
</script>