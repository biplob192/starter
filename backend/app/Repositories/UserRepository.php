<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function index()
    {
        $users = User::with(['roles'])->latest()->get();
        return ['data' => $users, 'message' => 'All user data.', 'status' => 200];
    }


    public function getData($request)
    {
        // Define the default page and perPage values
        $page           = $request->input('page', 1);
        $perPage        = $request->input('perPage', 10);
        $searchValue    = $request->input('search');
        $orderBy        = 'id';
        $order          = 'desc';

        if ($request->input('perPage') == 'All') {
        }


        // First method without handling all selected in frontend

        // Calculate the offset for the query
        // $offset = ($page - 1) * $perPage;

        // $users = User::query()
        // ->when($searchValue, function ($query, $searchValue) {
        //     $query->where(function ($query) use ($searchValue) {
        //         $query->where('name', 'like', '%' . $searchValue . '%')
        //         ->orWhere('email', 'like', '%' . $searchValue . '%');
        //     });
        // })
        // ->offset($offset)
        // ->limit($perPage)
        // ->get();

        // First method (end) without handling all selected in frontend


        // Second method with all selected in frontend
        $usersQuery = User::query()
            ->when($searchValue, function ($query, $searchValue) {
                $query->where(function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                });
            });

        // Check if $perPage is numeric or 'all'
        if ($perPage != -1 && is_numeric($perPage)) {
            $offset = ($page - 1) * $perPage;
            $usersQuery->offset($offset)->limit($perPage);
        }

        $users = $usersQuery->orderBy($orderBy, $order)->get();

        // Second method (end) with all selected in frontend

        // Get the total count of items (for pagination)
        $totalUsers = User::count(); // You can optimize this query if needed
        return ['data' => ['data' => $users, 'total' => $totalUsers, 'request' => $request->all()], 'message' => 'All user data.', 'status' => 200];
    }


    public function store($request)
    {
        // Create new user
        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->password     = Hash::make($request->password);
        $user->save();


        // Assign user role
        if ($request->role) {
            if ($request->role == 'Super_Admin') {
                throw new Exception("User with this role can not be created.", 403);
            }

            $user->assignRole($request->role);
        } else $user->assignRole('User');


        DB::commit();
        return ['data' => $user, 'message' => 'New user added successfully.', 'status' => 201];
    }


    public function show($id)
    {
        if (!$user = User::with(['roles'])->find($id)) {
            throw new Exception("No record found.", 404);
        }

        return ['data' => $user, 'message' => 'Single user data.', 'status' => 200];
    }


    public function info()
    {
        // if (!$user = User::with(['roles'])->find($id)) {
        if (!$user = auth()->user()) {
            throw new Exception("No record found.", 404);
        }

        return ['data' => $user, 'message' => 'Single user data.', 'status' => 200];
    }


    public function update($request, $id)
    {
        if (!$user = User::find($id)) {
            throw new Exception("No record found.", 404);
        }

        if ($request->name) {
            $user->name   = $request->name;
        }
        if ($request->email) {
            $user->email   = $request->email;
        }
        if ($request->phone) {
            $user->phone   = $request->phone;
        }
        if ($request->password) {
            $user->password   = Hash::make($request->password);
        }
        $user->roles;
        $user->save();


        if ($request->role) {
            $user->assignRole($request->role);
        }

        DB::commit();
        return ['data' => $user, 'message' => 'User updated successfully.', 'status' => 200];
    }


    public function destroy($id)
    {
        if (!User::find($id)?->delete()) {
            throw new Exception("No record found.", 404);
        }

        return ['data' => '', 'message' => 'User deleted successfully.', 'status' => 200];
    }


    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
