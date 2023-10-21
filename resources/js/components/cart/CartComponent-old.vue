<template>
    <span 
        class="d-inline-block text-decoration-none"
        @click.stop.prevent
    >
        <span
            @click.stop.prevent="open()"
            :class="cssClasses + ' cursor-pointer'"
        >
            <span class="fa-layers fa-fw m-0 position-relative d-flex flex-column-reverse w-100 positon-relative">

                <i class="fas fa-shopping-bag"></i>
                
                <span
                    v-if="! isEmpty"
                    class="fa-layers-counter text-muted-2 text-hover-darker d-flex flex-column align-items-center justify-content-center position-absolute small"
                    style="top: -14px; right: 0; left: 0;"
                >
                    <span class="d-flex font-size-0-7rem">
                        {{ totalItems }}
                    </span>
                </span>

            </span>
        </span>

        <div class="shadow-lg font-size-1rem line-height-1-5 offcanvas-collapse " :class="isOpen == true ? 'open' : ''">
            <div class="px-1 h-100" style="overflow: auto;">
                <div class="modal-header border-bottom-0 text-right d-block w-100" @click.prevent>
                    <button class="btn position-relative btn-danger text-white rounded-circle shadow-lg" style="padding: 0px 6.5px;" @click="close()" aria-label="Close cart">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container-fluid mb-5 w-100 d-flex flex-column">

                    <div class="row">
                        
                        <div class="col-12" v-if="showSuccessAlert">
                            <div class="alert alert-success mb-1 text-left">
                                New item added to your cart.
                            </div>
                        </div>

                        <div v-if="isEmpty" class="col-12 pt-3 pb-1" @click.stop.prevent>
                            <div class="alert alert-danger mb-1 text-left">
                                Your cart is empty.
                            </div>
                        </div>

                        <div class="col-12 text-right mb-3" v-else>
                            <div class="list-group pt-0 pb-3">
                                <div v-for="cartItem of availabeCartItems" class="list-group-item list-group-item-secondary border-secondary shadow-sm list-group-item-action p-0 mb-1 rounded-lg">
                                    <cart-item-component :item="cartItem"></cart-item-component>
                                </div>
                            </div>

                            <div class="py-3" @click.stop.prevent>
                                <div class="text-right h5 mb-0">
                                    Subtotal : <span class="font-weight-bold">{{ cartSubtotal | currency }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-right">
                            <div class="btn-group">
                                <a class="btn btn-secondary" href="#" @click.prevent="close()">Continue shopping</a>
                                <a v-if="! isEmpty" class="btn btn-highlight text-white" @click.prevent="openCheckoutLink()">Checkout</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </span>
</template>

<script>

export default {
    
    props: [
        'cssClasses',
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

        if (isVisible(this.$el) || isMobile()) {
            this.load()
        }

        this.$root.$on('openCart', function() {
            self.open()
        });

        this.$root.$on('cartItemAdded', cartItem => {

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
            $('body').css('overflow-y', 'hidden')
        },

        close() {
            this.isOpen = false;
            $('body').css('overflow-y', 'auto')
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

<style lang="scss" scoped>
    a div{
        cursor: auto;
    }
    .offcanvas-collapse {
        z-index: 9999;
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        width: 33%;
        max-width: 600px;
        overflow-y: auto;
        background-color: #fff;
        transition: -webkit-transform .3s ease-in-out;
        transition: transform .3s ease-in-out;
        transition: transform .3s ease-in-out, -webkit-transform .3s ease-in-out;
        -webkit-transform: translateX(130%);
        transform: translateX(130%);

        // lg
        @media (max-width: 1286px) {
            width: 45%;
        }

        // lg
        @media (max-width: 1042) {
            width: 50%;
        } 

        // sm
        @media (max-width: 768px) {
            width: 60%;
        } 
        
        @media (max-width: 668px) {
            width: 68%;
        } 
        
        @media (max-width: 568px) {
            width: 80%;
        } 
        
        @media (max-width: 468px) {
            width: 100%;
        } 

        &.open {
            -webkit-transform: translateX(0%);
            transform: translateX(0%);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }
    }
</style>