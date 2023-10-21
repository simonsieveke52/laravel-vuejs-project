<div class="row col-12">
    @foreach ($relatedProducts as $product)
        @include('front.products.product', compact('product'))
    @endforeach
</div>
