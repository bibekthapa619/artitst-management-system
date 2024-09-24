<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\UpdateArtistRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArtistImport;
use Illuminate\Validation\ValidationException;
use App\Services\ArtistService;
use App\Services\MusicService;
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
    protected $musicService;
    protected $genres = ['rnb','country','classic','rock','jazz'];

    public function __construct(UserService $userService, ArtistService $artistService, MusicService $musicService)
    {
        $this->userService = $userService;
        $this->artistService = $artistService;
        $this->musicService = $musicService;
    }

    public static function middleware():array{
        return [
            new Middleware(middleware:'role:super_admin|artist_manager',only:['index','show','showMusic']),
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

    public function showMusic(Request $request, $userId)
    {
        $page = $request->page ?? 1;
        $pageSize = $request->page_size ?? 10;
        $search = $request->search;
        $artist = $this->artistService->getArtistByUserId($userId);

        if (!$artist) {
            return redirect()->route('artists.index')->with('error', 'Artist not found.');
        }

        $condition = "artist_id = {$artist['id']}";
        $bindings = [];

        if($search){
            if(in_array($search,$this->genres)){
                $condition .= " AND genre = ?";
                $bindings = [$search];
            }
            else{
                $condition .= " AND (title like  ?
                                OR album_name like ?
                            )";
                $bindings = [
                    "%$search%",
                    "%$search%",
                ];
            }            
        }
     
        $data = $this->musicService->getAllMusic(
                                        columns:['*'], 
                                        condition:$condition, 
                                        bindings:$bindings, 
                                        orderBy:'id ASC', 
                                        pageSize:$pageSize, 
                                        currentPage:$page);
        $musics = $data['data'];
        $pagination = $data['meta'];

        return view('artists.show-music', compact('artist', 'musics','pagination'));
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
            return redirect()->route('artists.index')->with('success', 'Artist created successfully.');
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

    public function importForm()
    {
        return view('artists.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Try to import and validate the CSV data
        try {
            // Initialize the ArtistImport class and load the data from the CSV file
            $import = new ArtistImport();
            DB::beginTransaction();
            Excel::import($import, $request->file('csv_file'));

            $validatedData = $import->getValidatedData();

            foreach($validatedData as $data){
                $userData = [
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'email'      => $data['email'],
                    'phone'      => $data['phone'],
                    'password'   => bcrypt('password'),
                    'dob'        => $data['dob'],
                    'gender'     => $data['gender'],
                    'address'    => $data['address'],
                    'role'       => 'artist',
                    'super_admin_id' => Auth::user()->super_admin_id,
                ];
                $userId = $this->userService->createUser($userData);
                
                $artistData = [
                    'user_id'               => $userId,
                    'name'           => $data['artist_name'],
                    'first_release_year'    => $data['first_release_year'],
                    'no_of_albums_released' => $data['no_of_albums_released']
                ];

                $this->artistService->createArtist($artistData);

            }
            DB::commit();
            return redirect()->route('artists.import-form')->with('success','File imported successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($import->getValidationErrors());
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->route('artists.import-form')->with('error', $e->getMessage());
        }
    }
}
