<?php

namespace App\Repositories;

use App\Models\User;
use App\Http\Criterias\SearchCriteria;
use App\Http\Presenters\DataPresenter;
use Illuminate\Support\Facades\Hash;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
	public function __construct()
	{
		parent::__construct(User::class);
	}

	public function login($request)
    {
        try {
            $user = User::whereRaw('lower(email) = ?', strtolower($request->email))->first();

            if ($user && \Hash::check($request->password, $user->password)) {
                $data = [
                    'user' => $user,
                    'permission' => $user->roles->first()->permissions()->pluck('name')->all(),
                    'token' => ['access_token' => $user->createToken($user->id . '-'. $user->name)->accessToken,
                                'expires_in' => 3600]
                ];

                return response()->json([
                    'success' => true,
                    'data' => $data,
                ], 200);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.',
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
        }
    }
	
	public function browse($request)
	{
		try{
			if(!$this->hasPermissionTo('Read Users')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$this->query = $this->getModel()->with(['roles']);
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
			if(!$this->hasPermissionTo('Read Users')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$this->query = $this->getModel()->where(['id' => $id]);
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
		try {
			if(!$this->hasPermissionTo('Create Users')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$payload = $request->all();
			$payload['email'] = strtolower($this->randomString(8) .'@email.com');
			$payload['password'] = Hash::make($this->randomString(8));
			$user = User::create($payload);

			$user->syncRoles('Buruh');

			return $this->show($user->id, $request);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
		}
	}

	function randomString($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
	  
		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}
	  
		return $randomString;
    }

	public function update($id, $request)
	{
		try {
			if(!$this->hasPermissionTo('Update Users')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$payload = $request->all();
			$user = User::findOrFail($id);
			$payload['email'] = $request->filled('email') && $payload['email'] != null? $payload['email'] : $user->email;
			$payload['password'] = $request->filled('password') && $payload['password'] != null? Hash::make($payload['password']) :$user->password;

			$user->update($payload);
			$user->syncRoles('Buruh');
			return $this->show($id, $request);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], 400);
		}
	}

	public function destroy($id)
	{
		try {
			if(!$this->hasPermissionTo('Delete Users')){
				throw new \Exception("Error, you not have the correct permission", 403);
			}
			$user = User::findOrFail($id);
			\App\Models\Wage::where('user_id', $id)->delete();
			$user->delete();

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
