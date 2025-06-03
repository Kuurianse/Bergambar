@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <!-- Conditional: Commission found -->
            <div class="commission-found-placeholder">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h3 class="mb-0">Commission: Detailed Fantasy Portrait</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0 text-center">
                                <!-- Image Placeholder -->
                                <img src="https://via.placeholder.com/400x300/007bff/ffffff?text=Commission+Artwork" alt="Commission Image Placeholder" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
                                <!-- No image placeholder (toggle display) -->
                                <p style="display:none;">No image has been provided for this commission.</p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-3">Detailed Fantasy Portrait of Your Character</h4>
                                <p><strong>Artist:</strong> <a href="#">ArtisticGenius22</a></p>
                                <p><strong>Status:</strong> <span class="badge bg-success">Available</span></p>
                                <p><strong>Price:</strong> <span class="fw-bold fs-5 text-primary">Rp1.500.000</span></p>
                                <p><strong>Posted:</strong> January 15, 2024</p>
                                <p class="mb-3"><strong>Loves:</strong> <span id="loveCountPlaceholder">125</span></p>

                                <!-- Action Buttons Placeholder -->
                                <div class="mt-3 pt-3 border-top">
                                    <!-- Love Button (Example) -->
                                    <button type="button" class="btn btn-outline-danger btn-sm love-button-placeholder me-2">
                                        <i class="fas fa-heart"></i> <span class="love-text-placeholder">Love</span> (<span class="love-count-display-placeholder">125</span>)
                                    </button>
                                    <!-- Order Button (Example - show if available and not owner) -->
                                    <a href="#" class="btn btn-primary btn-sm me-2"><i class="fas fa-shopping-cart"></i> Order This Commission</a>
                                    <!-- Edit Button (Example - show if owner) -->
                                    <a href="#" class="btn btn-warning btn-sm" style="display:none;"><i class="fas fa-edit"></i> Edit Commission</a>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div>
                            <h5>Full Description:</h5>
                            <p>This commission offers a highly detailed, full-color portrait of your fantasy character (original characters, D&D, etc.). Includes a simple to moderately complex background. Please provide detailed references and descriptions. Turnaround time is approximately 2-3 weeks depending on complexity.</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section Placeholder -->
                <div class="mt-4 card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fas fa-star me-2"></i>Reviews (3)</h4>
                    </div>
                    <div class="card-body">
                        <!-- Example Review 1 -->
                        <div class="review-item card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="card-title">Amazing Work!</h6>
                                    <div class="rating-placeholder text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> <span>(5/5)</span>
                                    </div>
                                </div>
                                <p class="card-text">The artist was fantastic to work with and the final piece exceeded all my expectations. Highly recommend!</p>
                                <p class="card-text"><small class="text-muted">By: ClientX123 on February 01, 2024</small></p>
                            </div>
                        </div>
                        <!-- Example Review 2 -->
                        <div class="review-item card mb-3">
                            <div class="card-body">
                                 <div class="d-flex justify-content-between">
                                    <h6 class="card-title">Good communication</h6>
                                    <div class="rating-placeholder text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i> <span>(4/5)</span>
                                    </div>
                                </div>
                                <p class="card-text">Satisfied with the result, communication was clear throughout the process.</p>
                                <p class="card-text"><small class="text-muted">By: HappyCustomer007 on January 25, 2024</small></p>
                            </div>
                        </div>
                        <!-- No reviews placeholder (toggle display) -->
                        <p style="display:none;">There are no reviews for this commission yet.</p>
                    </div>
                </div>

                <!-- Leave a Review Section Placeholder (Show if authenticated and eligible) -->
                <div class="mt-4 card shadow-sm">
                     <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-pencil-alt me-2"></i>Leave a Review</h5>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST"> <!-- Action set to # -->
                            @csrf
                            <div class="mb-3">
                                <label for="review_text" class="form-label">Your Review</label>
                                <textarea name="review_text" id="review_text" class="form-control" rows="3" placeholder="Share your experience with this commission..." required></textarea>
                                <!-- Placeholder for error -->
                                <div class="invalid-feedback" style="display: none;">Review text cannot be empty.</div>
                            </div>
                            <div class="mb-3">
                                <label for="rating_select" class="form-label">Rating</label>
                                <select name="rating_select" id="rating_select" class="form-select" required>
                                    <option value="">-- Select Rating --</option>
                                    <option value="5">5 Stars (Excellent)</option>
                                    <option value="4">4 Stars (Good)</option>
                                    <option value="3">3 Stars (Average)</option>
                                    <option value="2">2 Stars (Fair)</option>
                                    <option value="1">1 Star (Poor)</option>
                                </select>
                                <!-- Placeholder for error -->
                                <div class="invalid-feedback" style="display: none;">Please select a rating.</div>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-2"></i>Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Conditional: Commission not found -->
            <div class="commission-not-found-placeholder text-center p-5" style="display: none;">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p class="lead">The requested commission could not be found.</p>
                <a href="#" class="btn btn-primary">Back to Commissions</a> <!-- Link to commissions index -->
            </div>
        </div>
    </div>
</div>

<!-- Barebones script for love button (no actual AJAX) -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.love-button-placeholder').forEach(button => {
        button.addEventListener('click', function () {
            const commissionId = 'placeholderCommissionId'; // In real app, get from data attribute
            let loveTextSpan = this.querySelector('.love-text-placeholder');
            let loveCountDisplaySpan = this.querySelector('.love-count-display-placeholder');
            let currentCount = parseInt(loveCountDisplaySpan.textContent);

            if (this.classList.contains('btn-danger')) { // If currently "Loved"
                this.classList.remove('btn-danger');
                this.classList.add('btn-outline-danger');
                loveTextSpan.textContent = 'Love';
                loveCountDisplaySpan.textContent = currentCount - 1;
            } else { // If currently "Love"
                this.classList.remove('btn-outline-danger');
                this.classList.add('btn-danger');
                loveTextSpan.textContent = 'Loved';
                loveCountDisplaySpan.textContent = currentCount + 1;
            }
            // console.log('Love button clicked for commission (placeholder): ' + commissionId);
        });
    });
});
</script>
@endpush

@endsection