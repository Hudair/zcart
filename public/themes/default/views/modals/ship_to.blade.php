<!-- Ship to selection Modal-->
<div class="modal auth-modal fade" id="shipToModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content flat">
        <div class="modal-header">
            <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
            {{ trans('theme.choose_your_location') }}
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => ['register'], 'data-toggle' => 'validator', 'id' => 'shipToForm']) !!}
                {{ Form::hidden('cart', Null, ['id' => 'cartinfo']) }} {{-- For the carts page --}}

                {{-- <button class="btn btn-block btn-lg flat btn-black space20" id="login_to_shipp_btn">{{ trans('app.login_to_choose_address') }}</button> --}}

                {{-- <div class="hr-sect">{{ trans('theme.or') }}</div> --}}

                <div class="row select-box-wrapper">
                    <div class="form-group col-md-12">
                        <label for="dispute_type_id">{{ trans('theme.country') }}:</label>
                        <select name="ship_to" id="shipTo_country" required="required">
                            @foreach($business_areas as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="row select-box-wrapper hidden" id="state_id_select_wrapper">
                    <div class="form-group col-md-12">
                        <label for="state_id">{{ trans('theme.placeholder.state') }}:</label>
                        <select name="state_id" id="shipTo_state" class="selectBoxIt"></select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <p class="space20 small"><i class="fas fa-info-circle"></i> {!! trans('theme.delivery_locations_info') !!}</p>

                <div class="col-5 pull-right">
                    <input class="btn btn-block btn-lg flat btn-primary" type="submit" value="{{ trans('theme.button.submit') }}">
                </div>
            {!! Form::close() !!}
        </div><!-- /.modal-body -->
        <div class="modal-footer"></div>
    </div><!-- /.modal-content -->
</div><!-- /#disputeOpenModal -->