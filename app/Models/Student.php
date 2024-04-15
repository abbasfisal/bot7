<?php

namespace App\Models;

use Database\Factories\studentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected static function newFactory()
    {
        return studentFactory::new();
    }

}
