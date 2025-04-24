<?php
/*namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModel;
use App\Models\User; // Jika kamu menggunakan model default User

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok'; // pastikan nama tabel sesuai
    protected $primaryKey = 'stok_id'; // pastikan primary key sesuai

    protected $fillable = [
        'barang_id',
        'user_id',
        'stok_tanggal',
        'stok_jumlah',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id'); // pastikan foreign key sesuai
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // ganti 'UserModel' menjadi 'User' jika menggunakan model default
    }
}*/
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModel;
use App\Models\UserModel; // Jika menggunakan UserModel yang disesuaikan

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok'; // pastikan nama tabel sesuai
    protected $primaryKey = 'stok_id'; // pastikan primary key sesuai

    protected $fillable = [
        'barang_id',
        'user_id',
        'stok_tanggal',
        'stok_jumlah',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id'); // pastikan foreign key sesuai
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id'); // pastikan foreign key sesuai
    }
}
