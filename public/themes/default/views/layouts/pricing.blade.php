<div class="product-info-price">
	@if($item->hasOffer())
	    <span class="old-price">{!! get_formated_price($item->sale_price, config('system_settings.decimals', 2)) !!}</span>
    @endif

    <span class="product-info-price-new">
    	{!! get_formated_price($item->current_sale_price(), config('system_settings.decimals', 2)) !!}
    </span>
</div>

{{-- <ul class="product-info-feature-labels">
    @foreach($item->getLabels() as $label)
        <li>{!! $label !!}</li>
    @endforeach
</ul> --}}
