<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostData extends Model
{
    protected $table = "post_data";
    protected $fillable = ["name"];
}
