<template>
	<div class="row align-items-center">
        <div class="col-12 d-none" style="opacity: 0.8;">
			<div v-if="hasSubmittedForm === true" class="text-white">
				<slot></slot>
			</div>
			<div v-else class="d-flex flex-column flex-md-row align-items-center justify-content-center">
				<div class="px-2 mb-2 mb-md-0">
					<img src="/images/subscribe.png" class="img-fluid max-w-125px">
				</div>
				<div class="px-3 mb-2 mb-md-0">
					<p class="mb-0 font-weight-light text-white">Stay up to date with the latest products and specials!</p>
				</div>
				<div class="px-2">
					<div class="input-group flex-nowrap rounded-lg border border-muted-1">
			            <input :readonly="loading" type="email" name="email" placeholder="Enter email address" class="form-control border-0 bg-highlight text-white" v-model="subscriber.email">
			            <div class="input-group-append">
				            <button @click.stop="subscribe()" class="btn btn-highlight text-muted-1" :disabled="loading">
				            	<span v-if="loading === false">
					            	<i class="fas fa-check"></i>
					            </span>
					            <span v-else>
									<i class="fas fa-spinner fa-pulse mr-2"></i>
								</span>
				            </button>
						</div>
			        </div>
			        <ul class="list-unstyled mb-0 mt-1 text-left" v-if="hasErrors">
			        	<li v-for="(items, index) in errors">
			        		<p class="m-0 small text-danger font-family-open-sans" v-for="item in items">
			        			- {{ item }}
			        		</p>
			        	</li>
		        	</ul>
				</div>
			</div>
        </div>
	</div>
</template>

<script>

	export default {
		data(){
			return {
				loading: false,
				subscriber: {
					email: ''
				},
				hasSubmittedForm: false,
				errors: []
			}
		},

		mounted() {
			$(this.$el).find('.col-12.d-none').removeClass('d-none')
		},

		methods: {
			subscribe(){

				if (this.loading) {
					return
				}

				if ( !this.hasValidData) {
					alert('Name and email fields are required');
					return false;
				}

				this.loading = true

				let self = this;

				$.ajax({
					url: '/api/subscribe',
					type: 'POST',
					dataType: 'json',
					data: this.subscriber,
				})
				.done(function(response) {
					try {
						self.errors = []
						self.hasSubmittedForm = true
					} catch (e) {
					}
				})
				.always(function(response) {
					try {
						self.loading = false
						self.errors = response.responseJSON.errors
					} catch (e) {
					}
				});
			}
		},

		computed: {
			hasErrors(){
	            try {
	            	return this.errors.length !== 0
	            } catch (e) {
	            	return true;
	            }
	        },

	        hasValidData(){
	        	var emailRegix = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	        	return this.subscriber.email.split('').length > 3 && emailRegix.test(this.subscriber.email)
	        }
		}
	}
</script>

<style scoped>
	::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
		color: #E0E0E0;
		opacity: 1; /* Firefox */
	}

	:-ms-input-placeholder { /* Internet Explorer 10-11 */
		color: #E0E0E0;
	}

	::-ms-input-placeholder { /* Microsoft Edge */
		color: #E0E0E0;
	}
</style>