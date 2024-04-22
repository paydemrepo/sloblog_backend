<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogposts extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'img_url',
        'post_category',
        'read_time',
        'content',
        'isPublished'
    ]; 
    protected $primaryKey = 'post_id'; // Replace with the actual primary key column name

}
