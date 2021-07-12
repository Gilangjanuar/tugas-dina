<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $table = 'sales';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'car_id',
        'sale_date',
        'created_at',
        'updated_at'
    ];

    /**
     * Relation To Sales
     */
    public function cars()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    /**
     * Formatting Tanggal.
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        if ($this->attributes['created_at'] === null) {
            return null;
        }

        return Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s');
    }

    /**
     * Formatting Tanggal.
     *
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        if ($this->attributes['updated_at'] === null) {
            return null;
        }

        return Carbon::parse($this->attributes['updated_at'])->format('d-m-Y H:i:s');
    }

    /**
     * Formatting Tanggal.
     *
     * @return string
     */
    public function getSaleDate()
    {
        if ($this->attributes['sale_date'] === null) {
            return null;
        }

        return Carbon::parse($this->attributes['sale_date'])->format('d F Y');
    }
}
