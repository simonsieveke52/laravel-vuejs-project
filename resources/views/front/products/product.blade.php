@isset($featureFirst)
    <div class="grid--feature-first__block grid--feature-first__block--{{ $loop->iteration }} product-container mb-3">
@else
    @if ($viewType === 'list')
        <div class="col-12 product-container mb-3">    
    @elseif($viewType === 'grid-large')
        <div class="col-md-6 col-12 product-container mb-3">
    @else
        <div class="col-lg-4 col-md-6 col-12 product-container mb-3">
    @endif
@endisset
    <div class="card m-0 bg-grey-light pb-0 pt-3 h-100 {{ $viewType === 'list' ? ' border flex-row' : ' border-0' }}">
        @if($product->quantity < $product->quantity_per_case)
        <div class="position-absolute" style="right: 0;">
            <span class="badge badge-red text-white border-radius-0 py-1 px-2">Out of Stock</span>
        </div>
        @else
            @if($product->id % 2 > 0)
            <div class="position-absolute" style="right: 0; margin-bottom: -18px; z-index: 1;">
                <span class="badge badge-highlight text-white border-radius-0 py-1 px-2">Sale</span>
            </div>
            @endif
            <div class="position-absolute">
                <add-to-favorites 
                    context="short"
                    icon="fa-star"
                    defaultFilled="true"
                    product='@json($product)'>
                </add-to-favorites>
            </div>
        @endif
        <a href="{{ route('product.show', ['product' => $product->slug, 'category' => $currentCategory->slug ?? NULL ]) }}" class="card-img-top text-center{{ $viewType === 'list' ? ' col-4 ' : '' }}{{ !isset($featureFirst) ? ' d-flex' : '' }}">
            <img
                src="{{ asset($product->main_image) }}"
                class="img-fluid img-responsive {{ isset($featureFirst) ? 'h-100 ' : '' }}w-auto d-block m-auto max-size"
                alt="{{ $product->name }}"
            >
        </a>
        <div class="card-body px-0 h-100 d-flex flex-column">
            <div class="d-flex flex-column flex-grow-1 justify-content-center pt-2">
                @isset($product->brand)
                <a href="{{ route('brand.show',$product->brand) }}" class="text-secondary-5">
                    <small class="text-uppercase">{{ $product->brand['name'] }}</small>
                </a>
                @endisset
                <h3 class="card-title font-weight-bold h6 my-1 px-3">
                    <a href="{{ route('product.show', ['product' => $product->slug, 'category' => $currentCategory->slug ?? NULL]) }}" class="text-dark">
                        {{ $product->name }}
                    </a>
                </h3>
                <div class="my-2 d-flex pl-3">
                    <span class="text-highlight">
                        @if($product->id % 2 > 0)
                        <strike class="text-secondary-7"><small>{{ config('cart.currency_symbol') . number_format($product->price, 2) }}</small></strike>
                        @endif
                        {{ config('cart.currency_symbol') . number_format($product->price, 2) }}
                    </span>
                </div>
                @unless(isset($hide_description))
                <div class="card-text mb-3">
                    {!! $product->short_description !!}
                </div>
                @endunless
            </div>
            
            <div class="card-text pl-3 text-center d-flex flex-lg-row flex-md-column mt-auto mb-0 align-self-end justify-content-between w-100">
               @if($product->quantity > 0)
                <a 
                    @click.prevent="$emit('showProductChildren', {{ json_encode($product) }}, {{ json_encode($product->children) }})"
                    href="#"
                    class="btn bg-highlight text-white border-radius-0 px-2"
                >
                    Buy Now
                </a>
                @endif
            </div>
        </div>
    </div>
</div>