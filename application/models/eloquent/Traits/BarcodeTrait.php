<?php namespace App\Models\Traits;

trait BarcodeTrait
{
    public function getBarcodeAttribute()
    {
        return sprintf("%06d", $this->id);
    }

}