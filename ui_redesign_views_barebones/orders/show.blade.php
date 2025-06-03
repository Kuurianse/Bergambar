@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <!-- Main Order Card -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Order #ORD-2024-001 Details</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Column for Commission Image -->
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <img src="https://via.placeholder.com/350x280/6c757d/ffffff?text=Commission+Artwork" alt="Commission Artwork Placeholder" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: contain;">
                            <!-- No image placeholder (toggle display) -->
                            <p style="display:none;">No image available for this commission.</p>
                        </div>

                        <!-- Column for Order and Commission Details -->
                        <div class="col-md-7">
                            <h4 class="mb-1">Commission: "Sci-Fi Character Concept"</h4>
                            <p class="text-muted">Full body concept art for a science fiction character...</p>
                            
                            <hr>
                            <h5 class="mb-3">Order Information</h5>
                            <p><strong>Status:</strong> <span class="badge bg-warning text-dark">In Progress</span></p>
                            <p><strong>Order Date:</strong> March 01, 2024, 10:30 AM</p>
                            <p><strong>Buyer:</strong> ClientUser001</p>
                            <p><strong>Artist:</strong> <a href="#">PixelPioneer</a></p>
                            <p><strong>Price Paid:</strong> <span class="fw-bold text-success">Rp2.000.000</span></p>
                            
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-comments me-1"></i>Contact Artist</a>
                                <a href="#" class="btn btn-outline-secondary btn-sm ms-2"><i class="fas fa-question-circle me-1"></i>Help with this order</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Actions for Submitted Work (Placeholder - Toggle display based on status) -->
            <div class="card shadow-sm mb-4 client-actions-placeholder" style="display: none;"> <!-- e.g., display:block if status is 'submitted_for_client_review' -->
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-user-check me-2"></i>Client Actions: Artwork Submitted</h4>
                </div>
                <div class="card-body p-4">
                    <p>The artist has submitted the artwork. You can view it via the link below:</p>
                    <p><a href="https://example.com/delivery_link_placeholder" target="_blank" rel="noopener noreferrer">View Submitted Artwork</a></p>
                    <hr>
                    <div class="d-flex justify-content-start gap-2">
                        <button type="button" class="btn btn-success"><i class="fas fa-check-circle me-1"></i>Approve & Complete Order</button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#requestRevisionModalPlaceholder">
                            <i class="fas fa-edit me-1"></i>Request Revision
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Alert for "Needs Revision" status (Placeholder - Toggle display) -->
            <div class="alert alert-warning needs-revision-alert" role="alert" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>You have requested a revision for this commission. Please await an update from the artist.
            </div>

            <!-- Alert for "Completed" status (Placeholder - Toggle display) -->
            <div class="alert alert-success completed-alert" role="alert" style="display: none;">
                <i class="fas fa-check-circle me-2"></i>This commission has been completed.
                <button type="button" class="btn btn-sm btn-outline-primary ms-3" data-bs-toggle="modal" data-bs-target="#leaveReviewModalPlaceholder"><i class="fas fa-star me-1"></i>Leave a Review</button>
            </div>

            <!-- Revision History Section (Placeholder - Toggle display) -->
            <div class="card shadow-sm mb-4 revision-history-placeholder" style="display: none;">
                <div class="card-header bg-light">
                    <h4 class="mb-0"><i class="fas fa-history me-2"></i>Revision History</h4>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <p class="mb-1"><strong>Requested on:</strong> April 05, 2024, 02:15 PM</p>
                            <p class="mb-0"><strong>Notes:</strong> "Please make the background a bit darker and add more stars."</p>
                        </li>
                        <li class="list-group-item">
                            <p class="mb-1"><strong>Requested on:</strong> April 02, 2024, 11:00 AM</p>
                            <p class="mb-0"><strong>Notes:</strong> "The character's left eye seems a bit off. Can you adjust it?"</p>
                        </li>
                    </ul>
                     <!-- No revisions placeholder -->
                    <p class="p-3 text-muted" style="display:none;">No revision requests have been made for this order.</p>
                </div>
            </div>

            <!-- Comments/Reviews Section (Placeholder - if reviews are tied to order/commission) -->
            <!-- This might be redundant if reviews are on the commission page itself -->
            <!-- For now, we assume reviews are on the commission page, and a "Leave Review" button appears on completion -->

        </div>
    </div>
</div>

<!-- Modal for Requesting Revision (Placeholder) -->
<div class="modal fade" id="requestRevisionModalPlaceholder" tabindex="-1" aria-labelledby="requestRevisionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="requestRevisionModalLabel"><i class="fas fa-edit me-2"></i>Request Revision</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST"> <!-- Action to # -->
            @csrf
            <div class="mb-3">
                <label for="revision_notes_modal" class="form-label">Revision Notes:</label>
                <textarea name="revision_notes_modal" id="revision_notes_modal" class="form-control" rows="4" placeholder="Clearly explain what needs to be revised..." required></textarea>
                <small class="form-text text-muted">Be specific to help the artist understand your request.</small>
            </div>
            <button type="submit" class="btn btn-warning w-100"><i class="fas fa-paper-plane me-2"></i>Submit Revision Request</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Leaving Review (Placeholder - if triggered from here) -->
<div class="modal fade" id="leaveReviewModalPlaceholder" tabindex="-1" aria-labelledby="leaveReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="leaveReviewModalLabel"><i class="fas fa-star me-2"></i>Leave a Review for this Commission</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST"> <!-- Action to # -->
            @csrf
            <div class="mb-3">
                <label for="review_rating_modal" class="form-label">Overall Rating:</label>
                <select name="review_rating_modal" id="review_rating_modal" class="form-select" required>
                    <option value="">-- Select Stars --</option>
                    <option value="5">★★★★★ (Excellent)</option>
                    <option value="4">★★★★☆ (Good)</option>
                    <option value="3">★★★☆☆ (Average)</option>
                    <option value="2">★★☆☆☆ (Fair)</option>
                    <option value="1">★☆☆☆☆ (Poor)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="review_text_modal" class="form-label">Your Review:</label>
                <textarea name="review_text_modal" id="review_text_modal" class="form-control" rows="4" placeholder="Share your experience..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="fas fa-check-circle me-2"></i>Submit Review</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection