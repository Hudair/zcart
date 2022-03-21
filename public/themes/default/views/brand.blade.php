@extends('theme::layouts.main')

@section('content')
    <!-- BRAND COVER IMAGE -->
    @include('theme::banners.brand_cover', ['brand' => $brand])

    <!-- CONTENT SECTION -->
    @include('theme::contents.brand_page')

    <!-- BROWSING ITEMS -->
    @include('theme::sections.recent_views')
@endsection