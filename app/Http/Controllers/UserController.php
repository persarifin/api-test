<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function login(LoginRequest $request)
    {
        return $this->repository->login($request);
    }

    public function index(Request $request)
    {
        return $this->repository->browse($request);
    }

    public function store(UserRequest $request)
    {
        return $this->repository->store($request);
    }

    public function show($id, Request $request)
    {
        return $this->repository->show($id, $request);
    }

    public function update($id, UserRequest $request)
    {
        return $this->repository->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
