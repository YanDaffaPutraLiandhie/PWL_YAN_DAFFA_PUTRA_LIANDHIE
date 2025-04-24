<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; // Pastikan menggunakan 'Authenticatable' jika ini model user
use Monolog\Level;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; // Nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id'; // Primary key dari tabel yang digunakan

    /**
     * =======================================
     * Variabel untuk pengaturan Eloquent
     * =======================================
     */
    protected $fillable = ['level_id', 'username', 'nama', 'password', 'created_at', 'updated_at'];

    protected $hidden   = ['password'];  // Untuk menyembunyikan password saat select

    protected $casts    = ['password' => 'hashed'];  // Casting password agar otomatis di-hash

    /**
     * Relasi ke Level (jika ada relasi ke level model)
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id'); // Sesuaikan foreign key dan primary key jika perlu
    }

    /**
     * Mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    /**
     * Mengecek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->level->level_kode;
    }
}
