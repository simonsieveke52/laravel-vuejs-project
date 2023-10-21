
<cart-modal-component checkout-url="{{ auth()->guard('customer')->check() ? route('checkout.index') :  route('guest.checkout.index') }}"></cart-modal-component>

<div class="modal fade" id="search-modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('product.search') }}">
                    <div class="input-group rounded-lg flex-nowrap m-0">
                        <input
                            value="{{ request()->input('keyword') }}"
                            type="text"
                            name="keyword"
                            class="form-control border-right-0 main-navbar-seach-field min-w-sm-175px min-w-lg-200px"
                            placeholder="Search"
                            aria-label="SEARCH BY PRODUCT"
                            aria-describedby="search-button"
                        >
                        <div class="input-group-append border border-highlight rounded-right">
                            <button aria-label="search" type="submit" class="btn-highlight border border-highlight border-left-0 font-weight-normal px-3" id="search-button">
                                <i class="fas d-block d-xl-none fa-search"></i>
                                <span class="d-none d-xl-block">Search</span>
                            </button>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>