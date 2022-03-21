<div class="modal-dialog modal-sm">
    <div class="modal-content">
        {!! Form::open(['route' => 'admin.promotion.bestFindsUnder.update', 'method' => 'PUT', 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            {{ trans('app.best_finds_under') }}
        </div>
        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('text', trans('app.price').'*') !!}
                <div class="input-group">
                  <span class="input-group-addon">{{ get_currency_symbol() }}</span>
                  {!! Form::number('price', $bestFinds ?? null, ['class' => 'form-control input-lg', 'step' => 'any', 'min' => '0','placeholder' => trans('app.price'), 'required']) !!}
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->