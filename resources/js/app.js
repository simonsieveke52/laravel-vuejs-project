require('es6-promise').polyfill();
window.Vue = require('vue');
window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');
require('history.js/history.adapter.ender.js');
require('history.js/history.js');
require('bootstrap');
require('busy-load');
require('./shared');

/**
 * Remove remove carousel plugin if not used
 */
import VueLazyload from 'vue-lazyload'
import VueCarousel from 'vue-carousel';

import route from 'ziggy'
import { Ziggy } from './routes'

/**
 * Safari fix page caching
 */
jQuery(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
       window.location.reload();
    }
});

prepareAjaxHeader()

Vue.component(
    'star-rating',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "vue-star-rating" */
        'vue-star-rating'
    )
);

Vue.component(
    'product-page-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-page-component" */
        './components/product/ProductPageComponent'
    )
);

Vue.component(
    'product-cart-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-cart-component" */
        './components/product/ProductCartComponent'
    )
);

Vue.component(
    'product-images-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-images-component" */
        './components/product/ProductImagesComponent'
    )
);

Vue.component(
    'products-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "products-component" */
        './components/product/ProductsComponent'
    )
);

Vue.component(
    'product-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-component" */
        './components/product/ProductComponent'
    )
);

Vue.component(
    'product-modal-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-modal-component" */
        './components/product/ProductModalComponent'
    )
);

Vue.component(
    'product-subscribe-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-subscribe-component" */
        './components/product/ProductSubscribeComponent'
    )
);

Vue.component(
    'add-to-favorites',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "add-to-favorites" */
        './components/product/AddToFavorites'
    )
);

Vue.component(
    'favorites-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "favorites-component" */
        './components/product/FavoritesComponent'
    )
);

Vue.component(
    'add-to-cart-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "add-to-cart-component" */
        './components/cart/AddToCartComponent'
    )
);

Vue.component(
    'cart-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-component" */
        './components/cart/CartComponent'
    )
);

Vue.component(
    'cart-modal-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-modal-component" */
        './components/cart/CartModalComponent'
    )
);

Vue.component(
    'cart-item-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-item-component" */
        './components/cart/CartItemComponent'
    )
);

Vue.component(
    'cart-overview-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-overview-component" */
        './components/cart/CartOverviewComponent'
    )
);

Vue.component(
    'coupon-code-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "coupon-code-component" */
        './components/cart/CouponCodeComponent'
    )
);

Vue.component(
    'pagination', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "laravel-vue-pagination" */
        'laravel-vue-pagination'
    )
);

Vue.component(
    'money-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "money-component" */
        './components/general/MoneyComponent'
    )
);

Vue.component(
    'slider-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "slider-component" */
        './components/general/SliderComponent'
    )
);

Vue.component(
    'address-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "address-component" */
        './components/address/AddressComponent'
    )
);

Vue.component(
    'address-item-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "address-item-component" */
        './components/address/AddressItemComponent'
    )
);

Vue.component(
    'zipcode-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "zipcode-component" */
        './components/address/ZipcodeComponent'
    )
);

Vue.component(
    'category-nav-item', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "category-nav-item" */
        './components/category/CategoryNavItem'
    )
);

Vue.component(
    'city-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "city-component" */
        './components/address/CityComponent'
    )
);

Vue.component(
    'state-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "state-component" */
        './components/address/StateComponent'
    )
);

Vue.component(
    'api-shipping-options', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackchunkname: "api-shipping-options" */
        './components/shipping/ApiShippingOptions'
    )
);

Vue.component(
    'subscribe-form-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "subscribe-form-component" */
        './components/subscribe/SubscribeFormComponent'
    )
);

Vue.component(
    'quote-request-form', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "quote-request-form" */
        './components/quote/QuoteRequestForm'
    )
);

Vue.component(
    'contact-request-form', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "contact-request-form" */
        './components/contact/ContactRequestForm'
    )
);

Vue.component(
    'credit-card-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackchunkname: "credit-card-component" */
        './components/checkout/CreditCardComponent'
    )
);

Vue.component(
    'checkout-button-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "checkout-button-component" */
        './components/checkout/CheckoutButtonComponent'
    )
);

Vue.component(
    'checkout-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "checkout-component" */
        './components/checkout/CheckoutComponent'
    )
);

Vue.component(
    'address-validation-component', 
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "address-validation-component" */
        './components/address/AddressValidationComponent'
    )
);

Vue.component(
    'product-reviews-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackchunkname: "product-reviews-component" */
        './components/review/ProductReviewsComponent'
    )
);

Vue.component(
    'product-review-form',
    () => import(
        /* webpackPrefetch: true */
        /* webpackchunkname: "product-review-form" */
        './components/review/ProductReviewForm'
    )
);

Vue.component(
    'product-review',
    () => import(
        /* webpackPrefetch: true */
        /* webpackchunkname: "product-review" */
        './components/review/ProductReview'
    )
);

Vue.component(
    'quote-slide',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "quote-slide" */
        './components/quote/QuoteSlide'
    )
);

Vue.filter('currency', window.currency)
Vue.filter('currencyInt', window.currencyInt)
Vue.filter('truncate', window.truncate)

Vue.use(VueLazyload)
Vue.use(VueCarousel);

window.route = route;
window.Ziggy = Ziggy;

Vue.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    }
});

window.app = new Vue({
    el: '#app',
    mounted() {

        var useragent = navigator.userAgent.toLowerCase(); 

        if (useragent.indexOf("safari") === -1) {
            History.pushState({}, document.title, location.href)
        }

        let self = this

        $(function() {
    
            let mainDocumentTitle = document.title

            History.Adapter.bind(window, 'statechange', function(){

                var state = History.getState()

                try {

                    if (state.data.state === "pagination") {

                        self.$emit(state.data.event, state.data.page)

                    } else {

                        var item = $('.nav-item[data-slug="'+ state.data.slug +'"]')

                        document.title = mainDocumentTitle

                        if (state.data.slug === '') {

                            browseCategory(item, state)
                            return true;

                        } else if (item.length == 0) {
                            item = $('[data-slug="'+ state.data.slug +'"]')
                            if( item.length == 0 ){
                                location.reload()
                                return false
                            }
                        }

                        browseCategory(item, state)
                    }
                } catch (e) {
                }
            })

            setTimeout(function() {
                  $('[data-toggle="tooltip"]').tooltip()
            }, 500)
        })
    },
    methods: {
        toggleElement(elm) {
            if ($(elm).hasClass('open')) {
                $('body').css('overflow-y', 'auto')
                $(elm).removeClass('open')
            } else {
                $('body').css('overflow-y', 'hidden')
                $(elm).removeClass('open').addClass('open')
            } 
        }
    }
})

