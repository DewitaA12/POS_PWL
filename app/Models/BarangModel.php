<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangModel extends Model
{   
    use HasFactory;

    protected $table = 'm_barang'; // Nama tabel di database
    protected $primaryKey = 'barang_id'; // Primary key dari tabel
    protected $foreignKey = 'kategori_id';
    protected $fillable = ['barang_kode', 'barang_nama', 'harga_beli', 'harga_jual']; // Kolom yang boleh diisi secara massal

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}