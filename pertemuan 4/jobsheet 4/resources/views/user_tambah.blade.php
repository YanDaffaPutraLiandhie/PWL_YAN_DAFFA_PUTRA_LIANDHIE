<body>
    <h1>Form Tambah Data</h1>
    <form action="/user/tambah_simpan" method="post">
        {{ csrf_field() }}
        <label for="">Username</label>
        <input type="text" name="username" placeholder="Masukkan Username">
        <br>
        <label for="">Nama</label>
        <input type="text" name="nama" placeholder="Masukkan Nama">
        <br>
        <label for="">Password</label>
        <input type="password" name="password" placeholder="Masukkan Password">
        <br>
        <label for="">Level ID</label>
        <input type="number" name="level_id" placeholder="Masukkan Level ID">
        <br><br>
        <input type="submit" class="btn btn-success" value="Simpan">
    </form>
</body>