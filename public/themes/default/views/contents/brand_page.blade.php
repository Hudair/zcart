<section>
  <div class="container mb-5">
    <div class="row">
        <div class="col-md-12">
			@if($products->count())
				@include('theme::contents.product_list')
			@else
				<p class="lead text-center my-5">
				  <span class="mb-4">@lang('theme.no_product_found')</span><br/>
				  <div class="mb-5 text-center">
					  <a href="{{ url('categories') }}" class="btn btn-primary btn-sm flat">@lang('theme.button.choose_from_categories')</a>
				  </div>
				</p>
			@endif
        </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>