<div class="modal-dialog modal-md" role="document">
    <div class="modal-content flat">
        <div class="modal-header">
            <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
            {{ trans('theme.'.$action.'_items') }}
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => ['order.submitCancelRequest', $order], 'data-toggle' => 'validator']) !!}
                {!! Form::hidden('action', $action) !!}

                <div class="row select-box-wrapper">
                    <div class="form-group col-md-12">
                        <label for="cancellation_reason_id">@lang('theme.select_reason'):<sup>*</sup></label>
                        <select name="cancellation_reason_id" id="cancellation_reason_id" class="selectBoxIt" required="required">
                            <option value="">@lang('theme.select')</option>
                            @foreach($reasons as $id => $reason)
                                <option value="{{ $id }}">{{ $reason }}</option>
                            @endforeach
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="product_id">@lang('theme.select_product'):*</label>
                        <ul class="list-group" style="margin-bottom: 0">
                            <li class="list-group-item">
                                {!! Form::checkbox('all_items', Null, $order->cancellation && ! $order->cancellation->isPartial() ? 1 : Null, ['class' => 'i-check']) !!}
                                {{ trans('theme.all_items') }} <small class="text-muted indent10">({{ $order->quantity .' '. trans('theme.items') }})</small>
                                <span class="badge badge-primary badge-pill">{{ get_formated_currency(($order->grand_total), true, 2) }}</span>
                            </li>
                            @foreach($order->inventories as $item)
                                <li class="list-group-item">
                                    {!! Form::checkbox('items[]', $item->id, $order->cancellation && $order->cancellation->isItemInRequest($item->id) ? 1 : Null, ['class' => 'i-check']) !!}
                                    <img src="{{ get_storage_file_url(optional($item->image)->path, 'tiny') }}" alt="{{ $item->slug }}" title="{{ $item->slug }}" />

                                    <span class="small">{{ $item->pivot->item_description }} <small class="text-muted indent5">x {{ $item->pivot->quantity }}</small></span>

                                    <span class="badge badge-primary badge-pill">{{ get_formated_currency(($item->pivot->unit_price*$item->pivot->quantity), true, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group space30">
                    <label for="description">@lang('theme.description'):</label>
                    {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control flat', 'rows' => 1, 'placeholder' => trans('theme.placeholder.description')]) !!}
                    <div class="help-block with-errors"></div>
                </div>

                <div class="row">
                    <div class="col-7">
                        <div class="help-block">
                            <span data-toggle="tooltip" data-title="{!! trans('theme.order_'.$action.'_msg') !!}" data-placement="top">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ trans('theme.order_'.$action.'_msg_title') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-block flat btn-primary">{{ trans('theme.send_request') }}</button>
                    </div>
                </div>
            {!! Form::close() !!}
            <small class="help-block text-muted">* {{ trans('theme.help.required_fields') }}</small>
        </div><!-- /.modal-body -->
        <div class="modal-footer"></div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
