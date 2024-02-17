<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends BaseModel
{
    use HasFactory;

    protected $fillable = [
      'user_id', 'date', 'time', 'total_amount'
    ];

    protected $appends = ['date_formatted'];
    protected $dates = ['date'];

    public function dateFormatted () : Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                return Carbon::create($this->date)->format('d/m/Y');
            }
        );
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) =>  Carbon::create($value)->format('H:i'),
            // set: fn (string $value) => strtolower($value),
        );
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function services ()
    {
        return $this->belongsToMany(Service::class, 'appointment_services');
    }
}
