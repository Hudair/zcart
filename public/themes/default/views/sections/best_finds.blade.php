@if(count($deals_under))
    <section>
        <div class="best-deals">
            <div class="container">
                <div class="best-deals__inner">
                    <div class="best-deals__header">
                        <div class="sell-header sell-header--bold">
                            <div class="sell-header__title">
                                <h2>{{trans('theme.best_find_under', ['amount' => get_from_option_table('best_finds_under')])}}</h2>
                            </div>
                            <div class="header-line">
                                <span></span>
                            </div>
                            <div class="best-deal__arrow">
                                <ul>
                                    <li><button class="left-arrow slider-arrow slick-arrow best-deal-left"><i class="fal fa-chevron-left"></i></button></li>
                                    <li><button class="right-arrow slider-arrow slick-arrow best-deal-right"><i class="fal fa-chevron-right"></i></button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="best-deals__items">
                        <div class="best-deals__items-inner">

                            @include('theme::partials._product_horizontal', ['products' => $deals_under, 'title' => 1, 'ratings' => 1, 'hover' => 1])

                        {{--<div class="best-deals__items-box box">
                                <div class="best-deals__items-img box-img">
                                    <img src="images/p11.png" alt="">
                                </div>
                                <div class="best-deals__items-price box-price">
                                    <p class="best-deals__items-price-new box-price-new">$700</p>
                                    <p class="best-deals__items-price-old box-price-old">$1400</p>
                                </div>
                        </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif