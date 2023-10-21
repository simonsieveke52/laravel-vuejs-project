<template>
    <div>
        <slot></slot>
        <div class="input-group font-family-open-sans min-w-md-240px mb-0">
            <input 
                v-model="code"
                :readonly="loading"
                type="text" 
                class="form-control rounded-0 border-right-0 bg-white border-muted-6" 
                placeholder="Promo code" 
                aria-label="Promo code"
            >
            <div class="input-group-append">
                <button class="btn bg-white border-left-0 rounded-0 border-muted-6" type="button" @click.prevent="applyCouponCode()">
                    Apply
                </button>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    data() {
        return {
            code: '',
            loading: false,
        }
    },
    
    methods: {
        applyCouponCode() {

            if (this.code == '') {
                return false;
            }

            let self = this
            this.loading = true

            $.ajax({
                url: 'cart/couponcode/' + this.code,
                type: 'GET',
                dataType: 'json'
            })
            .done(function(response) {
                if(response === 'code invalid') {
                    alert('We\'re sorry, that coupon code is not currently valid.')
                    return false;
                } else {
                    self.$root.$emit('couponCodeAdded', response);
                }
            })
            .fail(function() {
                alert('Invalid or expired promocode')
            })
            .always(function() {
                self.loading = false
            })
        }
    }
};

</script>