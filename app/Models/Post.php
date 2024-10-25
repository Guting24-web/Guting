<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comment::class); 
        
    }
    
    protected $fillable = ['title', 'content', 'author', 'is_published'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}