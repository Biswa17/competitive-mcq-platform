<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCQ Platform - Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e6f2ff; /* Light blue background */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .text-primary {
            color: #0d6efd !important;
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo h1 {
            color: #0d6efd;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-logo">
                    <h1>MCQ Platform</h1>
                </div>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold">Admin Login</h4>
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                        <div class="alert alert-danger mb-3">
                            {{ session('error') }}
                        </div>
                        @endif
                        
                        @if(session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div>
                        @endif
                        
                        @if($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary"><i class="fas fa-envelope text-primary"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary"><i class="fas fa-lock text-primary"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-bold">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
