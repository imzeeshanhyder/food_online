<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodCategory extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'food_categories';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'sort_order', 'description'];

    /**
     * many-to-many relationship method.
     *
     * @return QueryBuilder
     */
    public function foodItems() {
        return $this->hasMany('App\Models\FoodItems');
    }

}
