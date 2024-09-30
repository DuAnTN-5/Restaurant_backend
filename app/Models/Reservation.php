<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'user_id', 'table_id', 'reservation_date', 'guest_count', 'status', 'special_requests'
    ];
    
    // Thêm các cột ngày tháng vào mảng $dates
    protected $dates = ['reservation_date', 'created_at', 'updated_at'];
    // Một đặt chỗ thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Một đặt chỗ thuộc về một bàn
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
