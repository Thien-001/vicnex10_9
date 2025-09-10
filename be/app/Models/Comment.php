<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'comments'; // chỉ định đúng tên bảng

    protected $primaryKey = 'Comment_ID'; // chỉ định đúng khóa chính

    public $timestamps = false; // nếu bảng không có created_at, updated_at

    protected $fillable = [
        'Product_ID',
        'User_ID',
        'Content',
        'Status',
        'Create_at',
        'Update_at',
    ];

    /**
     * Mỗi bình luận thuộc về 1 sản phẩm
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    /**
     * Mỗi bình luận thuộc về 1 người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID'); // bảng user, khóa chính ID
    }

    /**
     * Định nghĩa quy tắc xác thực cho các thuộc tính của mô hình
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'Product_ID' => 'required|exists:products,Product_ID',
            'User_ID'    => 'required|exists:user,ID',
            'Content'    => 'required|string|max:255',
            'Status'     => 'required|boolean',
            'Create_at'  => 'required|date',
            'Update_at'  => 'nullable|date',
        ];
    }
}
