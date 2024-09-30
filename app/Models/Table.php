<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'tables';

    protected $fillable = [
        'number', 'seats', 'status', 'location'
    ];

    // Một bàn có thể có nhiều đặt chỗ
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }
}
