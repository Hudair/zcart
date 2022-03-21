<!-- CONTENT SECTION -->
<section>
  <div class="container text-center mb-5 mt-0">
    <div class="row thumb-lists">
	    @foreach($shops as $shop)
			<div class="col-6 col-md-2 my-5">
				<span class="vertical-helper"></span>
				<a href="{{ route('show.store', $shop->slug) }}" class="">
					<img src="{{ get_storage_file_url(optional($shop->logoImage)->path, 'logo_lg') }}" >
					<p class="thumb-list-name">{!! $shop->getQualifiedName() !!}</p>
				</a>
			</div>
		@endforeach
    </div><!-- /.row -->

	<div class="row pagenav-wrapper mt-4">
	    {{ $shops->links('theme::layouts.pagination') }}
	</div><!-- /.row .pagenav-wrapper -->
  </div><!-- /.container -->
</section>
<!-- END CONTENT SECTION -->