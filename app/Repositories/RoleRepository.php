<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Criterias\SearchCriteria;
use App\Http\Presenters\DataPresenter;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct(Role::class);
	}

	public function browse($request)
	{
		try{
			if(!$this->hasPermissionTo('Read Role & Permission')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$this->query = $this->getModel()->with(['permissions']);
			$this->applyCriteria(new SearchCriteria($request));

			return $this->renderCollection($request);
		}catch (\Exception $e) {
			return response()->json([
				'success' => false,	
				'message' => $e->getMessage()
			], 400);
		}
	}

	public function show($id, $request)
	{
		try{
			if(!$this->hasPermissionTo('Read Role & Permission')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$this->query = $this->getModel()->where(['id' => $id])->with(['permissions']);
			$this->applyCriteria(new SearchCriteria($request));

			return $this->render($request);
		}catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
		}
	}

	public function store($request)
	{
		\DB::beginTransaction();
		try {
			if(!$this->hasPermissionTo('Create Role & Permission')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$payload = $request->all();
			$role = Role::create($payload);
			
			$permission = Permission::whereIn('id', $payload['permission_ids'])->pluck('id')->all();
			$role->syncPermission($permission);

			\DB::commit();
			return $this->show($role->id, $request);
		} catch (\Exception $e) {
			\DB::rollback();
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
		}
	}

	public function update($id, $request)
	{
		\DB::beginTransaction();
		try {
			if(!$this->hasPermissionTo('Update Role & Permission')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$payload = $request->all();
			$role = Role::findOrFail($id);

			$permission = Permission::whereIn('id', $payload['permission_ids'])->pluck('id')->all();
			$role->syncPermission($permission);
			$role->update($payload);
			
			\DB::commit();
			return $this->show($id, $request);
		} catch (\Exception $e) {
			\DB::rollback();
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
		}
	}

	public function destroy($id)
	{
		try {
			if(!$this->hasPermissionTo('Delete Role & Permission')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$role = Role::findOrFail($id);
			if ($role->has('users')) {
				throw new \Exception("Error, cannot delete role used", 403);	
			}
			
			$role->delete();

			return response()->json([
				'success' => true,
				'message' => 'data has been deleted'
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
		}
	}
}
