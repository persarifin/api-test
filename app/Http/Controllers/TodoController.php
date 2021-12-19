<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TodoRepository;
use App\Http\Requests\TodoRequest;

class TodoController extends Controller
{
    public function __construct(TodoRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->browse($request);
    }

    public function store(TodoRequest $request)
    {
        return $this->repository->store($request);
    }

    public function show($id, Request $request)
    {
        return $this->repository->show($id, $request);
    }

    public function update($id, TodoRequest $request)
    {
        return $this->repository->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
