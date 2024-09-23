<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $pageSize = $request->page_size ?? 10;
        $search = $request->search;
        $userId = auth()->user()->id;
        $condition = "super_admin_id ='$userId' and role != 'super_admin'";
        $bindings = [];

        if($search){
            if(in_array($search,['artist_manager','artist'])){
                $condition .= " AND role = ?";
                $bindings = [$search];
            }
            else{
                $condition .= " AND (CONCAT(first_name, ' ', last_name) like ?
                                OR email like ? 
                                OR phone like ? 
                            )";
                $bindings = [
                    "%$search%",
                    "%$search%",
                    "%$search%",
                ];
            }            
        }
     
        $usersData = $this->userService->getAllUsers(
                                        columns:['*'], 
                                        condition:$condition, 
                                        bindings:$bindings, 
                                        orderBy:'id ASC', 
                                        pageSize:$pageSize, 
                                        currentPage:$page);
        $users = $usersData['data'];
        $pagination = $usersData['meta'];
        
        return view('users.index', compact('users', 'pagination'));
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
        return view('users.show', compact('user'));
    }

    public function create()
    {
        $roles = ['artist_manager','artist'];
        return view('users.create',compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['super_admin_id'] = auth()->user()->id;

        $this->userService->createUser($validatedData);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validatedData = $request->validated();

        $this->userService->updateUser($id, $validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $this->userService->deleteUser($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
