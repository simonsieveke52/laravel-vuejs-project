<template>

	<div ref="cart" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="close" @click="close()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body line-height-1-4 font-family-open-sans font-size-1rem">
                    <div class="row" @click.stop.prevent>
                        <div v-if="isEmpty" class="col-12">
                            <div class="alert alert-danger text-center">
                                Your cart is empty.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="list-group">
                                <cart-item-component 
                                    class="list-group-item p-0 border-secondary" 
                                    v-for="cartItem of availabeCartItems"
                                    :key="cartItem.id" 
                                    :item="cartItem"
                                >
                                </cart-item-component>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="! isEmpty" class="modal-footer d-flex flex-row align-items-center justify-content-between border-0">

                    <div class="font-weight-normal font-family-open-sans mb-0 h6">
                        <kbd class="py-1 text-dark-2 bg-white">
                            <span>SUBTOTAL</span>
                            <money-component v-if="cartOriginalSubtotal !== cartSubtotal" class="text-decoration-line-through text-muted font-weight-normal font-family-open-sans mb-0 h6 mr-n1" :value="cartOriginalSubtotal"></money-component>
                            <money-component class="text-dark font-weight-bolder font-family-open-sans mb-0 h5" :value="cartSubtotal"></money-component>
                        </kbd>
                    </div>

                    <div class="btn-group text-uppercase">
                        <button type="button" @click="close()" class="btn btn-secondary px-4 py-2 text-uppercase mt-3">
                            <small class="py-1 d-block">Close</small>
                        </button>
                        <button type="button" @click="openCheckoutLink()" class="btn btn-highlight px-4 py-2 text-uppercase mt-3">
                            <small class="py-1 d-block">Checkout</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</template>

<script>

export default {
    
    props: [
        'checkoutUrl'
    ],

    data() {
        return {
            isOpen: false,
            loaded: false,
            showSuccessAlert: false,
            cartItems : []
        }
    },

    mounted(){

        let self = this;

        this.load()

        this.$root.$on('openCart', function() {
            self.open()
        });

        this.$root.$on('cartItemAdded', cartItem => {

            toast('New item added to your cart.')
            this.showSuccessAlert = true

            // if item doesnt exist in cart
            // push it to cart items list
            // else return new car item 

            let itemExists = this.cartItems.filter(item => {
                return item.id === cartItem.id
            }).length !== 0

            if (itemExists) {
                this.cartItems = this.cartItems.map(item => {
                    if (item.id === cartItem.id) {
                        return cartItem
                    }
                    return item;
                })
            } else {
                this.cartItems.push(cartItem)
            }

            checkoutEcommerceEvent(this.availabeCartItems, 1);

            this.open()

            setTimeout(this.hideSuccessAlert, 3000)
        })
    },

    methods: {

        openCheckoutLink() {
            location.href = this.checkoutUrl;
        },

        load() {
            let self = this

            $.ajax({
                url: '/cart',
                type: 'GET'
            })
            .done(function(response) {
                self.loaded = true;
                self.cartItems = response.cartItems;
                self.$root.$emit('cartLoaded', response)
            })
            .fail(function() {
                self.cartItems = [];
            })
        },

        open() {
            if (this.loaded !== true) {
                this.load();
            }

            this.isOpen = true;
            let self = this;

            setTimeout(function() {
                $(self.$refs.cart).modal('show')
            }, 350)
        },

        close() {
            this.isOpen = false;
            $(this.$refs.cart).modal('hide')
        },

        hideSuccessAlert(){
            this.showSuccessAlert = false
        }
    },
    computed: {

        isEmpty(){
            return this.cartItems.filter(item => {
                return item.deleted === false
            }).length === 0
        },

        totalItems(){

            if (this.cartItems.length === 0) {
                return 0
            }

            return this.cartItems.map(item => {
                if (item.deleted === true) {
                    return 0;
                }

                return item.quantity
            })
            .reduce((accumulator, currentValue) => accumulator + currentValue)
        },

        availabeCartItems(){
            if (this.cartItems.length === 0) {
                return []
            }
            return this.cartItems.filter(item => {
                return item.deleted === false
            })
        },

        cartOriginalSubtotal(){

            if (this.cartItems.length === 0) {
                return 0
            }

            return this.cartItems.map(item => {
                if (item.deleted === true) {
                    return 0;
                }

                let price = (item.attributes.price !== undefined && item.attributes.price !== null) 
                    ? item.attributes.price
                    : item.price

                return price * item.quantity
            })
            .reduce((accumulator, currentValue) => accumulator + currentValue)
        },

        cartSubtotal(){

            if (this.cartItems.length === 0) {
                return 0
            }

            return this.cartItems.map(item => {
                if (item.deleted === true) {
                    return 0;
                }

                return item.price * item.quantity
            })
            .reduce((accumulator, currentValue) => accumulator + currentValue)
        }
    }
};

</script>