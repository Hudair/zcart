@extends('theme::auth.layout')

@section('content')
<div class="box login-box-body">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('theme.password_reset') }}</h3>
    </div> <!-- /.box-header -->
    <div class="box-body">
        {!! Form::open(['url' => 'customer/password/reset', 'id' => 'form', 'data-toggle' => 'validator']) !!}
            {!! Form::hidden('token', $token) !!}
            <div class="form-group has-feedback">
                {!! Form::email('email', null, ['class' => 'form-control input-lg', 'placeholder' => trans('theme.placeholder.valid_email'), 'required']) !!}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
              {!! Form::password('password', ['class' => 'form-control input-lg', 'id' => 'password', 'placeholder' => trans('theme.placeholder.password'), 'data-minlength' => '6', 'required']) !!}
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
              {!! Form::password('password_confirmation', ['class' => 'form-control input-lg', 'placeholder' => trans('theme.placeholder.confirm_password'), 'data-match' => '#password', 'required']) !!}
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              <div class="help-block with-errors"></div>
            </div>

            {!! Form::submit(trans('theme.password_reset'), ['class' => 'btn btn-block btn-lg btn-flat btn-primary']) !!}
        {!! Form::close() !!}
        <a href="{{ route('customer.login') }}" class="btn btn-link">{{ trans('theme.button.login') }}</a>
    </div>
</div>
@endsection
