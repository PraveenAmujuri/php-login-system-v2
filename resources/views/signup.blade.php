<!DOCTYPE html>
<html>
<body>

<h2>Register</h2>

<form method="POST" action="/register">
    @csrf
    <input type="email" name="userId" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<a href="/">Login</a>

</body>
</html>