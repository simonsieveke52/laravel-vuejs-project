<template>
	
	<div>

		<h4 v-if="shippingOptions.length > 0" class="d-flex justify-content-between align-items-center mb-3 w-100">
			<span class="text-dark font-weight-bold">{{ title }}</span>
		</h4>

		<div v-if="loading" class="alert alert-primary rounded-lg w-100">
			<div class="d-flex flex-row align-items-center justify-content-between">
				<div class="mr-3">
					<strong class="font-weight-semi-bold">Please wait, Looking for available shipping options</strong>
				</div>
				<div class="fa-2x">
				  	<i class="fas fa-spinner fa-pulse"></i>
				</div>
			</div>
		</div>

		<div v-else-if="shippingOptions.length === 0" class="alert alert-danger rounded-lg w-100">
			<p>No valid shipping methods found for your location.</p>
			<p class="mb-0 text-right">
				<a :href="route('guest.checkout.index').url()" class="btn btn-outline-danger">Edit my address</a>
			</p>
		</div>

		<transition name="slide-fade">
			<ul v-if="shippingOptions !== 0" class="list-group list-unstyled mb-3 w-100">
				<li class="mb-3" v-for="(carriers, title) in shippingOptions">

					<h4 class="h5" v-if="carriers.length > 0"><strong>{{ title }}</strong></h4>

					<label 
						v-for="(shipping, index) in carriers" :for="'shipping-' + shipping.serviceCode + '-' + index" v-if="carriers.length > 0" 
						class="align-items-center border-secondary list-group-item py-1 rounded-lg mb-1 list-group-item-secondary  list-group-item-action d-flex justify-content-between"
					>
						<span class="text-capitalize flex-fill text-dark flex-grow-1">{{ shipping.serviceName }}</span>
						<code class="flex-shrink-1 flex-fill text-right font-weight-bold py-0 text-dark">			
							{{ ( shipping.shipmentCost + shipping.otherCost ) | currency }}
						</code>
						<span class="flex-shrink-1 text-right ml-2 d-flex">
							<input 
								:id="'shipping-' + shipping.serviceCode + '-' + index"
								name="shipping_id"
								v-model="shippingMethod"
								:value="shipping"
								type="radio"
								@change="updateShipping()"
							>
						</span>
					</label>
				</li>
			</ul>
		</transition>

	</div>

</template>

<script>
	
	export default {

		props: [
			'title', 'selected'
		],

		data() {
	        return {
	            shippingMethod: 0,
	            shippingOptions: 0,
	            loading: true,
	            refreshAjaxRequest: null,
	            ajaxRequest: null
	        }
	    },

	    mounted(){

	    	let self = this;

	    	this.refresh()

	    	if (typeof(this.selected) === 'object' && this.selected.carrierCode !== undefined) {
	    		this.shippingMethod = this.selected
	    	}

	    	this.$root.$on('cartItemUpdated', function() {
	    		self.refresh()
	    	})
	    },

	    methods: {
	    	updateShipping(){

	    		let self = this;

	    		if (this.shippingMethod === 0) {
	    			return false;
	    		}

	    		try {

	    			let optionExists = false;

	    			$.each(this.shippingOptions, function(index, val) {
						var found = val.filter(function(v) {
							return v.serviceCode === self.shippingMethod.serviceCode
						})
						if (found.length > 0) {
							optionExists = true;
							return false;
						}
    				});

	    			if (optionExists === false) {
	    				return false;
	    			}
	    			
	    		} catch (e) {

	    		}

	    		if (this.ajaxRequest !== null) {
		    		this.ajaxRequest.abort()
	    		}

	    		$('.jq-confirm-checkout').attr('disabled', true)

	    		this.ajaxRequest = $.ajax({
	    			url: 'shipping',
	    			type: 'PUT',
	    			data: this.shippingMethod,
	    		})
	    		.done(function(response) {
	    			try {
	    				self.$root.$emit('shippingUpdated', self.shippingMethod);
	    			} catch (e) {

	    			}
	    		})
	    		.always(function() {
		    		
	    		});
	    	},

	    	refresh() {
	    		let self = this

	    		$('.jq-confirm-checkout').attr('disabled', true)

	    		if (this.refreshAjaxRequest !== null) {
	    			this.refreshAjaxRequest.abort()
	    		}

	    		this.refreshAjaxRequest = $.ajax({
	    			url: '/shipping',
	    			type: 'GET'
	    		})
	    		.done(function(response) {

	    			if (response.length === 0) {
	    				$('#credit-card-container').remove()
	    				self.shippingOptions = Object.assign({}, self.shippingOptions, [])
	    				self.shippingOptions = [];
	    			} else {
		    			try {
			    			self.shippingOptions = Object.assign({}, self.shippingOptions, response)
			    			self.updateShipping()
		    			} catch (e) {

		    			}
	    			}
	    		})
	    		.fail(function() {
	    			self.shippingOptions = []
	    		})
	    		.always(function() {
	    			self.loading = false
	    		})
	    	},
	    }
	}

</script>

<style>
	.slide-fade-enter-active {
	  	transition: all 1.66s;
	}
	.slide-fade-leave-active {
	  	transition: all 1.66s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}
	.slide-fade-enter, .slide-fade-leave-to {
	  	transform: translateX(-30px);
	  	opacity: 0;
	  	transition: all 2s;
	}
</style>