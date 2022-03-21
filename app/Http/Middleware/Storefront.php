<?php

namespace App\Http\Middleware;

use View;
use Auth;
use Closure;
use App\Helpers\ListHelper;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class Storefront
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check platform maintenance_mode
        if (config('system_settings.maintenance_mode')) {
            return response()->view('errors.503', [], 503);
        }

        //Supply important data to all views if not ajax request
        if (! $request->ajax()) {
            $expires = system_cache_remember_for();

            // View::share('active_announcement', ListHelper::activeAnnouncement());

            // $featured_categories = Cache::remember('featured_categories', $expires, function() {
            //     return ListHelper::hot_categories();
            // });
            // View::share('featured_categories', $featured_categories);

            $promotional_tagline = Cache::rememberForever('promotional_tagline', function() {
                return get_from_option_table('promotional_tagline', []);
            });

            View::share('promotional_tagline', $promotional_tagline);
            View::share('pages', ListHelper::pages(\App\Page::VISIBILITY_PUBLIC));
            View::share('all_categories', ListHelper::categoriesForTheme());
            View::share('search_category_list', ListHelper::search_categories());
            View::share('recently_viewed_items', ListHelper::recentlyViewedItems());
            View::share('cart_item_count', cart_item_count());
            View::share('global_announcement', get_global_announcement());

            // $languages = \App\Language::orderBy('order', 'asc')->active()->get();
        }

        return $next($request);
    }
}
