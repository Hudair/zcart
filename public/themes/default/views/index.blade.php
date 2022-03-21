@extends('theme::layouts.main')

@section('content')
    <style type="text/css">
        section {
          margin: 0 0 35px 0;
        }
        @media screen and (max-width: 991px) {
          section {
            margin: 0 0 30px 0;
          }
        }
    </style>

    <!-- MAIN SLIDER -->
    @desktop
        @include('theme::sections.slider')
    @elsedesktop
        @include('theme::mobile.slider')
    @enddesktop

    <!-- banner grp one -->
    @if(! empty($banners['group_1']))
        @include('theme::sections.banners', ['banners' => $banners['group_1']])
    @endif

    <!-- Flash deal start -->
    @include('theme::sections.flash_deals')

    <!-- Trending start -->
    @include('theme::sections.trending_now')

    <!-- banner grp two -->
    @if(! empty($banners['group_2']))
        @include('theme::sections.banners', ['banners' => $banners['group_2']])
    @endif

   <!-- Deal of Day start -->
    @include('theme::sections.deal_of_the_day')

    <!-- banner grp three -->
    @if(! empty($banners['group_3']))
        @include('theme::sections.banners', ['banners' => $banners['group_3']])
    @endif

    <!-- Featured category stat -->
    @include('theme::sections.featured_category')

    <!-- Popular Product type start -->
    @include('theme::sections.popular')

    <!-- banner grp three -->
    @if(! empty($banners['group_4']))
        @include('theme::sections.banners', ['banners' => $banners['group_4']])
    @endif

    <!-- Bundle start -->
    {{--@include('theme::sections.bundle_offer')--}}

    <!-- feature-brand start -->
    @include('theme::sections.featured_brands')

    <!-- Recently Added -->
    @include('theme::sections.recently_added')

    <!-- banner grp four -->
    @if(! empty($banners['group_5']))
        @include('theme::sections.banners', ['banners' => $banners['group_5']])
    @endif

    <!-- Additional Items -->
    @include('theme::sections.additional_items')

    <!-- banner grp four -->
    @if(! empty($banners['group_6']))
        @include('theme::sections.banners', ['banners' => $banners['group_6']])
    @endif

    <!-- Best finds under $99 deals start -->
    @include('theme::sections.best_finds')

    <!-- best selling Now   -->
    {{--@include('theme::sections.best_selling')--}}

    <!-- Recently Viewed -->
    @include('theme::sections.recent_views')
@endsection

@section('scripts')
    <script src="{{ theme_asset_url('js/eislideshow.js') }}"></script>
    <script type="text/javascript">
        // Main slider
        $('#ei-slider').eislideshow({
            animation : 'center',
            autoplay : true,
            slideshow_interval : 5000,
        });

        // Trending now tabs
        $(function() {
          $('.feature__tabs a').click(function() {
            $('.feature__items-inner').slick('refresh');

            // Check for active
            $('.feature__tabs li').removeClass('active');
            $(this).parent().addClass('active');

            // Display active tab
            let currentTab = $(this).attr('href');
            $('.feature__items .feature__items-inner').hide();
            $(currentTab).show();

            return false;
          });
        });

        // Flashdeal
        let endTime = "{{isset($flashdeals) ? $flashdeals['end_time'] : 'NaN'}}";

        if (endTime) {
            const second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;

            let countDown = new Date(endTime).getTime(),
                x = setInterval(function() {
                    let now = new Date().getTime(),
                        distance = countDown - now;

                    $('.deal-counter-days').text(Math.floor(distance / (day)));
                    $('.deal-counter-hours').text(Math.floor((distance % (day)) / (hour)));
                    $('.deal-counter-minutes').text(Math.floor((distance % (hour)) / (minute)));
                    $('.deal-counter-seconds').text(Math.floor((distance % (minute)) / second));

                    //do something later when date is reached
                    if (distance < 0) {
                        let headline = document.getElementById("headline"),
                            countdown = document.getElementById("countdown"),
                            content = document.getElementById("content");

                        headline.innerText = "{{trans('theme.sale_over')}}";
                        countdown.style.display = "none";
                        content.style.display = "block";

                        clearInterval(x);
                    }
                    //seconds
                }, 0);
        }
    </script>
@endsection
