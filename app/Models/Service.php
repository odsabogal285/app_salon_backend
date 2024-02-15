<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function appointments ()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services');
    }
}
