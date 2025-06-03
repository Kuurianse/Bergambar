@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-list-alt me-2"></i>All Commissions</h2>
        <!-- Button to create commission, visible if authenticated -->
        <a href="#" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i>Create New Commission
        </a>
    </div>

    <!-- Alert messages placeholder -->
    <div class="alert alert-success" role="alert" style="display: none;"> <!-- Toggle display -->
        Success message placeholder.
    </div>
    <div class="alert alert-danger" role="alert" style="display: none;"> <!-- Toggle display -->
        Error message placeholder.
    </div>
    
    <!-- Search and Filter Bar (Placeholder) -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search commissions by title, artist...">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected>Filter by Status...</option>
                <option value="available">Available</option>
                <option value="ordered">Ordered</option>
                <option value="completed">Completed</option>
                <option value="closed">Closed</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary w-100" type="button">Apply Filters</button>
        </div>
    </div>

    <!-- Conditional content: No commissions -->
    <div class="no-commissions-placeholder text-center p-5" style="display: none;"> <!-- Toggle display -->
        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
        <p class="lead">No commissions are currently available.</p>
        <p>Why not create one or check back later?</p>
    </div>

    <!-- Conditional content: Commissions exist (Table View) -->
    <div class="commissions-list-placeholder"> <!-- Toggle display -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Title/Description</th>
                        <th scope="col">Artist</th>
                        <th scope="col">Price (IDR)</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Row 1 -->
                    <tr>
                        <td><img src="https://via.placeholder.com/80x60/007bff/ffffff?text=Comm1" class="img-thumbnail" alt="Commission 1"></td>
                        <td>
                            <a href="#" class="fw-bold d-block">Fantasy Character Design</a>
                            <small class="text-muted">Detailed illustration of your original character...</small>
                        </td>
                        <td><a href="#">ArtistHero123</a></td>
                        <td>Rp750.000</td>
                        <td><span class="badge bg-success">Available</span></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-warning" title="Edit Commission"><i class="fas fa-edit"></i></a> <!-- Show if owner -->
                            <button type="button" class="btn btn-sm btn-danger" title="Delete Commission" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></button> <!-- Show if owner -->
                        </td>
                    </tr>
                    <!-- Example Row 2 -->
                    <tr>
                        <td><img src="https://via.placeholder.com/80x60/28a745/ffffff?text=Comm2" class="img-thumbnail" alt="Commission 2"></td>
                        <td>
                            <a href="#" class="fw-bold d-block">Chibi Icon Set</a>
                            <small class="text-muted">Set of 3 cute chibi icons for your profile...</small>
                        </td>
                        <td><a href="#">CreativeCat</a></td>
                        <td>Rp300.000</td>
                        <td><span class="badge bg-warning text-dark">Ordered</span></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    <!-- Example Row 3 -->
                    <tr>
                        <td><img src="https://via.placeholder.com/80x60/6f42c1/ffffff?text=Comm3" class="img-thumbnail" alt="Commission 3"></td>
                        <td>
                            <a href="#" class="fw-bold d-block">Landscape Painting</a>
                            <small class="text-muted">Digital painting of a serene landscape scene...</small>
                        </td>
                        <td><a href="#">ArtMasterPro</a></td>
                        <td>Rp1.200.000</td>
                        <td><span class="badge bg-primary">Completed</span></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination placeholder -->
        <nav aria-label="Page navigation commissions" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection