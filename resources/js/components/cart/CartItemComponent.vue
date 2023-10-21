<template>

	<div v-if="item.deleted !== true" @click.stop>

		<div class="d-flex flex-row justify-content-start align-items-center">

			<div class="w-100px">
				<img   
                    data-error="/storage/notfound.png"
                    data-loading="/images/px.png"
                    src="/images/px.png"
                    :data-src="'/images/px.png'"
                    v-lazy="'/storage/' + item.attributes.main_image"
                    class="img-fluid max-h-100px max-w-100px mx-auto"
                    :alt="item.name"
		        >
			</div>

		    <div class="mx-3 d-flex flex-column py-1">

		    	<a class="h5 mb-3 mt-1 d-block font-weight-light text-wrap text-dark-2" :href="route('product.show', item.attributes.slug !== undefined && item.attributes.slug !== null ? item.attributes.slug : item.attributes.id).url()">
		            {{ item.name | truncate(65) }}
		        </a>

		        <div class="d-flex flex-nowrap align-items-center justify-content-start">
		        	<div class="text-nowrap">
						<div class="btn-group max-w-100px">
			                <button 
			                	type="button" 
			                	@click="reduceQuantity"
			                	class="btn text-hover-darker rounded-0 px-0"
			                >
			                    <i class="fas fa-minus"></i>
			                </button>

			                <input 
			                	step="1" 
			                	type="text" 
			                	value="1" 
			                	class="form-control text-center border-0 font-size-1-3rem mb-0 p-0 w-44px" 
			                	v-model.number="quantity"
			                >

			                <button 
			                	type="button" 
			                	@click="raiseQuantity" 
			                	class="btn text-hover-darker rounded-0 px-0"
			                >
			                    <i class="fas fa-plus"></i>
			                </button>
			            </div>
		            </div>
	            	<div class="ml-3 pl-1 min-w-90px">
	            		<money-component 
		            		v-if="item.attributes.original_price != item.price && item.attributes.original_price > 0" 
							class="text-decoration-line-through text-muted font-weight-bolder font-family-open-sans mb-0 h6" 
							:value="originalTotal"
						>
						</money-component>
						&nbsp;
	            		<money-component class="text-dark font-weight-bolder font-family-open-sans mb-0 h4" :value="total"></money-component>
	            	</div>
	            </div>

		    </div>

		    <div class="ml-auto mr-0 px-2">
		    	<button class="btn text-hover-danger p-3" aria-label="remove item from cart" type="button" @click="deleteItem()">
	                <i class="fa fa-trash-alt"></i>
	            </button>
		    </div>

		</div>
		
	</div>
	
</template>
<script>
export default {

    props: ['item'],

    data() {
        return {
            ajaxRequest: null
        }
    },

    methods: {

        reduceQuantity() {
            if(this.quantity > 1) {
                this.quantity -= 1;
            }
        },

        raiseQuantity() {
            this.quantity += 1;
        },

        deleteItem(){

            if (! confirm('Are you sure you want to remove ' + this.item.name + ' from your cart?')) {
                return true;
            } 

            let self = this;

            $(self.$el).busyLoad('show')

            $.ajax({
                url: '/cart/' + this.item.id,
                type: 'DELETE'
            })
            .done(function() {
                $(self.$el).busyLoad('hide')
                self.$root.$emit('cartItemDeleted', self.item)
                self.item.deleted = true
            })
            .fail(function() {
                alert('Please refresh this page and try again')
                $(self.$el).busyLoad('hide')
            })
        },
    },

    computed: {


    	originalTotal() {
			return this.quantity * this.item.attributes.original_price
		},

		total() {
			return this.quantity * this.item.price
		},

        quantity: {
            
            // getter
            get: function () {
                return this.item.quantity
            },

            // setter
            set: function (newValue) {

                let oldValue = this.item.quantity;

                if (newValue > 0) {
                    this.item.quantity = newValue
                } else {
                    this.item.quantity = 1
                }

                try {
                    if (this.ajaxRequest !== null) {
                        this.ajaxRequest.abort();
                    }
                } catch (e) {

                }

                let self = this

                this.ajaxRequest = $.ajax({
                    url: '/cart/' + this.item.id,
                    type: 'PUT',
                    data: {
                        quantity: newValue
                    },
                })
                .done(function() {
                    self.$root.$emit('cartItemUpdated', self.item)
                })
                .fail(function(response) {
                    try {
                        alert(response.responseJSON.message)
                        self.item.quantity = oldValue;
                    } catch (e) {

                    }
                })

            }
        }
    }
};
</script>