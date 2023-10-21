<template>
	<div>
		<div class="form-group">
			<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Name" type="text" v-model="contact.name">
		</div>
		<div class="form-group">
			<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Email Address" type="email" v-model="contact.email">
		</div>
		<div class="form-group">
			<input :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="Phone" type="tel" v-model="contact.phone">
		</div>
		<div class="form-group">
			<textarea :disabled="formSubmitted" :style="formSubmitted ? 'opacity: 0.7;' : ''" class="form-control" placeholder="" v-model="contact.content" cols="30" rows="3"></textarea>
		</div>
		<div class="form-group">
			<button class="btn btn-highlight shadow" @click="submit()" :disabled="! hasValidData || formSubmitted">Submit</button>
		</div>

		<div v-if="formSubmitted">
			<div class="alert alert-success">
				Thank you.
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
				contact: {
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
					url: '/contact',
					type: 'POST',
					data: this.contact,
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
	        	var isEmailValid = this.contact.email.split('').length > 3 && emailRegix.test(this.contact.email)

	        	if (! isEmailValid) {
	        		return false;
	        	}

	        	if (this.contact.name.split('').length === 0) {
	        		return false;
	        	}

	        	if (this.contact.phone.split('').length < 9) {
	        		return false;
	        	}

	        	if (this.contact.content.split('').length <= 10) {
	        		return false;
	        	}

	        	return true;
	        }
		}
	}
</script>