<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Artist; // Added
use App\Models\Category; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Added

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Protect all service routes
        // Add more specific middleware if needed, e.g., ensuring user is an artist
    }

    // Display a list of services for the authenticated artist
    public function index()
    {
        $user = Auth::user();
        if (!$user->artist) {
            // Option 1: Redirect if no artist profile
            // return redirect()->route('artists.create')->with('info', 'Please create an artist profile to manage services.');
            // Option 2: Show empty state or different view
            return view('services.index', ['services' => collect(), 'artist' => null])
                   ->with('info', 'Create an artist profile to add services.');
        }
        $artist = $user->artist;
        $services = Service::where('artist_id', $artist->id)
                            ->with('category')
                            ->latest()
                            ->paginate(10);
        return view('services.index', compact('services', 'artist'));
    }

    // Show the form for creating a new service.
    public function create()
    {
        $user = Auth::user();
        if (!$user->artist) {
            return redirect()->route('artists.create')->with('info', 'You need an artist profile to add services.');
        }
        $categories = Category::all(); // For category selection
        return view('services.create', compact('categories'));
    }

    // Store a newly created service in storage.
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->artist) {
            return back()->with('error', 'You must have an artist profile to add services.')->withInput();
        }
        $artist = $user->artist;

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:illustration,music,rigging,other',
            'category_id' => 'nullable|exists:categories,id',
            'availability_status' => 'sometimes|boolean',
        ]);

        $service = new Service($validatedData);
        $service->artist_id = $artist->id;
        $service->availability_status = $request->boolean('availability_status');
        $service->save();

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    // Display the specified service (public view).
    public function show(Service $service) // Using route model binding
    {
        $service->load('artist.user', 'category');
        return view('services.show', compact('service'));
    }

    // Show the form for editing the specified service.
    public function edit(Service $service)
    {
        $user = Auth::user();
        // Authorization: ensure the user owns this service via their artist profile
        if (!$user->artist || $service->artist_id !== $user->artist->id) {
            abort(403, 'Unauthorized action.');
        }
        $categories = Category::all();
        return view('services.edit', compact('service', 'categories'));
    }

    // Update the specified service in storage.
    public function update(Request $request, Service $service)
    {
        $user = Auth::user();
        // Authorization
        if (!$user->artist || $service->artist_id !== $user->artist->id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:illustration,music,rigging,other',
            'category_id' => 'nullable|exists:categories,id',
            'availability_status' => 'sometimes|boolean',
        ]);

        $service->fill($validatedData);
        $service->availability_status = $request->boolean('availability_status');
        $service->save();

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    // Remove the specified service from storage.
    public function destroy(Service $service)
    {
        $user = Auth::user();
        // Authorization
        if (!$user->artist || $service->artist_id !== $user->artist->id) {
            abort(403, 'Unauthorized action.');
        }

        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
