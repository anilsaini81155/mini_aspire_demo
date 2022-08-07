<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model {

    protected $table = "loan";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

}


