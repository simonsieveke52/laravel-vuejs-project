<template>
	<div>
		<div class="form-group">
			<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Name" type="text" v-model="quote.name">
		</div>
		<div class="form-group">
			<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Email Address" type="email" v-model="quote.email">
		</div>
		<div class="form-group">
			<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Phone" type="tel" v-model="quote.phone">
		</div>
		<div class="form-group">
			<textarea :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Message" v-model="quote.content" cols="30" rows="3"></textarea>
		</div>
		<div class="form-group text-right">
			<button class="btn btn-highlight px-5 rounded-sides-pill-sm" @click="submit()" :disabled="! hasValidData || formSubmitted">Submit!</button>
		</div>

		<div v-if="formSubmitted">
			<div class="alert alert-success">
				Thank you, we'll have a quote ready in 20 business minutes.
			</div>
		</div>

		<div v-if="hasErrors">
			<div class="alert alert-danger">

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
</template>

<script>
	export default {
		data() {
			return {
				quote: {
					name: '',
					email: '',
					phone: '',
					content: ''
				},
				errors: [],
				formSubmitted: false
			}
		},

		methods: {
			submit() {
				let self = this

				$(self.$el).busyLoad('show')

				$.ajax({
					url: '/quote',
					type: 'POST',
					data: this.quote,
				})
				.done(function(response) {
					self.errors = [];
					self.formSubmitted = true
				})
				.fail(function(response) {
					self.errors = response.responseJSON
				})
				.always(function() {
					$(self.$el).busyLoad('hide')
				});
						
			}
		},

		computed: {

			hasErrors(){
	            return this.errors.length !== 0
	        },

	        hasValidData(){
	        	var emailRegix = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	        	var isEmailValid = this.quote.email.split('').length > 3 && emailRegix.test(this.quote.email)

	        	if (! isEmailValid) {
	        		return false;
	        	}

	        	if (this.quote.name.split('').length === 0) {
	        		return false;
	        	}

	        	if (this.quote.phone.split('').length < 9) {
	        		return false;
	        	}

	        	if (this.quote.content.split('').length <= 1) {
	        		return false;
	        	}

	        	return true;
	        }
		}
	}
</script>