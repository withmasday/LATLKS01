<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'table_reviews';
    protected $fillable = [
        'review',
        'user_id',
        'book_id',
    ];
}
