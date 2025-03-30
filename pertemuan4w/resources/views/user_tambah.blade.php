<body>
    <h1>Form Tambah Data</h1>
    <form action="/user/tambah_simpan" method="POST">
        {{ csrf_field() }} 
        
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan Username" required>
        <br>

        <label>Nama</label>
        <input type="text" name="nama" placeholder="Masukkan Nama" required>
        <br>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan Password" required>
        <br>

        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukkan Level ID" required>
        <br><br>

        <input type="submit" class="btn btn-success" value="Simpan">
    </form>
</body>
