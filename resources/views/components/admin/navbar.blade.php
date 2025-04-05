<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container-fluid">
        <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="d-flex align-items-center">
            <h5 class="mb-0">@yield('page_title', 'Dashboard')</h5>
        </div>
        
        <div class="d-flex align-items-center">
            <!-- Search Form -->
            <form class="me-3 d-none d-md-flex">
                <div class="input-group">
                    <input type="text" class="form-control border-end-0" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-outline-secondary border-start-0" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- Notifications Dropdown -->
            <div class="dropdown me-3">
                <a class="nav-link position-relative" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="min-width: 300px;">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle p-2 text-white">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">New user registered</p>
                                <small class="text-muted">5 minutes ago</small>
                            </div>
                        </div>
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success rounded-circle p-2 text-white">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">New exam created</p>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                        </div>
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning rounded-circle p-2 text-white">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">System alert</p>
                                <small class="text-muted">Yesterday</small>
                            </div>
                        </div>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                </ul>
            </div>
            
            <!-- User Dropdown -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" alt="Admin" class="rounded-circle me-2" width="32" height="32">
                    <span class="d-none d-md-inline">Admin User</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
