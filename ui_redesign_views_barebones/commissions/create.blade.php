@extends('layouts.app')

@section('content')
<!-- Assuming global styles handle background and font awesome is in layouts.app -->
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-paint-brush me-2"></i>Create New Commission</h3>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="POST" enctype="multipart/form-data"> <!-- Action set to # -->
                        @csrf
                        
                        <!-- Title Input -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Commission Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="e.g., Fantasy Character Illustration" value="Sample Commission Title" required>
                            <small class="form-text text-muted">A clear and concise title for your commission.</small>
                             <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Title is required.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="4" placeholder="Describe the commission details, what you offer, specific requirements, etc." required>This is a sample description for the commission. I will draw a full body character with a simple background.</textarea>
                             <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Description is required.</div>
                        </div>

                        <div class="mb-3">
                            <label for="total_price" class="form-label fw-bold">Total Price (IDR)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="total_price" id="total_price" placeholder="e.g., 500000" value="750000" required min="0" step="1000">
                            </div>
                             <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Price must be a valid number.</div>
                        </div>

                        <div class="mb-3">
                            <label for="service_id" class="form-label fw-bold">Link to Existing Service (Optional)</label>
                            <select class="form-select" name="service_id" id="service_id">
                                <option value="">-- Select a Service (if applicable) --</option>
                                <!-- Example Service Option 1 -->
                                <option value="1" selected>Detailed Character Art (Rp750.000)</option>
                                <!-- Example Service Option 2 -->
                                <option value="2">Chibi Icon (Rp150.000)</option>
                                <!-- Example: No services available -->
                                <!-- <option value="" disabled>You have no available services to link.</option> -->
                            </select>
                            <small class="form-text text-muted">If this commission is based on one of your listed services.</small>
                        </div>
                        
                        <!-- Commission Status (Artist sets this, might be 'available' by default) -->
                        <div class="mb-3">
                            <label for="commission_status" class="form-label fw-bold">Initial Status</label>
                            <select class="form-select" name="commission_status" id="commission_status" required>
                                <option value="available" selected>Available (Open for Orders)</option>
                                <option value="pending_details">Pending Details (Contact for Quote)</option>
                                <option value="closed">Closed (Not Taking Orders Currently)</option>
                            </select>
                             <!-- Placeholder for error -->
                            <div class="invalid-feedback" style="display: none;">Status is required.</div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Upload Image (Example/Reference)</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/png, image/jpeg, image/gif">
                            <small class="form-text text-muted">Max file size: 2MB. Allowed types: JPG, PNG, GIF.</small>
                            <!-- Image preview placeholder -->
                            <img id="imagePreview" src="https://via.placeholder.com/150/CCCCCC/FFFFFF?text=Preview" alt="Image Preview" class="mt-2 img-thumbnail" style="max-width: 150px; max-height: 150px; display: block;">
                        </div>
                        
                        <!-- Placeholder for general form error -->
                        <div class="alert alert-danger" role="alert" style="display: none;">
                            Please correct the errors above.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Create Commission
                            </button>
                            <a href="#" class="btn btn-outline-secondary"> <!-- Link to artist's commissions index or dashboard -->
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
    const imagePreview = document.getElementById('imagePreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = 'https://via.placeholder.com/150/CCCCCC/FFFFFF?text=Preview';
                // imagePreview.style.display = 'none'; // Or keep placeholder visible
            }
        });
    }
});
</script>
@endsection