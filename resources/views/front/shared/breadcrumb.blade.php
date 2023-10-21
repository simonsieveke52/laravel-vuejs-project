<div class="row py-2 mt-1 py-md-0 my-md-3">
    <div class="col-12">
        <section class="section section--breadcrumb">
            <div class="text-secondary-5">
                
                <a href="/">Home</a>

                @foreach ($parentCategories as $category)
                    <a class="text-nowrap" href="{{ route('category.filter', $category) }}">
                        <span class="px-1 px-sm-2">/</span> {{ $category->name }}
                    </a>
                @endforeach

                @yield('breadcrumb')                

            </div>
        </section>
    </div>
</div>