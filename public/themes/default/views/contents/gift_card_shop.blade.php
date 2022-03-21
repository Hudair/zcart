<div class="row product-list">
    @foreach($giftCards as $item)
        <div class="col-md-3">
            <div class="product product-grid-view sc-product-item">
                <input name="product_price" value="{{ get_formated_decimal($item->current_sale_price(), true, 2) }}" type="hidden"/>
                <input name="product_id" value="{{ $item->id }}" type="hidden"/>
                <input name="shop_id" value="{{ $item->id }}" type="hidden"/>

                {{-- <ul class="product-info-labels">
                    @if($item->orders_count >= config('system.popular.hot_item.sell_count', 3))
                        <li>@lang('theme.hot_item')</li>
                    @endif
                    @if($item->free_shipping == 1)
                        <li>@lang('theme.free_shipping')</li>
                    @endif
                    @if($item->stuff_pick == 1)
                        <li>@lang('theme.stuff_pick')</li>
                    @endif
                    @if($item->hasOffer())
                        <li>@lang('theme.percent_off', ['value' => get_percentage_of($item->sale_price, $item->offer_price)])</li>
                    @endif
                </ul> --}}

                <div class="product-img-wrap">
                    <img class="product-img-primary" src="{{ get_product_img_src($item, 'medium') }}" data-name="product_image" alt="{{ $item->title }}" title="{{ $item->title }}"/>

                    <img class="product-img-alt" src="{{ get_product_img_src($item, 'medium', 'alt') }}" alt="{{ $item->title }}" title="{{ $item->title }}"/>

                    <a class="product-link" href="{{ route('show.product', $item->slug) }}" data-name="product_link"></a>
                </div>

                <div class="product-actions btn-group">
                    <a class="btn btn-default flat add-to-wishlist" href="javascript:void(0)" data-link="{{ route('wishlist.add', $item) }}">
                        <i class="far fa-heart" data-toggle="tooltip" title="@lang('theme.button.add_to_wishlist')"></i> <span>@lang('theme.button.add_to_wishlist')</span>
                    </a>

                    <a class="btn btn-default flat itemQuickView" href="{{ route('quickView.product', $item->slug) }}">
                        <i class="far fa-external-link" data-toggle="tooltip" title="@lang('theme.button.quick_view')"></i> <span>@lang('theme.button.quick_view')</span>
                    </a>

                    <a class="btn btn-primary flat sc-add-to-cart" data-link="#">
                        <i class="fas fa-shopping-cart"></i> @lang('theme.button.add_to_cart')
                    </a>
                </div>

                <div class="product-info">
                    @include('theme::layouts.ratings', ['ratings' => $item->feedbacks->avg('rating'), 'count' => $item->feedbacks_count])

                    <a href="{{ route('show.product', $item->slug) }}" class="product-info-title" data-name="product_name">{{ $item->title }}</a>

                    <div class="product-info-availability">
                        @lang('theme.availability'): <span>{{ ($item->stock_quantity > 0) ? trans('theme.in_stock') : trans('theme.out_of_stock') }}</span>
                    </div>

                    @include('theme::layouts.pricing', ['item' => $item])

                    <div class="product-info-desc"> {{ $item->description }} </div>
                    {{-- <div class="product-info-desc" data-name="product_description"> {{ $item->description }} </div> --}}
                    <ul class="product-info-feature-list">
                        <li>{{ $item->condition }}</li>
                        {{-- <li>{{ $product->product_id }}</li> --}}
                    </ul>
                </div><!-- /.product-info -->
            </div><!-- /.product -->
        </div><!-- /.col-md-* -->
    @endforeach
</div><!-- /.row .product-list -->

<div class="sep"></div>

<div class="row pagenav-wrapper">
  {{ $giftCards->links('theme::layouts.pagination') }}
</div><!-- /.row .pagenav-wrapper -->
