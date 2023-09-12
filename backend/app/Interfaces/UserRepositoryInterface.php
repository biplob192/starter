<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function index();
    public function getData($request);
    public function store($request);
    public function show($id);
    public function info();
    public function update($request, $id);
    public function destroy($id);
    public function export();
}
