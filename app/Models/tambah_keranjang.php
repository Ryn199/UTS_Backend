<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tambah_keranjang extends Model
{
    use HasFactory;

    public $table = 'keranjang';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
