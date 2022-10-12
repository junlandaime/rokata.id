<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['status_label'];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-gradient-secondary">Menunggu Konfirmasi</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge bg-gradient-warning">Ditolak</span>';
        }
        return '<span class="badge bg-gradient-success">Selesai</span>';
        
    }
}
