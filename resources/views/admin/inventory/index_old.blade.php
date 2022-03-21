@extends('admin.layouts.master')

@section('content')
	@can('create', App\Inventory::class)
		@include('admin.inventory._add')
	@endcan

	<div class="box">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs nav-justified">
				<li class="{{ Request::has('tab') ? '' : 'active' }}"><a href="#active_inventory_tab" data-toggle="tab">
					<i class="fa fa-superpowers hidden-sm"></i>
					{{ trans('app.active_stocks') }}
				</a></li>
				<li class="{{ Request::input('tab') == 'inactive_listings' ? 'active' : '' }}"><a href="#inactive_listings_tab" data-toggle="tab">
					<i class="fa fa-bell-o hidden-sm"></i>
					{{ trans('app.inactive_stocks') }}
				</a></li>
				<li class="{{ Request::input('tab') == 'out_of_stock' ? 'active' : '' }}"><a href="#stock_out_tab" data-toggle="tab">
					<i class="fa fa-bullhorn hidden-sm"></i>
					{{ trans('app.out_of_stock') }}
				</a></li>
			</ul>
			<div class="tab-content">
			    <div class="tab-pane {{ Request::has('tab') ? '' : 'active' }}" id="active_inventory_tab">
					<table class="table table-hover table-2nd-no-sort">
						<thead>
							<tr>
								@can('massDelete', App\Inventory::class)
									<th class="massActionWrapper">
						                <!-- Check all button -->
										<div class="btn-group ">
											<button type="button" class="btn btn-xs btn-default checkbox-toggle">
												<i class="fa fa-square-o" data-toggle="tooltip" data-placement="top" title="{{ trans('app.select_all') }}"></i>
											</button>
											<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<span class="caret"></span>
												<span class="sr-only">{{ trans('app.toggle_dropdown') }}</span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
												<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
											</ul>
										</div>
									</th>
								@endcan
								<th>{{ trans('app.image') }}</th>
								<th>{{ trans('app.sku') }}</th>
								<th>{{ trans('app.title') }}</th>
								<th>{{ trans('app.condition') }}</th>
								<th>{{ trans('app.price') }} <small>( {{ trans('app.excl_tax') }} )</small> </th>
								<th>{{ trans('app.quantity') }}</th>
								<th>{{ trans('app.option') }}</th>
							</tr>
						</thead>
				        <tbody id="massSelectArea">
							@foreach($inventories->where('active', 1) as $inventory )
								<tr class="{{ $inventory->isLowQtt() ? 'danger' : '' }}">
								  	@can('massDelete', App\Inventory::class)
										<td><input id="{{ $inventory->id }}" type="checkbox" class="massCheck"></td>
								  	@endcan
									<td>
										<img src="{{ get_product_img_src($inventory, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
									</td>
									<td>{{ $inventory->sku }}</td>
									<td>{!! $inventory->title !!}</td>
									<td>{{ $inventory->condition }}</td>
									<td>
										@if(($inventory->offer_price > 0) && ($inventory->offer_end > \Carbon\Carbon::now()))
											@php
												$offer_price_help =
													trans('help.offer_starting_time') . ': ' .
													$inventory->offer_start->diffForHumans() . ' ' . trans('app.and') . ' ' .
													trans('help.offer_ending_time') . ': ' .
													$inventory->offer_end->diffForHumans();
											@endphp

											<small class="text-muted">{{ $inventory->sale_price }}</small><br/>
											{{ get_formated_currency($inventory->offer_price, 2) }}

											<small class="text-muted" data-toggle="tooltip" data-placement="top" title="{{ $offer_price_help }}"><sup><i class="fa fa-question"></i></sup></small>
										@else
											{{ get_formated_currency($inventory->sale_price, 2) }}
										@endif
									</td>
									<td>
										@if(Gate::allows('update', $inventory))
											<a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.editQtt', $inventory->id) }}" class="ajax-modal-btn qtt-{{$inventory->id}}" data-toggle="tooltip" data-placement="top" title="{{ trans('app.update') }}">
												{{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}
											</a>
										@else
											{{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}
										@endif
									</td>
									<td class="row-options">
										@can('view', $inventory)
											<a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.show', $inventory->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
										@endcan

										@can('update', $inventory)
											<a href="{{ route('admin.stock.inventory.edit', $inventory->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
										@endcan

										@can('delete', $inventory)
											{!! Form::open(['route' => ['admin.stock.inventory.trash', $inventory->id], 'method' => 'delete', 'class' => 'data-form']) !!}
												{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
											{!! Form::close() !!}
										@endcan
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

			    <div class="tab-pane {{ Request::input('tab') == 'inactive_listings' ? 'active' : '' }}" id="inactive_listings_tab">
					<table class="table table-hover table-2nd-no-sort">
						<thead>
							<tr>
								@can('massDelete', App\Inventory::class)
									<th class="massActionWrapper">
						                <!-- Check all button -->
										<div class="btn-group ">
											<button type="button" class="btn btn-xs btn-default checkbox-toggle">
												<i class="fa fa-square-o" data-toggle="tooltip" data-placement="top" title="{{ trans('app.select_all') }}"></i>
											</button>
											<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<span class="caret"></span>
												<span class="sr-only">{{ trans('app.toggle_dropdown') }}</span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
												<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
											</ul>
										</div>
									</th>
								@endcan
								<th>{{ trans('app.image') }}</th>
								<th>{{ trans('app.sku') }}</th>
								<th>{{ trans('app.title') }}</th>
								<th>{{ trans('app.condition') }}</th>
								<th>{{ trans('app.price') }} <small>( {{ trans('app.excl_tax') }} )</small> </th>
								<th>{{ trans('app.quantity') }}</th>
								<th>{{ trans('app.option') }}</th>
							</tr>
						</thead>
				        <tbody id="massSelectArea2">
							@foreach($inventories->where('active', 0) as $inventory )
								<tr class="{{ $inventory->isLowQtt() ? 'danger' : '' }}">
								  	@can('massDelete', App\Inventory::class)
										<td><input id="{{ $inventory->id }}" type="checkbox" class="massCheck"></td>
								  	@endcan
									<td>
										<img src="{{ get_product_img_src($inventory, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
									</td>
									<td>
										{{ $inventory->sku }}
										@if($inventory->inspection_status && $inventory->inInspection())
											<br/>
											{!! trans('inspector::lang.inspection') .': '. $inventory->getInspectionStatus() !!}
										@endif
									</td>
									<td>{!! $inventory->title !!}</td>
									<td>{{ $inventory->condition }}</td>
									<td>
										@if(($inventory->offer_price > 0) && ($inventory->offer_end > \Carbon\Carbon::now()))
											<?php $offer_price_help =
													trans('help.offer_starting_time') . ': ' .
													$inventory->offer_start->diffForHumans() . ' and ' .
													trans('help.offer_ending_time') . ': ' .
													$inventory->offer_end->diffForHumans(); ?>

											<small class="text-muted">{{ $inventory->sale_price }}</small><br/>
											{{ get_formated_currency($inventory->offer_price, 2) }}

											<small class="text-muted" data-toggle="tooltip" data-placement="top" title="{{ $offer_price_help }}"><sup><i class="fa fa-question"></i></sup></small>
										@else
											{{ get_formated_currency($inventory->sale_price, 2) }}
										@endif
									</td>
									<td>{{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}</td>
									<td class="row-options">
										@can('view', $inventory)
											<a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.show', $inventory->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
										@endcan

										@can('update', $inventory)
											<a href="{{ route('admin.stock.inventory.edit', $inventory->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
										@endcan

										@can('delete', $inventory)
											{!! Form::open(['route' => ['admin.stock.inventory.trash', $inventory->id], 'method' => 'delete', 'class' => 'data-form']) !!}
												{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
											{!! Form::close() !!}
										@endcan
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

			    <div class="tab-pane {{ Request::input('tab') == 'out_of_stock' ? 'active' : '' }}" id="stock_out_tab">
					<table class="table table-hover table-2nd-no-sort">
						<thead>
							<tr>
								@can('massDelete', App\Inventory::class)
									<th class="massActionWrapper">
						                <!-- Check all button -->
										<div class="btn-group ">
											<button type="button" class="btn btn-xs btn-default checkbox-toggle">
												<i class="fa fa-square-o" data-toggle="tooltip" data-placement="top" title="{{ trans('app.select_all') }}"></i>
											</button>
											<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<span class="caret"></span>
												<span class="sr-only">{{ trans('app.toggle_dropdown') }}</span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
												<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
											</ul>
										</div>
									</th>
								@endcan
								<th>{{ trans('app.image') }}</th>
								<th>{{ trans('app.sku') }}</th>
								<th>{{ trans('app.title') }}</th>
								<th>{{ trans('app.condition') }}</th>
								<th>{{ trans('app.price') }} <small>( {{ trans('app.excl_tax') }} )</small> </th>
								<th>{{ trans('app.quantity') }}</th>
								<th>{{ trans('app.option') }}</th>
							</tr>
						</thead>
						<tbody id="massSelectArea3">
							@foreach($inventories->where('stock_quantity', '<=', 0) as $inventory )
								<tr>
								  	@can('massDelete', App\Inventory::class)
										<td><input id="{{ $inventory->id }}" type="checkbox" class="massCheck"></td>
								  	@endcan
									<td>
										<img src="{{ get_product_img_src($inventory, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
									</td>
									<td>{{ $inventory->sku }}</td>
									<td>{!! $inventory->title !!}</td>
									<td>{{ $inventory->condition }}</td>
									<td>
										@if(($inventory->offer_price > 0) && ($inventory->offer_end > \Carbon\Carbon::now()))
											<?php $offer_price_help =
													trans('help.offer_starting_time') . ': ' .
													$inventory->offer_start->diffForHumans() . ' and ' .
													trans('help.offer_ending_time') . ': ' .
													$inventory->offer_end->diffForHumans(); ?>

											<small class="text-muted">{{ $inventory->sale_price }}</small><br/>
											{{ get_formated_currency($inventory->offer_price, 2) }}

											<small class="text-muted" data-toggle="tooltip" data-placement="top" title="{{ $offer_price_help }}"><sup><i class="fa fa-question"></i></sup></small>
										@else
											{{ get_formated_currency($inventory->sale_price, 2) }}
										@endif
									</td>
									<td>
										@if(Gate::allows('update', $inventory))
											<a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.editQtt', $inventory->id) }}" class="ajax-modal-btn qtt-{{$inventory->id}}" data-toggle="tooltip" data-placement="top" title="{{ trans('app.update') }}">
												{{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}
											</a>
										@else
											{{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}
										@endif
									</td>
									<td class="row-options">
										@can('view', $inventory)
											<a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.show', $inventory->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
										@endcan

										@can('update', $inventory)
											<a href="{{ route('admin.stock.inventory.edit', $inventory->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
										@endcan

										@can('delete', $inventory)
											{!! Form::open(['route' => ['admin.stock.inventory.trash', $inventory->id], 'method' => 'delete', 'class' => 'data-form']) !!}
												{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
											{!! Form::close() !!}
										@endcan
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div> <!-- /.box-body -->
	</div> <!-- /.box -->

	<div class="box collapsed-box">
		<div class="box-header with-border">
			<h3 class="box-title">
				@can('massDelete', App\Inventory::class)
					{!! Form::open(['route' => ['admin.stock.inventory.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
						{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm btn btn-default btn-flat ajax-silent', 'title' => trans('help.empty_trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'right']) !!}
					{!! Form::close() !!}
				@else
					<i class="fa fa-trash-o"></i>
				@endcan
				{{ trans('app.trash') }}
			</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div> <!-- /.box-header -->
		<div class="box-body">
			<table class="table table-hover table-no-sort">
				<thead>
					<tr>
						<th>{{ trans('app.image') }}</th>
						<th>{{ trans('app.sku') }}</th>
						<th>{{ trans('app.title') }}</th>
						<th>{{ trans('app.condition') }}</th>
						<th>{{ trans('app.price') }}</th>
						<th>{{ trans('app.quantity') }}</th>
						<th>{{ trans('app.deleted_at') }}</th>
						<th>{{ trans('app.option') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($trashes as $trash )
					<tr>
						<td>
						  	@if($trash->image)
								<img src="{{ get_storage_file_url($trash->image->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
							@else
								<img src="{{ get_storage_file_url(optional($trash->product->image)->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
							@endif
						</td>
						<td>{{ $trash->sku }}</td>
						<td>{{ $trash->title }}</td>
						<td>{{ $trash->condition }}</td>
						<td>{{ get_formated_currency($trash->sale_price, 2) }}</td>
						<td>{{ $trash->stock_quantity }}</td>
						<td>{{ $trash->deleted_at->diffForHumans() }}</td>
						<td class="row-options">
							@can('delete', $trash)
								<a href="{{ route('admin.stock.inventory.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

								{!! Form::open(['route' => ['admin.stock.inventory.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
									{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.delete_permanently'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
								{!! Form::close() !!}
							@endcan
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div> <!-- /.box-body -->
	</div> <!-- /.box -->
@endsection