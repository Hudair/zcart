<div class="modal fade" id="disputeOpenModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content flat">
            <div class="modal-header">
                <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                {{ trans('theme.button.open_dispute') }}
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['dispute.save', $order], 'data-toggle' => 'validator']) !!}
                <div class="row select-box-wrapper">
                    <div class="form-group col-md-12">
                        <label for="dispute_type_id">@lang('theme.select_reason'):<sup>*</sup></label>
                        <select name="dispute_type_id" id="dispute_type_id" class="selectBoxIt" required="required">
                            <option value="">@lang('theme.select')</option>
                            @foreach($types as $id => $type)
                                <option value="{{ $id }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="row space10">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-6 nopadding">
                                <label for="goods_received">@lang('theme.goods_received')?<sup>*</sup></label>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-3">
                                <label>
                                  <input name="order_received" value="1" class="i-radio-blue" type="radio" required="required"/> {{ trans('theme.yes') }}
                                </label>
                            </div>
                            <div class="col-3">
                                <label>
                                  <input name="order_received" value="0" class="i-radio-blue" type="radio" required="required"/> {{ trans('theme.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row select-box-wrapper space10 hidden" id="select_disputed_item">
                    <div class="form-group col-md-12">
                        <label for="product_id">@lang('theme.select_product'):*</label>
                        <select name="product_id" id="product_id" class="selectBoxIt">
                            <option value="">@lang('theme.select')</option>
                            <option value="all">@lang('theme.all_items')</option>
                            @foreach($order->inventories as $id => $item)
                                <option value="{{ $item->product_id }}">
                                    {{ $item->pivot->item_description }} (@lang('theme.unit_price'): {{ get_formated_currency($item->pivot->unit_price, true, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="refund_amount">@lang('theme.refund_amount'):*</label>
                    <div class="input-group">
                        <span class="input-group-addon flat">{{ config('system_settings.currency_symbol') ?: '$' }}</span>
                        {!! Form::number('refund_amount' , 0, ['id' => 'refund_amount', 'class' => 'form-control flat', 'step' => 'any', 'max' => $order->grand_total, 'placeholder' => trans('theme.placeholder.refund_amount'), 'required']) !!}
                    </div>
                    <div class="help-block with-errors">
                        @php
                          $refunded_amt = $order->refundedSum();
                        @endphp

                        @if($refunded_amt > 0)
                          <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><i class="fas fa-warning"></i> {{ trans('theme.alert') }}!</h4>
                            {!! trans('theme.help.order_refunded', ['amount' => get_formated_currency($refunded_amt, true, 2), 'total' => get_formated_currency($order->grand_total, true, 2)]) !!}
                          </div>
                        @else
                          <small>{!! trans('theme.help.customer_paid', ['amount' => get_formated_currency($order->grand_total, true, 2)]) !!}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">@lang('theme.description'):<sup>*</sup></label>
                    {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control flat', 'rows' => 3, 'placeholder' => trans('theme.placeholder.description'), 'required']) !!}
                    <div class="help-block with-errors"></div>
                </div>

                <div class="row">
                    <div class="col-7">
                        <div class="form-group hidden" id="return_goods_checkbox">
                            <label>
                              <input name="return_goods" value="1" class="i-check-blue" id="return_goods" type="checkbox"/> {{ trans('theme.return_goods') }}
                            </label>
                        </div>
                        <div class="help-block with-errors small"><span class="text-info hidden" id="return_goods_help_txt"><i class="fas fa-info-circle"></i> @lang('theme.help.return_goods_help_txt')</span></div>
                    </div>
                    <div class="col-5">
                        <input class="btn btn-block flat btn-primary" type="submit" value="{{ trans('theme.button.open_dispute') }}">
                    </div>
                </div>
                {!! Form::close() !!}
                <small class="help-block text-muted">* {{ trans('theme.help.required_fields') }}</small>
            </div><!-- /.modal-body -->
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /#disputeOpenModal -->