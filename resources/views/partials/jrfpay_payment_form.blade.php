<div id="jrfpay-form" class="jrfpay-form" style="display: none;">
  <hr class="style4 muted">
  <div id="jrfpay-errors" class="hide"></div>
  <div class="form-group">
    {!! Form::text('jrfpay_customer_id', Null, ['id' => 'jrfpay-id', 'class' => 'form-control jrfpay-request-field flat', 'placeholder' => trans('jrfpay::lang.jrfpay_customer_id')]) !!}
    <div class="help-block with-errors"></div>
  </div>
  <div class="form-group">
    {!! Form::text('jrfpay_customer_pin', Null, ['id' => 'jrfpay-pin', 'class' => 'form-control jrfpay-request-field flat', 'placeholder' => trans('jrfpay::lang.jrfpay_customer_pin')]) !!}
    <div class="help-block with-errors"></div>
  </div>

  <div id="jrfpay-otp" class="form-group" style="display: none;">
    {!! Form::text('jrfpay_otp', Null, ['id' => 'jrfpay-otp-code', 'class' => 'form-control flat', 'placeholder' => trans('jrfpay::lang.jrfpay_otp')]) !!}
    <div class="help-block with-errors"></div>
  </div>
</div> <!-- /#jrfpay-cc-form -->