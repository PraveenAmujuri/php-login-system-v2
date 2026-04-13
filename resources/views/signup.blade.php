<!DOCTYPE html>
<html>
<body>
<h2>Register</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

@if ($errors->any())
    <div style="color:red">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="/register">
    @csrf
    <input type="email" name="userId" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<a href="/">Login</a>

</body>
</html>