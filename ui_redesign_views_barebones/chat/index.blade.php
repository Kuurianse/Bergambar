@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 1.5%">
    <h2 class="mb-4">Recent Messages</h2>
    
    <div class="list-group">
        <!-- Example Chat Item 1 -->
        <a href="#" class="list-group-item list-group-item-action mb-2 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <img src="https://via.placeholder.com/40/007bff/ffffff?text=AU" class="rounded-circle me-2" alt="User Avatar">
                        <strong class="h5 mb-0">Artist User One</strong>
                    </div>
                    <p class="mb-0 text-muted">
                        Okay, I will start working on the sketch soon!
                    </p>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block mb-1">2 hours ago</small>
                    <span class="badge bg-primary rounded-pill">3</span> <!-- Unread messages count -->
                </div>
            </div>
        </a>

        <!-- Example Chat Item 2 -->
        <a href="#" class="list-group-item list-group-item-action mb-2 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <img src="https://via.placeholder.com/40/28a745/ffffff?text=CU" class="rounded-circle me-2" alt="User Avatar">
                        <strong class="h5 mb-0">Client User Two</strong>
                    </div>
                    <p class="mb-0 text-muted">
                        Thanks for the update, looking forward to it. This is a slightly longer last message to see how it wraps.
                    </p>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block mb-1">Yesterday</small>
                    <!-- No unread messages badge if count is 0 -->
                </div>
            </div>
        </a>

        <!-- Example Chat Item 3 (No unread) -->
        <a href="#" class="list-group-item list-group-item-action mb-2 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <img src="https://via.placeholder.com/40/ffc107/000000?text=SU" class="rounded-circle me-2" alt="User Avatar">
                        <strong class="h5 mb-0">Support User</strong>
                    </div>
                    <p class="mb-0 text-muted">
                        You're welcome!
                    </p>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block mb-1">3 days ago</small>
                </div>
            </div>
        </a>

        <!-- Placeholder for No Chats -->
        <div class="no-chats-placeholder" style="display: none;"> <!-- Toggle display:block if no chats -->
            <div class="text-center p-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <p class="lead">No recent messages.</p>
                <p>Start a conversation with an artist or client!</p>
            </div>
        </div>
    </div>

    <!-- Optional: Pagination if many chats -->
    <nav aria-label="Page navigation example" class="mt-4" style="display: none;"> <!-- Toggle display:flex if pagination needed -->
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
</div>
@endsection