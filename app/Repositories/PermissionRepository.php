<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use App\Http\Criterias\SearchCriteria;
use App\Http\Presenters\DataPresenter;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct(Permission::class);
	}

	public function browse($request)
	{
		try{
			if (!\Auth::user()->hasRole('Administrator')) {
				throw new \Exception("Error, You not have the correct permission", 403);
			}
			$this->query = $this->getModel();
			$this->applyCriteria(new SearchCriteria($request));

			return $this->renderCollection($request);
		}catch (\Exception $e) {
			return response()->json([
				'success' => false,	
				'message' => $e->getMessage()
			], 400);
		}
	}
}
