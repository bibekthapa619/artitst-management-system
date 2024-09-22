<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

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
        $condition = "role != 'super_admin'";
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
}
