<div class="modal-dialog modal-md">
    <div class="modal-content">
        {!! Form::open(['route' => 'admin.order.order.create', 'method' => 'get', 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        	{{ trans('app.form.search') }}
        </div>
        <div class="modal-body">
            @include('admin.partials._search_customer')
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.form.proceed'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->