<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('meta')

    @if(url('/') !== request()->url())
        <link href='https://fonts.googleapis.com/css?family=Roboto:500,300,700,400italic,400' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    @endif

    <!-- Main custom css -->
    <link href="{{ theme_asset_url('css/style.css')}}" media="screen" rel="stylesheet">
    {{-- <link href="https://dl.dropbox.com/s/zyu2rzm3r1limec/resposive.css" media="screen" rel="stylesheet"> --}}

    @if(config('active_locales')->firstWhere('code', App::getLocale())->rtl)
       <link href="{{ theme_asset_url('css/rtl.css')}}" media="screen" rel="stylesheet">
    @endif

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   {{-- <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->--}}
</head>
<body class="{{ config('active_locales')->firstWhere('code', App::getLocale())->rtl ? 'rtl' : 'ltr'}}">
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Wrapper start -->
    <div class="wrapper">
        <!-- Header start -->
        <header class="header">
            <!-- VALIDATION ERRORS -->
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>{{ trans('theme.error') }}!</strong> {{ trans('messages.input_error') }}<br><br>
                  <ul class="list-group">
                      @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                      @endforeach
                  </ul>
                </div>
            @endif

            @if(Session::has('global_announcement'))
                <div class="header__enouncement">
                    @if(session('global_announcement')->action_url)
                        <div class="container">
                            <div class="header__enouncement-inner">
                                <p>{!! session('global_announcement')->parsed_body !!}</p>
                                <a href="{{ session('global_announcement')->action_url }}" >{{ session('global_announcement')->action_text }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Primary Menu -->
            @include('theme::nav.main')

            <!-- Mobile Menu -->
            @include('theme::nav.mobile')
        </header>

        <div class="close-sidebar">
            <strong><i class="fal fa-times"></i></strong>
        </div>

        <div id="content-wrapper">
            @yield('content')
        </div>
        {{--@unless(Auth::guard('customer')->check())
            @include('theme::auth.modals')
        @endunless--}}
        <div id="loading">
            <img id="loading-image" src="{{ theme_asset_url('img/loading.gif') }}" alt="busy...">
        </div>
        <!-- Quick View Modal-->
        <div id="quickViewModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>

        <!-- my Dynamic Modal-->
        <div id="myDynamicModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>

        <!-- footer start -->
        @include('theme::nav.footer')
    </div>
    <!-- Wrapper end -->

    <!-- MODALS -->
    @unless(Auth::guard('customer')->check())
        @include('theme::auth.modals')
    @endunless

    <script src="{{ theme_asset_url('js/app.js') }}"></script>
    {{-- <script src="https://dl.dropbox.com/s/nqdfqqp5fper8no/script.js"></script> --}}

    @include('theme::notifications')

    <!-- AppJS -->
    @include('theme::scripts.appjs')

    <!-- Page Scripts -->
    @yield('scripts')

</body>
</html>