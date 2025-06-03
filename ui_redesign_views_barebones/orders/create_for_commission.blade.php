@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Order Commission: "Sci-Fi Character Concept"</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Column for Image -->
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <!-- Image Placeholder -->
                            <img src="https://via.placeholder.com/350x280/6c757d/ffffff?text=Commission+Example" alt="Commission Image Placeholder" class="img-fluid rounded shadow" style="max-height: 350px; object-fit: contain;">
                            <!-- No image placeholder (toggle display) -->
                            <p style="display:none;">No image provided for this commission.</p>
                        </div>

                        <!-- Column for Details and Description -->
                        <div class="col-md-7">
                            <h4 class="mb-3">Sci-Fi Character Concept Art</h4>
                            <p><strong>Artist:</strong> <a href="#">PixelPioneer</a></p>
                            <p><strong>Description:</strong> Full body concept art for a science fiction character, including basic weapon design. Client to provide detailed brief and references.</p>
                            <p class="mb-1"><strong>Price:</strong></p>
                            <h2 class="fw-bold text-success mb-3">Rp2.000.000</h2>
                            
                            <p class="text-muted small">By proceeding, you agree to the artist's terms of service and our platform's policies.</p>

                            <!-- Order and Chat Buttons Placeholder -->
                            <div class="order-buttons mt-4 pt-3 border-top">
                                <!-- Authenticated User Buttons -->
                                <div class="authenticated-user-actions">
                                    <button type="button" class="btn btn-lg btn-primary me-2 mb-2" data-bs-toggle="modal" data-bs-target="#paymentModalPlaceholder">
                                        <i class="fas fa-credit-card me-2"></i>Proceed to Order
                                    </button>
                                    <a href="#" class="btn btn-lg btn-outline-secondary mb-2">
                                        <i class="fas fa-comments me-2"></i>Contact Artist
                                    </a>
                                </div>
                                <!-- Guest User Prompt (toggle display) -->
                                <div class="guest-user-prompt" style="display:none;">
                                    <p class="lead"><a href="#">Login</a> or <a href="#">Register</a> to order this commission.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Optional: Artist's other popular commissions or related items (Placeholder) -->
            <div class="mt-5">
                <h4 class="mb-3">Other Commissions by PixelPioneer</h4>
                <div class="row">
                    <!-- Example related item 1 -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/300x200/adb5bd/ffffff?text=Other+Art+1" class="card-img-top" alt="Other Art 1">
                            <div class="card-body">
                                <h6 class="card-title">Cyberpunk Cityscape</h6>
                                <p class="card-text small">Rp1.800.000</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                     <!-- Example related item 2 -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/300x200/adb5bd/ffffff?text=Other+Art+2" class="card-img-top" alt="Other Art 2">
                            <div class="card-body">
                                <h6 class="card-title">Fantasy Creature Design</h6>
                                <p class="card-text small">Rp1.500.000</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal for Payment (Placeholder) -->
<div class="modal fade" id="paymentModalPlaceholder" tabindex="-1" aria-labelledby="paymentModalLabelPlaceholder" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentModalLabelPlaceholder"><i class="fas fa-lock me-2"></i>Confirm Order & Secure Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-7">
                        <h5>Order Summary:</h5>
                        <p>You are about to order the commission: <br><strong>"Sci-Fi Character Concept Art"</strong></p>
                        <p><strong>Artist:</strong> PixelPioneer</p>
                        <p><strong>Buyer:</strong> YourUsernameHere</p>
                        <hr>
                        <p class="fs-4 fw-bold">Total: <span class="text-success">Rp2.000.000</span></p>
                        <p class="small text-muted">Payment will be processed securely. This is a simulation.</p>
                    </div>
                    <div class="col-md-5 text-center">
                        <h6>Simulated Payment Method:</h6>
                        <img src="https://via.placeholder.com/200x200/6c757d/ffffff?text=QRIS+Placeholder" alt="QRIS QR Code Placeholder" class="img-fluid rounded mb-2" style="max-width: 180px;">
                        <p class="small">Scan the QR code with your payment app.</p>
                    </div>
                </div>
                <hr>
                <!-- Payment Confirmation Button -->
                <form action="#" method="POST"> <!-- Action set to # -->
                    @csrf
                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg btn-success">
                            <i class="fas fa-check-circle me-2"></i>Confirm Payment & Place Order
                        </button>
                    </div>
                </form>
                <button type="button" class="btn btn-outline-secondary w-100 mt-2" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection