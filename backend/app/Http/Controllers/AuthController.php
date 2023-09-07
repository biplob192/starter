<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\BaseController;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends BaseController
{
    public function __construct(private AuthRepositoryInterface $authRepository)
    {
    }


    public function register(RegisterRequest $request)
    {
        try {
            $response = $this->authRepository->register($request);
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function login(LoginRequest $request)
    {
        try {
            $response = $this->authRepository->login($request);
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function logout()
    {
        return $this->sendResponse(auth()->user()->tokens()?->delete(), 'Logout successfully.', 200);
    }


    public function refresh()
    {
        try {
            $response = $this->authRepository->refresh();
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', '', $e->getCode() ? $e->getCode() : 500);
        }
    }
}
