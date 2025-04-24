<?php

namespace App\Models;

use App\Models\KategoriModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StokModel;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';
    public $timestamps = false;

    protected $fillable = ['barang_kode', 'barang_nama', 'harga_jual', 'harga_beli', 'kategori_id'];

    public function stok()
    {
        return $this->hasMany(StokModel::class, 'barang_id', 'barang_id');
    }

    public function getStokAvailableAttribute()
    {
        return $this->stok()->sum('stok_jumlah');
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
