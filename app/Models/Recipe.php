<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['title', 'description', 'prep', 'servings', 'ingredients','instructions', 'image_url'];
}
