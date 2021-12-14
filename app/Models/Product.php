<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'sanpham';
    protected $primarykey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    public function Category()
    {
        return $this->belongsTo('App\Models\Category', 'id_dm', 'id');
    }
    public function Brand()
    {
        return $this->belongsTo('App\Models\Brand', 'id_th', 'id');
    }
}