<h3 class="widget-title">{{ trans('theme.payment_options') }}</h3>
<div class="space20">
  @foreach($paymentMethods as $paymentMethod)
    @php
      $config = get_payment_config_info($paymentMethod->code, vendor_get_paid_directly() ? $shop : Null);
    @endphp

    {{-- Skip the payment option if not confirured --}}
    @continue(! $config || ! is_array($config) || ! $config['config'])

    @if($customer && ($paymentMethod->code == 'stripe') && $customer->hasBillingToken())
      <div class="form-group">
        <label>
          <input name="payment_method" value="saved_card" class="i-radio-blue payment-option" type="radio" data-info="{{$config['msg']}}" data-type="{{ $paymentMethod->type }}" required="required" {{ old('payment_method') ? '' : 'checked' }} /> @lang('theme.card'): <i class="fas fa-cc-{{ strtolower($customer->card_brand) }}"></i> ************{{$customer->card_last_four}}
        </label>
      </div>
    @endif

    <div class="form-group">
      <label>
        <input name="payment_method" value="{{ $paymentMethod->code }}" data-code="{{ $paymentMethod->code }}" class="i-radio-blue payment-option" type="radio" data-info="{{$config['msg']}}" data-type="{{ $paymentMethod->type }}" required="required" {{ old('payment_method') == $paymentMethod->code ? 'checked' : '' }}/> {{ $paymentMethod->code == 'stripe' ? trans('theme.credit_card') : $paymentMethod->name }}
      </label>
    </div>
  @endforeach
</div>

{{-- authorize-net --}}
@include('partials.authorizenet_card_form')

{{-- Stripe --}}
@include('partials.strip_card_form')

{{-- JRF Pay --}}
@if(is_incevio_package_loaded('jrfpay'))
  @include('partials.jrfpay_payment_form')
@endif

<p id="payment-instructions" class="text-info small space20">
  <i class="far fa-info-circle"></i>
  <span>@lang('theme.placeholder.select_payment_option')</span>
</p>

<div class="form-group mb-4">
  <div class="checkbox">
    <label>
      {!! Form::checkbox('agree', null, null, ['class' => 'i-check', 'required']) !!} {!! trans('theme.input_label.i_agree_with_terms', ['url' => route('page.open', \App\Page::PAGE_TNC_FOR_CUSTOMER)]) !!}
    </label>
  </div>
  <div class="help-block with-errors"></div>
</div>

<div id="submit-btn-block" class="clearfix space30" style="display: none;">
  <button id="pay-now-btn"  class="btn btn-primary btn-lg btn-block" type="submit">
    <small>
      <i class="far fa-shield"></i> <span id="pay-now-btn-txt">@lang('theme.button.checkout')</span>
    </small>
  </button>

  <a href="javascript:void(0)" id="paypal-express-btn" class="hide" type="submit">
    <img src="{{ asset(sys_image_path('payment-methods') . "paypal-express.png") }}" width="70%" alt="paypal express checkout" title="paypal-express" />
  </a>
</div>