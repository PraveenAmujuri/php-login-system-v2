<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .dashboard-container {
            height: calc(100vh - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-card {
            width: 420px;
            padding: 40px;
            border-radius: 24px;
            background: white;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            text-align: center;
        }

        .dashboard-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .activity-list {
            text-align: left;
            margin: 20px 0;
            max-height: 150px;
            overflow-y: auto;
            font-size: 13px;
        }

        .activity-list li {
            margin-bottom: 6px;
            color: #374151;
        }

        .logout-btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 30px;
            border: 1.5px solid #d1d5db;
            background: transparent;
            color: #111827;
            font-weight: 600;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .logout-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            background: #4f46e5;
            transform: scaleX(0);
            transform-origin: left;
            transition: 0.3s;
            z-index: 0;
        }

        .logout-btn span {
            position: relative;
            z-index: 1;
        }

        .logout-btn:hover::before {
            transform: scaleX(1);
        }

        .logout-btn:hover {
            color: white;
            border-color: #4f46e5;
        }
    </style>
</head>

<body>

<div class="outer-card">

    <!-- LEFT PANEL -->
    <div class="visual-panel">
        <div class="quote-label">Dashboard</div>

        <div class="visual-text">
            <h1>Welcome Back</h1>
            <p>You're successfully logged in. Continue your work.</p>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="form-panel">

        <div class="dashboard-container">

            <div class="dashboard-card">

                <h2>Hello,</h2>

                <p class="user-email">
                    {{ $user->userId }}
                </p>

                <p>Last Login: {{ $user->last_login }}</p>

                <h4>Your Activity</h4>

                <ul class="activity-list">
                    @foreach($logs as $log)
                        <li>{{ $log->action }} at {{ $log->created_at }}</li>
                    @endforeach
                </ul>

                <a href="/logout" class="logout-btn">
                    <span>Logout</span>
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>