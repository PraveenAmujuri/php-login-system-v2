<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="outer-card">

    <!-- LEFT PANEL -->
    <div class="visual-panel">
        <div class="quote-label">A Wise Quote</div>

        <div class="visual-text">
            <h1>Create Your<br>Account</h1>
            <p>Start your journey. Build something meaningful and stay consistent every day.</p>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="form-panel">

        <div class="form-content">
            <h2>Create Account</h2>
            <p class="subtitle">Enter your details to get started</p>

            @if(session('error'))
                <p style="color:red">{{ session('error') }}</p>
            @endif

            @if ($errors->any())
                <p style="color:red">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="/register">
                @csrf

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="userId" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>

                        <button type="button" class="toggle-btn" onclick="togglePassword()">👁</button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Sign Up</span>
                </button>
            </form>
        </div>

        <p class="footer-text">
            Already have an account? <a href="/">Login</a>
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