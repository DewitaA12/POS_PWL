<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier'; // Match the new table name
    protected $primaryKey = 'supplier_id'; // Match the PK name
    protected $fillable = ['supplier_kode', 'supplier_nama','alamat'];
}
