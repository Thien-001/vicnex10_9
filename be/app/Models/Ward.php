<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'wards';
    protected $primaryKey = 'code'; // nếu trường code là khóa chính
    public $timestamps = false; // nếu bạn không sử dụng trường created_at và updated_at
}
