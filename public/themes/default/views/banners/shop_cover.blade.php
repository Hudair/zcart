<section class="store-cover-img-wrapper">
  	<div class="banner banner-o-hid cover-img-wrapper" style="background-image:url( {{ get_cover_img_src($shop, 'shop') }} );">
		<div class="page-cover-caption">
			<img src="{{ get_storage_file_url(optional($shop->logoImage)->path, 'thumbnail') }}" class="img-rounded">
			<h5 class="page-cover-title">
                <a href="#" data-toggle="modal" data-target="#shopReviewsModal">
	            	{!! $shop->getQualifiedName() !!}
                </a>
			</h5>
            <span class="small">
	            @include('theme::layouts.ratings', ['ratings' => $shop->feedbacks->avg('rating'), 'count' => $shop->feedbacks->count(), 'shop' => true])
	        </span>
			<p class="member-since small">{{ trans('theme.member_since') }}: {{ $shop->created_at->diffForHumans() }}</p>
		</div>
	</div>
</section>