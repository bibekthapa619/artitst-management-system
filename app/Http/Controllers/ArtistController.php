<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\UpdateArtistRequest;
use App\Services\ArtistService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ArtistController extends Controller implements HasMiddleware
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
            new Middleware(middleware:'role:super_admin|artist_manager',only:['index','show']),
            new Middleware(middleware:'role:artist_manager',only:['create','store','edit','update']),
        ];
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $pageSize = $request->page_size ?? 10;
        $search = $request->search;
        $user = Auth::user();
        $superAdminId = $user->role === 'super_admin' ? $user->id :$user->super_admin_id;
        $condition = "users.super_admin_id ='$superAdminId'";
        $bindings = [];

        if($search){
                $condition .= " AND (CONCAT(first_name, ' ', last_name) like ?
                                OR name like ?
                                OR email like ? 
                                OR phone like ? 
                            )";
                $bindings = [
                    "%$search%",
                    "%$search%",
                    "%$search%",
                    "%$search%",
                ];
        }
     
        $data = $this->artistService->getAllArtists(
                                        columns:['
                                            artists.id as id,
                                            users.id as user_id,
                                            users.first_name,
                                            users.last_name,
                                            artists.name,
                                            artists.first_release_year,
                                            artists.no_of_albums_released
                                        '], 
                                        condition:$condition, 
                                        bindings:$bindings, 
                                        orderBy:'id ASC', 
                                        pageSize:$pageSize, 
                                        currentPage:$page);
        $artists = $data['data'];
        $pagination = $data['meta'];
        
        return view('artists.index', compact('artists', 'pagination'));
    }

    public function show($userId)
    {
        $artist = $this->artistService->getAllDetailsByUserId($userId);
        if (!$artist) {
            return redirect()->route('artists.index')->with('error', 'Artist not found.');
        }
        return view('artists.show', compact('artist'));
    }

    public function create()
    {
        return view('artists.create');
    }

    public function store(StoreArtistRequest $request)
    {
        try{  
            $userData = $request->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'password',
                'dob',
                'gender',
                'address'
            ]);

            $artistData = $request->only([
                'name',
                'first_release_year',
                'no_of_albums_released'
            ]);
            
            $userData['password'] = Hash::make($userData['password']);
            $userData['super_admin_id'] = Auth::user()->super_admin_id;
            $userData['role'] = 'artist';
            
            DB::beginTransaction();
            $userId = $this->userService->createUser($userData);

            $artistData['user_id'] = $userId;
            $this->artistService->createArtist($artistData);

            DB::commit();
            return redirect()->route('artists.index')->with('success', 'Artist updated successfully.');
        }
        catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($userId)
    {
        $artist = $this->artistService->getAllDetailsByUserId($userId);
        if (!$artist) {
            return redirect()->route('artists.index')->with('error', 'Artist not found.');
        }
    
        return view('artists.edit', compact('artist'));
    }

    public function update(UpdateArtistRequest $request, $userId)
    {
        try{  
            $userData = $request->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'dob',
                'gender',
                'address'
            ]);

            $artistData = $request->only([
                'name',
                'first_release_year',
                'no_of_albums_released'
            ]);
            
            DB::beginTransaction();
            $this->userService->updateUser($userId, $userData);

            $user = $this->userService->getUserById($userId);
               
            $this->artistService->updateArtistByUserId($userId,$artistData);

            DB::commit();
            return redirect()->route('artists.index')->with('success', 'User created successfully.');
        }
        catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('artists.index')->with('error', 'Artist not found.');
        }

        $this->userService->deleteUser($id);

        return redirect()->route('artists.index')->with('success', 'Artist deleted successfully.');
    }
}
