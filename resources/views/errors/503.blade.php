<!DOCTYPE html>
<html>
    <head>
        <title>@lang('app.marketplace_down')</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 52px;
                margin-top: 20px;
                margin-bottom: 40px;
            }
            .brand-logo {
              max-width: 140px;
              max-height: 50px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                @if( Storage::exists('logo.png') )
                    <img src="{{ get_storage_file_url('logo.png', 'full') }}" class="brand-logo" alt="{{ trans('app.logo') }}" title="{{ trans('app.logo') }}">
                @else
                  <img src="https://placehold.it/140x60/eee?text={{ get_platform_title() }}" class="brand-logo" alt="LOGO" title="LOGO" />
                @endif
                <div class="title">@lang('app.marketplace_down')</div>
            </div>
        </div>
    </body>
</html>
