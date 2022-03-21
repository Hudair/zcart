<section>
  <div class="container full-width">
      <div class="row">
        @if($products->count())

          <div class="col-md-3 bg-light">
            @include('theme::contents.product_list_sidebar_filters')
          </div><!-- /.col-sm-2 -->
          <div class="col-md-9" style="padding-left: 15px;">

            @include('theme::contents.product_list')

            @if(config('system_settings.show_seo_info_to_frontend'))
              <div class="clearfix space20"></div>
              <span class="lead">{!! $category->meta_title !!}</span>
              <p>{!! $category->meta_description !!}</p>
              <div class="clearfix space20"></div>
            @endif

          </div><!-- /.col-sm-10 -->
        @else
          <div class="col-12">
            <p class="lead text-center mt-5">
              <span class="space50">@lang('theme.no_product_found')</span><br/>
              <div class="space50 text-center">
                <a href="{{ url('categories') }}" class="btn btn-primary btn-sm flat">@lang('theme.button.shop_from_other_categories')</a>
              </div>
            </p>
          </div>
        @endif
      </div><!-- /.row -->
  </div><!-- /.container -->
</section>