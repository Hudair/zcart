@extends('admin.layouts.master')

@section('content')
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">{{ trans('app.email_templates') }}</h3>
			<div class="box-tools pull-right">
				@can('create', App\EmailTemplate::class)
					<a href="javascript:void(0)" data-link="{{ route('admin.utility.emailTemplate.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_template') }}</a>
				@endcan
			</div>
		</div> <!-- /.box-header -->
		<div class="box-body">
			<table class="table table-hover table-no-sort">
				<thead>
					<tr>
						@can('massDelete', App\EmailTemplate::class)
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
										<li><a href="javascript:void(0)" data-link="{{ route('admin.utility.emailTemplate.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
										<li><a href="javascript:void(0)" data-link="{{ route('admin.utility.emailTemplate.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
									</ul>
								</div>
							</th>
						@endcan
						<th>{{ trans('app.name') }}</th>
						<th>{{ trans('app.subject') }}</th>
						<th>{{ trans('app.sender_email') }}</th>
						<th>{{ trans('app.type') }}</th>
						<th>{{ trans('app.template_for') }}</th>
						<th>{{ trans('app.option') }}</th>
					</tr>
				</thead>
		        <tbody id="massSelectArea">
					@foreach($templates as $template )
					<tr>
					  	@can('massDelete', App\EmailTemplate::class)
							<td><input id="{{ $template->id }}" type="checkbox" class="massCheck"></td>
					  	@endcan
						<td>{{ $template->name }}</td>
						<td>{{ $template->subject }}</td>
						<td>{{ $template->sender_email }}</td>
						<td>{{ $template->type }}</td>
						<td>{{ $template->template_for }}</td>
						<td class="row-options">
							@can('view', $template)
								<a href="javascript:void(0)" data-link="{{ route('admin.utility.emailTemplate.show', $template->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.preview') }}" class="fa fa-eye"></i></a>&nbsp;
							@endcan
							@can('update', $template)
								<a href="javascript:void(0)" data-link="{{ route('admin.utility.emailTemplate.edit', $template->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
							@endcan

							@can('delete', $template)
								{!! Form::open(['route' => ['admin.utility.emailTemplate.trash', $template->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
				@can('massDelete', App\EmailTemplate::class)
					{!! Form::open(['route' => ['admin.utility.emailTemplate.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
						<th>{{ trans('app.name') }}</th>
						<th>{{ trans('app.subject') }}</th>
						<th>{{ trans('app.sender_email') }}</th>
						<th>{{ trans('app.type') }}</th>
						<th>{{ trans('app.deleted_at') }}</th>
						<th>{{ trans('app.option') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($trashes as $trash )
					<tr>
						<td>{{ $trash->name }}</td>
						<td>{{ $trash->subject }}</td>
						<td>{{ $trash->sender_email }}</td>
						<td>{{ $trash->type }}</td>
						<td>{{ $trash->deleted_at->diffForHumans() }}</td>
						<td class="row-options">
							@can('delete', $trash)
								<a href="{{ route('admin.utility.emailTemplate.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

								{!! Form::open(['route' => ['admin.utility.emailTemplate.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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