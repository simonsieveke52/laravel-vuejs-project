<template>
    <span 
        class="d-inline-block text-decoration-none"
        @click.stop
    >
        <span
            @click.stop="open()"
            :class="cssClasses + ' cursor-pointer'"
        >
            <span class="fa-layers fa-fw m-0 position-relative d-flex flex-column-reverse w-100 positon-relative">

                <img   
                    src="/images/cart.png"
                    class="img-fluid position-relative max-w-175px max-w-md-215px"
		        >
                
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

    </span>
</template>

<script>

export default {
    
    props: [
        'cssClasses',
    ],

    data() {
        return {
            isOpen: false,
            loaded: false,
            cartItems : []
        }
    },

    mounted(){
        let self = this;

        this.$root.$on('cartLoaded', function(cartResponse) {
            self.cartItems = cartResponse.cartItems
        })
    },

    methods: {
        open() {
            this.$root.$emit('openCart')
        }
    },

    computed: {
        isEmpty() {
            return this.cartItems.length === 0
        },

        totalItems() {
            return this.cartItems.length
        },
    }
};

</script>