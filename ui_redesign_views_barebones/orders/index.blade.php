@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12"> <!-- Full width for better table display -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white"> <!-- Changed color for client orders -->
                    <h3 class="mb-0"><i class="fas fa-receipt me-2"></i>My Orders (as Client)</h3>
                </div>

                <div class="card-body p-4">
                    <!-- Alert messages placeholder -->
                    <div class="alert alert-success" role="alert" style="display: none;">
                        Success message placeholder (e.g., Order status updated).
                    </div>
                    <div class="alert alert-danger" role="alert" style="display: none;">
                        Error message placeholder (e.g., Could not retrieve order details).
                    </div>

                    <!-- Search and Filter Bar (Placeholder) -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Search by commission title or artist...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select">
                                <option selected>Filter by Order Status...</option>
                                <option value="paid">Paid (Pending Artist Action)</option>
                                <option value="artist_accepted">Artist Accepted</option>
                                <option value="in_progress">In Progress</option>
                                <option value="submitted_for_client_review">Submitted for Review</option>
                                <option value="needs_revision">Needs Revision</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary w-100" type="button"><i class="fas fa-filter me-1"></i>Apply</button>
                        </div>
                    </div>

                    <!-- Conditional content: No orders -->
                    <div class="no-orders-placeholder text-center p-5" style="display: none;">
                        <i class="fas fa-shopping-basket fa-3x text-muted mb-3"></i>
                        <p class="lead">You haven't placed any orders yet.</p>
                        <a href="#" class="btn btn-primary"><i class="fas fa-search me-1"></i>Browse Commissions</a>
                    </div>

                    <!-- Conditional content: Orders exist (Table View) -->
                    <div class="orders-list-placeholder">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Commission</th>
                                        <th scope="col">Artist</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Date Placed</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Example Row 1 -->
                                    <tr>
                                        <td><code>ORD-2024-001</code></td>
                                        <td>
                                            <a href="#" class="fw-bold d-block">Sci-Fi Character Concept</a>
                                            <small class="text-muted">Full body concept art...</small>
                                        </td>
                                        <td><a href="#">PixelPioneer</a></td>
                                        <td>Rp2.000.000</td>
                                        <td><span class="badge bg-warning text-dark">In Progress</span></td>
                                        <td>01 Mar 2024, 10:30</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-info" title="View Order Details"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary ms-1" title="Contact Artist"><i class="fas fa-comments"></i></a>
                                        </td>
                                    </tr>
                                    <!-- Example Row 2 -->
                                    <tr>
                                        <td><code>ORD-2024-002</code></td>
                                        <td>
                                            <a href="#" class="fw-bold d-block">Cute Animal Sticker Pack</a>
                                            <small class="text-muted">Pack of 5 custom stickers...</small>
                                        </td>
                                        <td><a href="#">DoodleQueen</a></td>
                                        <td>Rp500.000</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>15 Feb 2024, 14:00</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-info" title="View Order Details"><i class="fas fa-eye"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-warning ms-1" title="Leave a Review"><i class="fas fa-star"></i></button>
                                        </td>
                                    </tr>
                                     <!-- Example Row 3 -->
                                    <tr>
                                        <td><code>ORD-2024-003</code></td>
                                        <td>
                                            <a href="#" class="fw-bold d-block">Abstract Logo Design</a>
                                            <small class="text-muted">Modern logo for a startup...</small>
                                        </td>
                                        <td><a href="#">DesignGuru</a></td>
                                        <td>Rp1.250.000</td>
                                        <td><span class="badge bg-primary">Submitted for Review</span></td>
                                        <td>10 Apr 2024, 09:15</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-info" title="View Order Details"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary ms-1" title="Contact Artist"><i class="fas fa-comments"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination placeholder -->
                        <nav aria-label="Page navigation client orders" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection