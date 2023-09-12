<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use App\Interfaces\UserRepositoryInterface;

class UserController extends BaseController
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
        $this->middleware('deletableUser')->only('destroy');
    }

    public function index()
    {
        try {
            $response = $this->userRepository->index();

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function getData(Request $request)
    {
        try {
            $response = $this->userRepository->getData($request);

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $response = $this->userRepository->store($request);

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            DB::rollBack();
            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function show($id)
    {
        try {
            $response = $this->userRepository->show($id);

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function info()
    {
        // return auth()->user();
        try {
            $response = $this->userRepository->info();

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function update(UserRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $response = $this->userRepository->update($request, $id);

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            DB::rollBack();
            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function destroy($id)
    {
        try {
            $response = $this->userRepository->destroy($id);

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);

            // $error = $e->getMessage();
            // return $this->sendError('Internal server error.', $error, 500);
        }
    }


    public function export()
    {
        try {
            return $this->userRepository->export();
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }
}
