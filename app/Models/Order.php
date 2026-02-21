<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property float $total
 * @property string|null $payment_method
 * @property string|null $payment_id
 * @property string|null $payment_status
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $notes
 * @property string|null $lat
 * @property string|null $lng
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Order extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
