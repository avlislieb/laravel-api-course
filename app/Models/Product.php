<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'category_id'
    ];

    public function getResults($data, $total)
    {
        return $this->where(function ($query) use ($data) {
            if (isset($data['filter'])) {
                $filter = $data['filter'];
                $query->where('name', $filter)
                    ->orWhere('description', 'LIKE', "%{$filter}%");
            }

            if (isset($data['name'])) {
                $filter = $data['name'];
                $query->where('name', $filter);
            }

            if (isset($data['description'])) {
                $filter = $data['description'];
                $query->where('description', 'LIKE', "%{$filter}%");
            }
        })->paginate($total);
    }
}
