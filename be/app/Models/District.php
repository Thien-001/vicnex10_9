<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $primaryKey = 'code'; // nếu trường code là khóa chính
    public $timestamps = false;

    // Nếu muốn lấy các phường/xã thuộc quận/huyện này
    // public function wards()
    // {
    //     return $this->hasMany(Ward::class, 'district_code', 'code');
    // }
}
