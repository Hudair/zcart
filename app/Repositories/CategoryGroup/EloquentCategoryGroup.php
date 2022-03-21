<?php

namespace App\Repositories\CategoryGroup;

use App\CategoryGroup;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;

class EloquentCategoryGroup extends EloquentRepository implements BaseRepository, CategoryGroupRepository
{
	protected $model;

	public function __construct(CategoryGroup $categoryGroup)
	{
		$this->model = $categoryGroup;
	}

    public function all()
    {
        return $this->model->withCount('subGroups')->with('image')->orderBy('order', 'asc')->get();
    }

    public function trashOnly()
    {
        return $this->model->onlyTrashed()->withCount('subGroups')->get();
    }

    public function store(Request $request)
    {
        $catGrp = parent::store($request);

        return $catGrp;
    }

    public function update(Request $request, $id)
    {
        $catGrp = parent::update($request, $id);

        return $catGrp;
    }

    public function destroy($id)
    {
        $catGrp = parent::findTrash($id);

        $catGrp->flushImages();

        return $catGrp->forceDelete();
    }

    public function massDestroy($ids)
    {
        $catGrps = $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($catGrps as $catGrp) {
            $catGrp->flushImages();
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $catGrps = $this->model->onlyTrashed()->get();

        foreach ($catGrps as $catGrp) {
            $catGrp->flushImages();
        }

        return parent::emptyTrash();
    }

}