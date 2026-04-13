<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="outer-card">

    <!-- LEFT PANEL -->
    <div class="visual-panel">
        <div class="quote-label">A Wise Quote</div>

        <div class="visual-text">
            <h1>Welcome<br>Back</h1>
            <p>Login to continue your work and manage your account.</p>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="form-panel">

        <div class="form-content">
            <h2>Login</h2>
            <p class="subtitle">Enter your credentials</p>

            <!-- ERROR -->
            @if(session('error'))
                <p style="color:red">{{ session('error') }}</p>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="userId" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter password" required>

                        <button type="button" class="toggle-btn" onclick="togglePassword()">
                            👁
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Login</span>
                </button>
            </form>
        </div>

        <p class="footer-text">
            Don’t have an account? <a href="/signup">Sign Up</a>
        </p>

    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>