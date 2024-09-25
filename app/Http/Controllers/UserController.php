<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\ArtistService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller implements HasMiddleware
{
    protected $userService;
    protected $artistService;

    public function __construct(UserService $userService, ArtistService $artistService)
    {
        $this->userService = $userService;
        $this->artistService = $artistService;
    }

    public static function middleware():array{
        return [
            new Middleware('role:super_admin')
        ];
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $pageSize = $request->page_size ?? 10;
        $search = $request->search;
        $userId = Auth::user()->id;
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
        if (!Gate::allows('can-manage-user', ['user'=>$user])) {
            return redirect()->route('users.index')->with('error', 'Unauthorized to manage this user.');
        }
        
        if($user['role'] === 'artist'){
            $user = $this->artistService->getAllDetailsByUserId($id);
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
        try{
            
            $userData = $request->validated();
            
            $userData['password'] = Hash::make($userData['password']);
            $userData['super_admin_id'] = Auth::user()->id;
            
            DB::beginTransaction();

            $userId = $this->userService->createUser($userData);
    
            if($request->input('role') === 'artist')
            {
                $artistData = $request->validate([
                    'name' => 'required|string|max:255',
                    'first_release_year' => 'required|integer|min:1900|max:' . date('Y'),
                    'no_of_albums_released' => 'required|integer|min:0',
                ]);
                $artistData['user_id'] = $userId;
                $this->artistService->createArtist($artistData);
            }
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        }
        catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withErrors($e->validator) 
                             ->withInput();              
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
        if (!Gate::allows('can-manage-user', ['user'=>$user])) {
            return redirect()->route('users.index')->with('error', 'Unauthorized to manage this user.');
        }

        if($user['role'] === 'artist'){
            $user = $this->artistService->getAllDetailsByUserId($id);
        }
    
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try{
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->userService->updateUser($id, $validatedData);

            $user = $this->userService->getUserById($id);

            if($user['role'] === 'artist')
            {
                $artistData = $request->validate([
                    'name' => 'required|string|max:255',
                    'first_release_year' => 'required|integer|min:1900|max:' . date('Y'),
                    'no_of_albums_released' => 'required|integer|min:0',
                ]);
               
                $this->artistService->updateArtistByUserId($user['id'],$artistData);
            }
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        }
        catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withErrors($e->validator) 
                             ->withInput();              
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
        if (!Gate::allows('can-manage-user', ['user'=>$user])) {
            return redirect()->route('users.index')->with('error', 'Unauthorized to manage this user.');
        }

        $this->userService->deleteUser($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
