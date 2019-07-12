<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Product extends Model
{
    use UsesUuid;

    protected $guarded = ['uuid'];
}
