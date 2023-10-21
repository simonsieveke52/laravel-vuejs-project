<template>
	<div>
		<div class="mb-0">
            <div class="row">
                <div class="col-xl-12 mb-3">
                    <label class="text-dark font-size-0-9rem mb-2" for="cc_number">Credit card number</label>
                    <input v-model="number" name="cc_number" type="text" class="form-control form-control rounded-0 border bg-secondary-1 border-muted-6 " id="cc_number" required autocomplete="cc-number">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-xl-6 mb-3">
                    <label class="text-dark font-size-0-9rem mb-2" for="cc_name">Name on card</label>
                    <input v-model="name" name="cc_name" type="text" class="form-control form-control rounded-0 border bg-secondary-1 border-muted-6 " id="cc_name" required autocomplete="cc-name">
                    <small class="text-dark">Full name as displayed on card</small>
                </div>
                <div class="col-8 col-xl-4 mb-3">
                    <label class="text-dark font-size-0-9rem mb-2 text-nowrap" for="cc_expiration">Expiration (Month/Year)</label>

                    <div class="d-flex flex-row">
                    	<div class="d-flex flex-column flex-fill mr-2">
                            <input name="cc_expiration_month" type="number" step="1" min="1" max="12" class="form-control form-control rounded-0 border bg-secondary-1 border-muted-6" autocomplete="cc-exp-month" placeholder="Month" id="cc_expiration_month" required maxlength="2">
                        </div>
                        <div class="d-flex flex-column flex-fill">
                            <input v-model.number="expirationYear" name="cc_expiration_year" type="number" step="1" min="19" max="30" class="form-control form-control rounded-0 border bg-secondary-1 border-muted-6" autocomplete="cc-exp-year" placeholder="Year" id="cc_expiration_year" required maxlength="2">
                        </div>
                    </div>

                    <small v-if="! isValidMonth" class="text-danger mt-1 d-block">
                    	{{ expMonthError }}
                    </small>

                    <small v-if="! isValidYear" class="text-danger mt-1 d-block">
                    	{{ expYearError }}
                    </small>

                </div>
                <div class="col-4 col-xl-2 mb-3">
                    <label class="text-dark font-size-0-9rem mb-2" for="cc_cvv">CVV</label>
                    <input v-model="cvv" name="cc_cvv" type="text" class="form-control form-control rounded-0 border bg-secondary-1 border-muted-6 " id="cc_cvv" required autocomplete="cc-csc">
                </div>
            </div>
        </div>
	</div>
</template>

<script>

	var card = require("card");

	export default {
		data() {
			return {
				card: null,
				number: '',
				name: '',
				expirationMonth: '',
				expirationYear: '',
				cvv: '',

				expMonthError: '',
				expYearError: ''
			}
		},

		mounted() {
			this.$root.$emit('paymentMethodUpdated','credit_card');

			let card = new Card({

				form: 'form.jq-checkout-form',

				container: '.card-wrapper',

				width: 300,

				formSelectors: {
					numberInput: 'input#cc_number',
					expiryInput: 'input#cc_expiration_month, input#cc_expiration_year',
					nameInput: 'input#cc_name',
					cvcInput: 'input#cc_cvv'
				}
				
			});
		},

		computed: {

			isValidYear() {
				return (this.expirationYear > 19 && this.expirationYear < 30)
			},

			isValidMonth() {
				return (this.expirationMonth > 0 && this.expirationMonth < 13)
			},
		}
	}
</script>