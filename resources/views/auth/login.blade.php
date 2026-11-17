<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="login-box">
        <h2>Login Pemilik</h2>

        @if ($errors->any())
            <p class="error-message">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <label>Email:</label>
            <input type="text" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
