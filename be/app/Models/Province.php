<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $primaryKey = 'code'; // nếu trường code là khóa chính
    public $timestamps = false;

    // Nếu muốn lấy các quận/huyện thuộc tỉnh này
    // public function districts()
    // {
    //     return $this->hasMany(District::class, 'province_code', 'code');
    // }
}
