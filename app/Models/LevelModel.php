<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Match the new table name
    protected $primaryKey = 'level_id'; // Match the PK name
    protected $fillable = ['level_kode', 'level_nama'];
}
