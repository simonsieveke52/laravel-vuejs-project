<div class="offcanvas-collapse bg-white" id="main-navbar">

	<div class="d-block d-md-none mb-3">
		<div class="modal-header d-flex align-items-center border-bottom-0 bg-secondary">
			<div style="width: 80%;">
                <form method="GET" action="{{ route('product.search') }}">
                	<div class="input-group font-family-open-sans min-w-md-240px mb-0">
						<input 
							value="{{ request()->keyword }}"
							type="text" 
							name="keyword"
							class="form-control rounded-0 border-right-0 bg-white border-muted-6" 
							placeholder="Search products" 
							aria-label="Search products"
						>
						<div class="input-group-append">
							<button class="btn bg-white border-left-0 rounded-0 border-muted-6" type="submit">
								<i class="fas fa-search text-muted-4"></i>
							</button>
						</div>
					</div>
                </form>
            </div>

            <button type="button" class="close" aria-label="Close" @click.prevent="toggleElement('#main-navbar')" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
        </div>
	</div>

	<div class="px-3 px-md-0">
		<div class="navbar--categories">
			<form class="category-filter" id="categories-filter-container">

				<div id="browse-by-category">

					@php
						$currentCategory = $category;
					@endphp
				    
				    <ul class="navbar-nav mr-auto d-block position-relative list-unstyled mt-md-0 d-block position-relative">

				        @foreach($categories as $category)

				        	@if( isset($parentCategoriesIds) && in_array( $category->id, $parentCategoriesIds ) )

					        	@php
									$childrens = $category->children()
										->withDepth()
										->where('status', true)
										->remember(config('default-variables.cache_life_time'))
										->orderBy('name', 'asc')
										->get()
								@endphp


				        		<li class="mb-1 d-block position-relative @if( $loop->last ) border-bottom-0 @endif pl-{{ $category->depth }}">
									<a 
										data-slug="{{ $category->slug }}"
										data-depth="{{ $category->depth }}"
										href="{{ route('category.filter', $category) }}" 
										class="nav-item d-block position-relative jq-category-click @if( is_active(route('category.filter', $category)) ) font-weight-bold @endif"
										>
										<span class="py-3 font-size-1rem mb-0 text-dark-5 d-flex flex-row flex-nowrap align-items-center justify-content-between text-uppercase">

											<span>{{ $category->name }}</span>

											<span class="small text-soft-dark">
												@if( is_active(route('category.filter', $category)) || in_array($category->id, $childrens->pluck('parent_id')->toArray()) )
													<i class="fas fa-minus"></i>
												@else
													<i class="fas fa-plus"></i>
												@endif
											</span>

										</span>
									</a>
								</li>

								<ul class="list-unstyled">

									@if( ! $childrens->isEmpty() )

										@foreach( $childrens as &$children )

											@php
												$children->depth += 1
											@endphp

											@include('front.categories.category-nav', [
												'parentCategoriesIds' => $parentCategoriesIds,
												'category' => $children
											])

										@endforeach

									@endif

								</ul>
								
				        	@else

					        	<li class="mb-1 d-block position-relative @if( $loop->last ) border-bottom-0 @endif pl-{{ $category->depth > 0 ? $category->depth - 1 : 0 }}">

									<a 
										data-slug="{{ $category->slug }}"
										data-depth="{{ $category->depth }}"
										href="{{ route('category.filter', $category) }}" 
										class="nav-item jq-category-click @if( is_active(route('category.filter', $category)) ) font-weight-bold @endif"
									>
										<span class="py-3 font-size-1rem mb-0 text-dark-5 d-flex flex-row flex-nowrap align-items-center justify-content-between text-uppercase">
											<span>{{ $category->name }}</span>
											<span class="small text-soft-dark">
												<i class="fas fa-plus"></i>
											</span>
										</span>
									</a>

								</li>	

				        	@endif

				        	<hr class="border border-secondary">
				            
				        @endforeach

				    </ul>
				</div>

			</form>
		</div>
	</div>
</div>