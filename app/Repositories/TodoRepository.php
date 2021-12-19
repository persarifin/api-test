<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Http\Criterias\SearchCriteria;
use App\Http\Presenters\DataPresenter;
use App\Repositories\BaseRepository;

class TodoRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct(Todo::class);
	}

	public function browse($request)
	{
		try{
			if(!$this->hasPermissionTo('Read Todo Payments')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$this->query = $this->getModel()->with(['wage' => function($q){
				$q->oldest()->with(['user'])->first();
			}]);
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
			if(!$this->hasPermissionTo('Detail Todo Payments')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$this->query = $this->getModel()->where(['id' => $id])->with(['wage' => function($q){
				$q->oldest()->with(['user'])->first();
			}]);
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
			if(!$this->hasPermissionTo('Create Todo Payments')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$payload = $request->all();
			$todo = Todo::create($payload);

			if ($request->filled('wages')) {
				foreach ($payload['wages'] as $value) {
					$todo->wage()->create($value);
				}
			}

			\DB::commit();
			return $this->show($todo->id, $request);
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
			if(!$this->hasPermissionTo('Update Todo Payments')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$payload = $request->all();
			$todo = Todo::findOrFail($id);

			if ($request->filled('wages')) {
				$todo->wage()->first()->delete();
				foreach ($payload['wages'] as $value) {
					$todo->wage()->create($value);
				}
			}

			$todo->update($payload);
			
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
			if(!$this->hasPermissionTo('Delete Todo Payments')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$todo = Todo::findOrFail($id);
			$todo->wage()->delete();
			$todo->delete();

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
