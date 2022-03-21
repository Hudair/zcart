<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\CategoryGroup;
use App\CategorySubGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryGroupResource;
use App\Http\Resources\CategorySubGroupResource;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $sub_group = Null)
    {
        if ($sub_group) {
            $categories = Category::where('category_sub_group_id', $sub_group)->with(['coverImage','coverImage'])->active()->get();
        }
        else {
            $categories = Category::with(['coverImage','coverImage'])->active()->get();
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryGroup()
    {
        $categories = CategoryGroup::with(['coverImage'])->active()->get();

        return CategoryGroupResource::collection($categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categorySubGroup(Request $request, $group = Null)
    {
        if ($group) {
            $categories = CategorySubGroup::where('category_group_id', $group)
            ->with(['coverImage'])->active()->get();
        }
        else {
            $categories = CategorySubGroup::with(['coverImage'])->active()->get();
        }

        return CategorySubGroupResource::collection($categories);
    }
}