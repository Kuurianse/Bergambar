<?php

namespace App\Http\Controllers;

use App\Models\Artist; // Added
use App\Models\Commission;
use App\Models\User;
use Illuminate\Http\Request; // Added
use Illuminate\Support\Facades\Auth; // Added

class ArtistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']); // Added for artist profile management
    }

    // Display a list of artists (users with commissions)
    public function index()
    {
        // Retrieve all Artist profiles, with their associated User model and counts of commissions and services
        $artists = Artist::with(['user' => function ($query) {
                            $query->withCount('commissions'); // Loads user_commissions_count on the user model
                         }])
                         ->withCount('services') // Loads services_count on the artist model
                         ->latest()
                         ->paginate(10);

        // Pass the artist data to the 'artists.index' view
        // This view will need to be updated to work with Artist models
        return view('artists.index', compact('artists'));
    }

    /**
     * Display the specified artist profile.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\View\View
     */
    public function show(Artist $artist) // Route model binding for Artist
    {
        // Eager load necessary relationships for the artist's profile page
        $artist->load([
            'user.commissions' => function ($query) {
                $query->with(['reviews.user', 'loves'])->latest();
            },
            'services'
        ]);

        // The view 'artists.show' will need to be updated to work with an Artist model
        // and its relationships (e.g., $artist->user->name, $artist->portfolio_link, $artist->user->commissions)
        return view('artists.show', compact('artist'));
    }

    /**
     * Show the form for creating a new artist profile.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Check if the user already has an artist profile
        $existingArtistProfile = Artist::where('user_id', Auth::id())->first();
        if ($existingArtistProfile) {
            // Redirect to their existing profile, or an edit page
            // For now, let's assume we redirect to a conceptual 'artists.show' for Artist model
            return redirect()->route('artists.show', $existingArtistProfile->id)->with('info', 'You already have an artist profile.');
        }
        return view('artists.create'); // Needs artists.create.blade.php
    }

    /**
     * Store a newly created artist profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $existingArtistProfile = Artist::where('user_id', Auth::id())->first();
        if ($existingArtistProfile) {
            return redirect()->route('artists.show', $existingArtistProfile->id)->with('info', 'You already have an artist profile.');
        }

        $validatedData = $request->validate([
            'portfolio_link' => 'nullable|url|max:255',
            // 'is_verified' and 'rating' are typically not set by users directly.
        ]);

        $artist = new Artist();
        $artist->user_id = Auth::id();
        $artist->portfolio_link = $validatedData['portfolio_link'] ?? null;
        // is_verified and rating will have defaults or be handled by admin/system
        $artist->save();

        // We'll need to adjust routes and the show method later to correctly handle Artist model.
        // For now, this conceptual redirect assumes artists.show takes an Artist ID.
        return redirect()->route('artists.show', $artist->id)->with('success', 'Artist profile created successfully!');
    }

    /**
     * Show the form for editing the specified artist profile.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Artist $artist)
    {
        // Authorization: Ensure the logged-in user owns this artist profile
        if (Auth::id() !== $artist->user_id) {
            abort(403, 'Unauthorized action. You can only edit your own artist profile.');
        }
        // It's good practice to also load the user relationship if needed in the edit form
        $artist->load('user');
        return view('artists.edit', compact('artist')); // Needs artists.edit.blade.php
    }

    /**
     * Update the specified artist profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Artist $artist)
    {
        // Authorization: Ensure the logged-in user owns this artist profile
        if (Auth::id() !== $artist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'portfolio_link' => 'nullable|url|max:255',
            // User details (name, username, bio, profile_picture) are edited via UserController
            // Admin might handle 'is_verified' and 'rating'
        ]);

        $artist->portfolio_link = $validatedData['portfolio_link'] ?? null;
        $artist->save();

        return redirect()->route('artists.show', $artist->id)->with('success', 'Artist profile updated successfully!');
    }
}
