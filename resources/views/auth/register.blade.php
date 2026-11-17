<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
</head>
<body>
    <h1>Form Registrasi</h1>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('register.submit') }}" method="POST">
        @csrf
        <label>Nama:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        <button type="submit">Daftar</button>
    </form>

    <br>
    <a href="{{ route('login') }}">← Kembali ke Login</a>
</body>
</html>
