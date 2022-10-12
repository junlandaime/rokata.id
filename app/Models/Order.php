<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    protected $appends = ['status_label', 'ref_status_label', 'commission'];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-gradient-secondary">Baru</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge bg-gradient-primary">Dikonfirmasi</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge bg-gradient-info">Proses</span>';
        } elseif ($this->status == 3) {
            return '<span class="badge bg-gradient-warning">Dikirim</span>';
        }
        return '<span class="badge bg-gradient-success">Selesai</span>';
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function return()
    {
        return $this->hasOne(OrderReturn::class);
    }

    public function getRefStatusLabelAttribute()
    {
        if ($this->ref_status == 0) {
            return '<span class="badge bg-gradient-secondary">Pending</span>';
        }
        return '<span class="badge bg-gradient-success">Dicairkan</span>';
    }

    public function getCommissionAttribute()
    {
        //komisinya adalah 10$ dari subtotal
        $commission = ($this->subtotal * 10) / 100;
        //tapi jika lebih dari 10.000 maka yang dikembalikan adalah 10.000
        return $commission > 10000 ? 10000 : $commission;
    }
}
