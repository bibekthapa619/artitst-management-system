<?php

namespace App\Http\Controllers;

use App\Http\Requests\MusicRequest;
use App\Services\ArtistService;
use App\Services\MusicService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MusicController extends Controller implements HasMiddleware
{
    protected $musicService;
    protected $artistService;
    protected $genres = ['rnb','country','classic','rock','jazz'];

    public function __construct(MusicService $musicService, ArtistService $artistService)
    {
        $this->musicService = $musicService;
        $this->artistService = $artistService;
    }

    public static function middleware():array{
        return [
            new Middleware('role:artist')
        ];
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $pageSize = $request->page_size ?? 10;
        $search = $request->search;
        $userId = Auth::user()->id;
        $artistId = $this->artistService->getArtistByUserId($userId)['id'];
        $condition = "artist_id = $artistId";
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
        
        return view('music.index', compact('musics', 'pagination'));
    }

    public function create()
    {
        $genres = $this->genres;
        return view('music.create',compact('genres'));
    }

    public function store(MusicRequest $request)
    {
        try{
            
            $data = $request->validated();
            
            $artist = $this->artistService->getArtistByUserId(Auth::user()->id);
            
            $data['artist_id'] = $artist['id'];
            DB::beginTransaction();

            $this->musicService->createMusic($data);

            DB::commit();
            return redirect()->route('music.index')->with('success', 'Music created successfully.');
        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $music = $this->musicService->getMusicById($id);
        $genres = $this->genres;
        if (!$music) {
            return redirect()->route('music.index')->with('error', 'Music not found.');
        }
    
        return view('music.edit', compact('music','genres'));
    }

    public function update(MusicRequest $request, $id)
    {
        try{
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->musicService->updateMusic($id, $validatedData);

            DB::commit();
            return redirect()->route('music.index')->with('success', 'Music updated successfully.');
        }
        catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $music = $this->musicService->getMusicById($id);
        if (!$music) {
            return redirect()->route('music.index')->with('error', 'Music not found.');
        }

        $this->musicService->deleteMusic($id);

        return redirect()->route('music.index')->with('success', 'Music deleted successfully.');
    }

}
