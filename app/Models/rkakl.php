<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rkakl extends Model
{
    protected   $table = 'rkakl';
    use HasFactory;
    protected $guarded = [];
    public function datarab()
    {
        return $this->hasMany(rab::class, 'id_rkakl', 'id');
    }
}
