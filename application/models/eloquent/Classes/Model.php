<?php

namespace App\Models\Classes;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    public function toArray()
    {
        if(isset($this->nested) && count($this->nested))
        {
            foreach ($this->nested as $attribute)
            {
                $this->hidden[] = "{$attribute}_id";
                $this->$attribute;
            }
        }

        return parent::toArray();
    }

}