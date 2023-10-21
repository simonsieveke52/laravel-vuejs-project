let Card = require("card");

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