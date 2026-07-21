<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Karang Taruna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        h1,h2,h3,h4,h5 { font-family: 'Poppins', sans-serif; }
        body { background: #F1F5F9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }

        .login-wrapper { width: 100%; max-width: 440px; padding: 20px; }
        .login-card {
            background: white; border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #1B2537 0%, #1E3A8A 100%);
            padding: 40px 36px 32px;
            text-align: center;
        }
        .login-logo {
            width: 60px; height: 60px; border-radius: 18px;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .login-title { color: white; font-weight: 700; font-size: 1.4rem; margin-bottom: 4px; }
        .login-subtitle { color: rgba(255,255,255,0.6); font-size: 0.85rem; }
        .login-body { padding: 36px; }

        .form-label { font-weight: 600; font-size: 0.85rem; color: #374151; margin-bottom: 7px; }
        .form-control {
            border: 1.5px solid #E2E8F0; border-radius: 12px;
            padding: 12px 16px; font-size: 0.9rem;
            transition: all 0.2s; background: #F8FAFC;
        }
        .form-control:focus { border-color: #4154F1; box-shadow: 0 0 0 3px rgba(65,84,241,0.1); background: white; outline: none; }
        .input-group .form-control { border-right: none; border-radius: 12px 0 0 12px; }
        .input-group-text {
            background: #F8FAFC; border: 1.5px solid #E2E8F0; border-left: none;
            border-radius: 0 12px 12px 0; cursor: pointer; transition: all 0.2s;
        }
        .input-group:focus-within .input-group-text { border-color: #4154F1; background: white; }

        .btn-login {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #4154F1, #7C3AED);
            color: white; border: none; border-radius: 12px;
            font-weight: 700; font-size: 0.95rem;
            transition: all 0.3s; letter-spacing: 0.3px;
        }
        .btn-login:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(65,84,241,0.4); color: white; }
        .btn-login:active { transform: translateY(0); }

        .alert-err { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; border-radius: 12px; padding: 12px 16px; font-size: 0.87rem; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #64748B; text-decoration: none; font-size: 0.85rem; transition: color 0.2s; }
        .back-link a:hover { color: #4154F1; }

        .form-check-input:checked { background-color: #4154F1; border-color: #4154F1; }

        /* Decorative circles */
        .deco-circle {
            position: fixed; border-radius: 50%;
            background: linear-gradient(135deg, rgba(65,84,241,0.15), rgba(124,58,237,0.1));
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="deco-circle" style="width:400px;height:400px;top:-100px;right:-100px;"></div>
    <div class="deco-circle" style="width:300px;height:300px;bottom:-80px;left:-80px;"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="bi bi-star-fill text-white" style="font-size:1.6rem;"></i>
                </div>
                <div class="login-title">Karang Taruna</div>
                <div class="login-subtitle">Silahkan masuk untuk mengakses dashboard</div>
            </div>

            <div class="login-body">
                @if(session('error'))
                    <div class="alert-err mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-4 p-3 d-flex align-items-center gap-2" style="background:#ECFDF5;border:1px solid #A7F3D0;color:#065F46;border-radius:12px;font-size:0.87rem;">
                        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-envelope me-1 text-primary"></i>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="admin@karangtaruna.com" required autofocus>
                        @error('email')
                            <div class="text-danger" style="font-size:0.8rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-lock me-1 text-primary"></i>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                id="passwordInput" placeholder="••••••••" required>
                            <span class="input-group-text" onclick="togglePassword()">
                                <i class="bi bi-eye" id="eyeIcon" style="color:#94A3B8;font-size:0.9rem;"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-danger" style="font-size:0.8rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember" style="font-size:0.85rem;color:#64748B;">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ url('/') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke Website</a>
                </div>
            </div>
        </div>

        <div class="text-center mt-3" style="color:#94A3B8;font-size:0.8rem;">
            &copy; {{ date('Y') }} Karang Taruna
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
