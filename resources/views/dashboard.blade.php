<!DOCTYPE html>
<html>
<body>

<h3>Welcome {{ $user->userId }}</h3>

<p>Last Login: {{ $user->last_login }}</p>

<h4>Your Activity</h4>

<ul>
@foreach($logs as $log)
    <li>{{ $log->action }} at {{ $log->created_at }}</li>
@endforeach
</ul>
<a href="/logout">Logout</a>
</body>
</html>