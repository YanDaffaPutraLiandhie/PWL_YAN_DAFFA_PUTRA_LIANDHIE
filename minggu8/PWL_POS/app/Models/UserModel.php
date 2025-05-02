<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Monolog\Level;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id'; // Mendefinisikan primary key dari tabel yang digunakan.

    /**
     * 
     * 
     *  @var array
    */
    
    // ============ jobsheet 4 ===================
    // protected $fillable = ['level_id', 'username', 'nama', 'password']; Praktikkum 1

    // protected $fillable = ['level_id', 'username', 'nama']; //pada praktikum 2.1 terdapat error dibagian ini yang akan berpengaruh pada UserController, 
                                                            //jadi diberikan solusi pada variable fillable, dengan menambahkan 'password' didalamnya.
    // protected $fillable = ['level_id', 'username', 'nama', 'password'];

    //praktikkm 2.7 no. 1
    // public function level(): BelongsTo
    // {
    //     return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    // }


    // ========= Jobsheet 7 =================
    protected $fillable = ['level_id', 'username', 'nama', 'password', 'created_at', 'updated_at'];

    protected $hidden   = ['password'];  // jangan ditampilkan saat select

    protected $casts    = ['password' => 'hashed'];  // casting password agar otomatis di hash

    /**
     * relasi ke tabel level
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    // ========= Jobsheet 7 prak-2 no-1 ============

    /**
     * mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    /**
     * cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }


    // ========= Jobsheet 7 prak-3 no-1 ============
    /**
     * mendapatkan kode role
     */
    public function getRole()
    {
        return $this->level->level_kode;
    }
}