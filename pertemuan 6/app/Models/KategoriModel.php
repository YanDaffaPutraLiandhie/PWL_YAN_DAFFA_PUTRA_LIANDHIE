<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori';
    protected $primaryKey = 'Kategori_id';

    protected $fillable = ['kategori_kode', 'kategori_nama','created_at', 'updated_at'];

    public function barang(): HasMany
    {
        return $this->hasMany(BarangModel::class, 'Kategori_id', 'Kategori_id');
    }
}