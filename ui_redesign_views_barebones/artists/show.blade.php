@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Artist Profile Section -->
    <div class="row">
        <div class="col-md-4 text-center">
            <!-- Profile Picture -->
            <img src="https://via.placeholder.com/150" alt="Artist Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
            <h3>Artist Username/Name</h3>
            <p class="text-muted">Artist Full Name</p>
            <!-- Portfolio Link -->
            <p><a href="#" target="_blank" class="btn btn-outline-primary btn-sm">View Portfolio</a></p>
            <!-- Action Buttons -->
            <!-- If viewing own profile -->
            <a href="#" class="btn btn-secondary btn-sm mb-2">Edit Artist Profile</a>
            <!-- If viewing other artist's profile -->
            <!-- <a href="#" class="btn btn-success btn-sm mb-2">Contact Artist</a> -->
        </div>
        <div class="col-md-8">
            <h4>About [Artist Username]</h4>
            <p>Artist bio goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <p><strong>Email:</strong> artist@example.com</p>
            <!-- Verification Badge -->
            <p><span class="badge bg-success">Verified Artist</span></p>
            <!-- Rating -->
            <p><strong>Rating:</strong> 4.5/5.0</p>
        </div>
    </div>

    <hr class="my-4">

    <!-- Services Offered Section -->
    <h3 class="mt-4">Services Offered</h3>
    <div class="row">
        <!-- Service Card 1 -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Service Title 1</h5>
                    <p class="card-text">Short service description...</p>
                    <p class="card-text"><strong>Price:</strong> RpXX.XXX.XXX</p>
                    <p class="card-text"><small class="text-muted">Type: Service Type</small></p>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-auto">View Service Details</a>
               </div>
           </div>
       </div>
        <!-- Service Card 2 (Example) -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Service Title 2</h5>
                    <p class="card-text">Another service description...</p>
                    <p class="card-text"><strong>Price:</strong> RpYY.YYY.YYY</p>
                    <p class="card-text"><small class="text-muted">Type: Another Type</small></p>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-auto">View Service Details</a>
               </div>
           </div>
       </div>
        <!-- Add more service cards as needed -->
    </div>
    <!-- If no services -->
    <!-- <p>This artist currently offers no services.</p> -->

    <hr class="my-4">

    <!-- Commissions by Artist Section -->
    <h3 class="mt-4">Commissions by [Artist Username]</h3>
    <div class="row">
        <!-- Commission Card 1 -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <a href="#">
                    <img src="https://via.placeholder.com/300x200" alt="Commission Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                </a>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="#">Commission Title/Description 1</a>
                    </h5>
                    <p class="card-text"><small class="text-muted">Price: RpXX.XXX.XXX</small></p>
                    <p class="card-text"><small class="text-muted">Status: <span class="badge bg-success">Available</span></small></p>
                    <p class="card-text"><small class="text-muted">Loves: 123</small></p>
                </div>
                <div class="card-footer">
                     <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>
        <!-- Commission Card 2 (Example) -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <a href="#">
                    <img src="https://via.placeholder.com/300x200" alt="Commission Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                </a>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="#">Commission Title/Description 2</a>
                    </h5>
                    <p class="card-text"><small class="text-muted">Price: RpYY.YYY.YYY</small></p>
                    <p class="card-text"><small class="text-muted">Status: <span class="badge bg-warning">Ordered</span></small></p>
                    <p class="card-text"><small class="text-muted">Loves: 45</small></p>
                </div>
                <div class="card-footer">
                     <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>
        <!-- Add more commission cards as needed -->
    </div>
    <!-- If no commissions -->
    <!-- <p>This artist has not posted any commissions yet.</p> -->

    <!-- If artist profile not found -->
    <!-- <p>Artist profile not found.</p> -->
</div>
@endsection