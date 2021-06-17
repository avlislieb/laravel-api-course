<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name'
    ];

    public function getResults(String $name)
    {
        if (empty($name)) {
            return $this->get();
        }
        return $this->where('name', 'LIKE', "%{$name}%")->get();
    }
}
