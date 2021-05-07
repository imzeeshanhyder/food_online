<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodItems extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'food_items';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'price', 'image', 'description'];

    /**
     * many-to-many relationship method.
     *
     * @return QueryBuilder
     */
    public function category() {
        return $this->belongsTo('App\Models\FoodCategory', 'food_category_id');
    }

}
