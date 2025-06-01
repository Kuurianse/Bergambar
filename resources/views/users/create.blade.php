<!-- resources/views/users/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Tambah User Baru</title>
</head>
<body>
    <h1>Tambah User Baru</h1>
    
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <label>Username:</label>
        <input type="text" name="name" required><br>

        <label>Nama:</label>
        <input type="text" name="name" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        
        <label>Password:</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
