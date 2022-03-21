<td>
	@can('massDelete', App\Inventory::class)
		<td><input id="{{ $inventory->id }}" type="checkbox" class="massCheck"></td>
	@endcan
</td>