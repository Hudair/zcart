<td>
	@can('massDelete', App\Product::class)
		@unless($product->inventories_count > 0 && ! Auth::user()->isFromPlatform())
			<input id="{{ $product->id }}" type="checkbox" class="massCheck">
		@endunless
	@endcan
</td>