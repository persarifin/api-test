<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionRepository;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function __construct(RoleRepository $repository, PermissionRepository $permissionRepository)
    {
        $this->repository = $repository;
        $this->permissionRepository = $permissionRepository;
    }
    public function index(Request $request)
    {
        return $this->repository->browse($request);
    }

    public function store(RoleRequest $request)
    {
        return $this->repository->store($request);
    }

    public function show($id, Request $request)
    {
        return $this->repository->show($id, $request);
    }

    public function update($id, RoleRequest $request)
    {
        return $this->repository->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function getPermission(Request $request)
    {
        return $this->permissionRepository->browse($request);
    }
}
