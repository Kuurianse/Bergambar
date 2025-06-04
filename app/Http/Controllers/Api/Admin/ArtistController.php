<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Http\Resources\Admin\ArtistResource;
use App\Http\Resources\Admin\UserResource; // For potential use within ArtistResource if needed
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $artists = Artist::with('user')->orderBy('id', 'desc')->paginate(15);
        return ArtistResource::collection($artists);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \App\Http\Resources\Admin\ArtistResource
     */
    public function show(Artist $artist)
    {
        return new ArtistResource($artist->loadMissing('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \App\Http\Resources\Admin\ArtistResource
     */
    public function update(Request $request, Artist $artist)
    {
        $validatedData = $request->validate([
            'portfolio_link' => 'nullable|string|max:255|url',
            'rating' => 'nullable|numeric|min:0|max:5', // Assuming a 0-5 rating scale
            'is_verified' => 'required|boolean',
        ]);

        $artist->update($validatedData);

        return new ArtistResource($artist->loadMissing('user'));
    }

    /**
     * Toggle the verification status of the specified artist.
     *
     * @param  \App\Models\Artist  $artist
     * @return \App\Http\Resources\Admin\ArtistResource
     */
    public function toggleVerification(Artist $artist)
    {
        $artist->is_verified = !$artist->is_verified;
        $artist->save();

        return new ArtistResource($artist->loadMissing('user'));
    }
}