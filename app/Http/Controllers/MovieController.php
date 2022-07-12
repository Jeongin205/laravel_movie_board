<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
class MovieController extends Controller
{
    //
    public function index()
    {
        $movies = Movie::all();
        return view('movie.index', ['movies'=>$movies]);
    }

    public function store(Request $request)
    {
        // if(!$request->file('photo')){
        //     return redirect()->back()->withErrors([
        //         'error' => "이미지를 넣어주세요"
        //     ]);
        // }이미지가 없을경우
        if(!$request->validate(['photo' => 'image|mimes:jpeg,png,jpg,svg',])){
            return redirect()->back()->withErrors([
                'error' => "이미지파일만 가능합니다."
            ]);
        }
        $path = $request->file('photo')->store('public');
        $movie = Movie::create([
            'photo' => Storage::url($path),
            'title' => $request->title,
            'genre' => $request->genre,
            'score' => 0.00,
            'audience' => 0,
            'rating' => $request->rating,
            'open_date' => $request->open_date,
            'actors' => $request->actors,
        ]);
        return redirect()->back();
    }
    public function show(Movie $movie){
        return response()->json([
            'movie' =>$movie
        ]);
    }
    public function update(Request $request, Movie $movie){
        $audience = $movie->audience + 1;
        $pre_score = $movie->score * $movie->audience;
        $new_score = ($pre_score + $request->score) / $audience;
        $movie->update([
            'audience'=> $audience,
            'score' => number_format((float)$new_score, 2, '.', ''),
        ]);
        return "success";
    }
}
