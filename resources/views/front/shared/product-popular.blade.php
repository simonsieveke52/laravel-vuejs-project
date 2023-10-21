<div class="border-0 card {{ $loop->iteration === 3 ? 'mx-0' : '' }} px-0 bg-grey-light">
    <div class="add-to-favorites-wrapper px-2">
        <add-to-favorites 
            product='@json($product)'>
        </add-to-favorites>
    </div>
    <a href="{{ route('product.show', $product) }}" class="d-flex justify-content-center ">
        <img src="images/fme-logo-product.png" class="card-img-top img-fluid max-w-h-200px border rounded" alt="Card image cap">
    </a>
    <div class="card-body text-center pt-0 pb-1 mb-1 px-0">
        <h5 class="card-title min-h-100 font-weight-bold pt-3 pb-0 mb-0">
            {{ $product->name }}
        </h5>
        <p class="card-text text-highlight py-0 mt-2 mb-3 h5 text-grow-3 font-weight-bolder">
            ${{number_format($product->price, 2) }}
        </p>
        <p class="card-text d-flex justify-content-between pt-0">
            <a href="{{ route('product.show', $product) }}" class="btn bg-highlight text-white border-radius-0 px-2">Learn More</a>
            <a href="{{ route('product.show', $product) }}" class="btn border-radius-0 px-2 bg-highlight text-white">Quick View</a>
        </p>
    </div>
</div>