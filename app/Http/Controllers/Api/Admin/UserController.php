<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource; // We'll create this next
use App\Http\Resources\Admin\ArtistResource; // Will be created
use App\Models\User;
use App\Models\Artist; // Added for Artist model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // Added this line

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // Basic pagination, can be enhanced with search/filtering later
        $users = User::orderBy('id', 'desc')->paginate(15); // Default 15 per page

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed', // Requires 'password_confirmation' field in request
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'user', 'artist']),
            ],
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'bio' => $validatedData['bio'] ?? null,
            'email_verified_at' => now(), // New users are active by default
        ]);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\Admin\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user->loadMissing('artist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\Admin\UserResource
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'user', 'artist']), // Adjust if role list differs
            ],
            'is_active' => 'required|boolean', // Added for status update
            // Add validation for other fields if they become editable in the future
            // 'name' => 'sometimes|string|max:255',
            // 'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            // 'bio' => 'nullable|string',
        ]);

        $user->role = $validatedData['role'];
        $user->email_verified_at = $validatedData['is_active'] ? now() : null;

        // If other fields were allowed for edit:
        // if ($request->has('name') && isset($validatedData['name'])) {
        //     $user->name = $validatedData['name'];
        // }
        // // etc. for other fields

        $user->save();

        return new UserResource($user->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 403); // Forbidden
        }

        $user->delete();
        // Asumsi hard delete. Pertimbangkan implikasi pada data terkait.
        
        return response()->noContent(); // HTTP 204 No Content
    }

    /**
     * Promote a user to an artist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function promoteToArtist(Request $request, User $user)
    {
        if ($user->artist) {
            return response()->json(['message' => 'User is already an artist.'], 409); // Conflict
        }

        $validatedData = $request->validate([
            'portfolio_link' => 'nullable|string|max:255|url',
        ]);

        $artist = $user->artist()->create([
            'portfolio_link' => $validatedData['portfolio_link'] ?? null,
            'is_verified' => false, // Default to not verified
            'rating' => null,       // Default to no rating
        ]);

        // Load the user relationship for the ArtistResource
        $artist->load('user');

        return (new ArtistResource($artist))
                ->response()
                ->setStatusCode(201); // Created
    }
}