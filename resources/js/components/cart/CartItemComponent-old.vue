<template>
    <div class="px-1 py-1 pl-sm-2 pr-sm-1 py-sm-2" v-if="item.deleted !== true" @click.parent.stop>
        <div class="d-flex flex-row flex-nowrap flex-nowrap justify-content-center align-items-center">

            <div class="d-flex align-items-center justify-content-center bg-white h-auto w-auto p-1 rounded-lg" style="min-width: 105px; min-height: 105px;">
                <img   
                    @click.stop.prevent
                    :src="item.attributes.main_image" 
                    class="img-fluid max-h-100px max-w-100px mx-auto" 
                    :alt="item.name" 
                >
            </div>

            <div class="flex-column py-2 px-2 w-100 align-items-start justify-content-start d-flex">
                <a class="mb-2 d-block text-wrap text-left" :href="route('product.show', item.id).url()">
                    {{ item.name | truncate(88) }}
                </a>
                <div class="btn-group">
                    <button type="button" @click.prevent="reduceQuantity" class="btn btn-green">
                        -
                    </button>
                    <input @click.prevent type="text" value="1" class="form-control text-center min-w-44px max-w-70px rounded-0" v-model.number="quantity">
                    <button type="button" @click.prevent="raiseQuantity" class="btn btn-green">
                        +
                    </button>
                </div>
            </div>

            <div class="py-2 text-dark px-2" @click.stop.prevent>
                <div v-if="item.attributes !== undefined && item.attributes.has_map_price" class="d-flex flex-column align-items-end">
                    <small v-if="item.attributes.original_price != item.price && item.attributes.original_price > 0">
                        <money-component 
                            class="text-decoration-line-through" 
                            :value="item.attributes.original_price"
                        >
                        </money-component>
                    </small>
                    <money-component class="text-dark h4 font-weight-bold mb-0" :value="item.price"></money-component>
                </div>
                <div v-else>
                    <money-component class="text-dark h4 font-weight-bold mb-0" :value="item.price"></money-component>
                </div>

                <div class="small" v-if="item.attributes !== undefined && item.attributes.is_free_shipping">
                    <span class="text-danger small" v-if="item.attributes.free_shipping_option == 'Free 2-day'">+ FREE 2-DAY SHIPPING</span>
                    <span class="text-danger small" v-else>+ FREE SHIPPING</span>
                </div>

            </div>

            <button class="btn text-hover-danger" aria-label="remove item from cart" @click.stop.prevent="deleteItem()">
                <i class="fa fa-trash-alt"></i>
            </button>

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