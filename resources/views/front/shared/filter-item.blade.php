<li>
    <input
        data-type="{{ $filterType }}"
        data-val="{{ $filterText }}"
        name="{{ $filterType }}[{{ $filterVal }}]"
        id="{{ $filterType }}-{{ $filterVal }}"
        type="checkbox"
        {{ strpos(request()->get($filterType), \Illuminate\Support\Str::slug($filterText)) > -1 ? 'checked=checked' : '' }}
        value="{{ strpos(request()->get($filterType), \Illuminate\Support\Str::slug($filterText)) ? 1 : 0 }}"
        >
    <label for="{{ $filterType}}-{{ $filterVal }}">
        {{ $filterText }}
    </label>
</li>
