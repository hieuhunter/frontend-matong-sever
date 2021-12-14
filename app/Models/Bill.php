<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'hoadon';
    protected $primarykey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $guarded = [];
    
    public function CTBill()
    {
        return $this->hasMany('App\Models\CTBill', 'id_hd', 'id');
    }
    public function User()
    {
        return $this->belongsTo('App\Models\User', 'id_kh', 'id');
    }
}
