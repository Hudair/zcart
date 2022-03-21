@extends('admin.layouts.master')

@section('content')
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">{{ trans('app.suppliers') }}</h3>
			<div class="box-tools pull-right">
				@can('create', App\Supplier::class)
					<a href="javascript:void(0)" data-link="{{ route('admin.stock.supplier.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_supplier') }}</a>
				@endcan
			</div>
		</div> <!-- /.box-header -->
		<div class="box-body">
			<table class="table table-hover table-2nd-no-sort">
				<thead>
					<tr>
						@can('massDelete', App\Supplier::class)
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
										<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.supplier.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
										<li><a href="javascript:void(0)" data-link="{{ route('admin.stock.supplier.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
									</ul>
								</div>
							</th>
						@endcan
						<th>{{ trans('app.image') }}</th>
						<th>{{ trans('app.name') }}</th>
						<th>{{ trans('app.contact_person') }}</th>
						<th>{{ trans('app.email') }}</th>
						<th>{{ trans('app.status') }}</th>
						<th>{{ trans('app.option') }}</th>
					</tr>
				</thead>
		        <tbody id="massSelectArea">
					@foreach($suppliers as $supplier )
					<tr>
					  	@can('massDelete', App\Supplier::class)
							<td><input id="{{ $supplier->id }}" type="checkbox" class="massCheck"></td>
					  	@endcan
						<td>
							<img src="{{ get_storage_file_url(optional($supplier->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
						</td>
						<td>{{ $supplier->name }}</td>
						<td>{{ $supplier->contact_person }}</td>
						<td>{{ $supplier->email }}</td>
						<td>{{ ($supplier->active) ? trans('app.active') : trans('app.inactive') }}</td>
						<td class="row-options">
							@can('view', $supplier)
								<a href="javascript:void(0)" data-link="{{ route('admin.stock.supplier.show', $supplier->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
							@endcan

							@can('update', $supplier)
								<a href="javascript:void(0)" data-link="{{ route('admin.stock.supplier.edit', $supplier->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;

								@if($supplier->primaryAddress)
									<a href="javascript:void(0)" data-link="{{ route('address.edit', $supplier->primaryAddress->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.update_address') }}" class="fa fa-map-marker"></i></a>&nbsp;
								@else
									<a href="javascript:void(0)" data-link="{{ route('address.create', ['supplier', $supplier->id]) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.add_address') }}" class="fa fa-plus-square-o"></i></a>&nbsp;
								@endif
							@endcan

							@can('delete', $supplier)
								{!! Form::open(['route' => ['admin.stock.supplier.trash', $supplier->id], 'method' => 'delete', 'class' => 'data-form']) !!}
									{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
								{!! Form::close() !!}
							@endcan
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div> <!-- /.box-body -->
	</div> <!-- /.box -->

	<div class="box collapsed-box">
		<div class="box-header with-border">
			<h3 class="box-title">
				@can('massDelete', App\Supplier::class)
					{!! Form::open(['route' => ['admin.stock.supplier.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
			<table class="table table-hover table-2nd-no-sort">
				<thead>
					<tr>
						<th>{{ trans('app.image') }}</th>
						<th>{{ trans('app.name') }}</th>
						<th>{{ trans('app.contact_person') }}</th>
						<th>{{ trans('app.email') }}</th>
						<th>{{ trans('app.deleted_at') }}</th>
						<th>{{ trans('app.option') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($trashes as $trash )
					<tr>
						<td>
							<img src="{{ get_storage_file_url(optional($trash->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
						</td>
						<td>{{ $trash->name }}</td>
						<td>{{ $trash->contact_person }}</td>
						<td>{{ $trash->email }}</td>
						<td>{{ $trash->deleted_at->diffForHumans() }}</td>
						<td class="row-options">
							@can('delete', $trash)
								<a href="{{ route('admin.stock.supplier.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

								{!! Form::open(['route' => ['admin.stock.supplier.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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