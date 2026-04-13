<!DOCTYPE html>
<html>
<body>

<h2>Login</h2>

@if(session('error'))
<p style="color:red;">{{ session('error') }}</p>
@endif

<form method="POST" action="/login">
    @csrf
    <input type="email" name="userId" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<a href="/signup">Signup</a>

</body>
</html>