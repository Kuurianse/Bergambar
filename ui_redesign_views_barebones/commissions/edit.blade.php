@extends('layouts.app')

@section('content')
<!-- Assuming global styles handle background and Font Awesome is in layouts.app -->
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white"> <!-- Changed color for edit -->
                    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Commission</h3>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="POST" enctype="multipart/form-data"> <!-- Action set to # -->
                        @csrf
                        @method('PUT') <!-- Keep for barebones, though action is # -->
                        
                        <!-- Title Input -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Commission Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="Existing Commission Title - Updated" required>
                            <small class="form-text text-muted">A clear and concise title for your commission.</small>
                            <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Title is required.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="4" required>This is the updated description for the commission. I will draw a full body character with a detailed background and one extra prop.</textarea>
                            <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Description is required.</div>
                        </div>

                        <div class="mb-3">
                            <label for="total_price" class="form-label fw-bold">Total Price (IDR)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="total_price" id="total_price" value="950000" required min="0" step="1000">
                            </div>
                            <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Price must be a valid number.</div>
                        </div>

                        <div class="mb-3">
                            <label for="service_id" class="form-label fw-bold">Link to Existing Service (Optional)</label>
                            <select class="form-select" name="service_id" id="service_id">
                                <option value="">-- Select a Service (if applicable) --</option>
                                <option value="1">Detailed Character Art (Rp750.000)</option>
                                <option value="2" selected>Premium Full Illustration (Rp950.000)</option> <!-- Example selected -->
                                <option value="3">Chibi Icon (Rp150.000)</option>
                            </select>
                            <small class="form-text text-muted">If this commission is based on one of your listed services.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="commission_status" class="form-label fw-bold">Status</label>
                            <select class="form-select" name="commission_status" id="commission_status" required>
                                <option value="available">Available (Open for Orders)</option>
                                <option value="pending_details">Pending Details (Contact for Quote)</option>
                                <option value="ordered_pending_artist_action">Ordered - Pending Artist Action</option>
                                <option value="artist_accepted">Artist Accepted</option>
                                <option value="in_progress" selected>In Progress</option> <!-- Example selected -->
                                <option value="submitted_for_client_review">Submitted for Client Review</option>
                                <option value="needs_revision">Needs Revision</option>
                                <option value="completed">Completed</option>
                                <option value="closed">Closed (Not Taking Orders Currently)</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Status is required.</div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Upload New Image (Optional)</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/png, image/jpeg, image/gif">
                            <small class="form-text text-muted">Max file size: 2MB. Replaces current image if a new one is uploaded.</small>
                            <div class="mt-2">
                                <p class="mb-1"><strong>Current Image:</strong></p>
                                <img id="currentImagePreview" src="https://via.placeholder.com/200/4CAF50/FFFFFF?text=Current+Image" alt="Current Commission Image" class="img-thumbnail me-2" style="max-width: 200px; max-height: 200px;">
                                <img id="newImagePreview" src="https://via.placeholder.com/150/CCCCCC/FFFFFF?text=New+Preview" alt="New Image Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px; display: none;"> <!-- Initially hidden -->
                            </div>
                        </div>
                        
                        <!-- Placeholder for general form error -->
                        <div class="alert alert-danger" role="alert" style="display: none;">
                            Please correct the errors above.
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg"> <!-- Changed color for update -->
                                <i class="fas fa-save me-2"></i>Update Commission
                            </button>
                            <a href="#" class="btn btn-outline-secondary"> <!-- Link to commission show page or artist's commissions index -->
                                <i class="fas fa-times-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const newImagePreview = document.getElementById('newImagePreview');

    if (imageInput && newImagePreview) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    newImagePreview.src = e.target.result;
                    newImagePreview.style.display = 'inline-block'; // Show new preview
                }
                reader.readAsDataURL(file);
            } else {
                newImagePreview.src = 'https://via.placeholder.com/150/CCCCCC/FFFFFF?text=New+Preview';
                newImagePreview.style.display = 'none'; // Hide if no file selected
            }
        });
    }
});
</script>
@endsection