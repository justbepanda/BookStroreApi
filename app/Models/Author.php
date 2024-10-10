<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'info', 'birth_date'];
    protected $appends = ['books_count'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getBooksCountAttribute()
    {
        return $this->books()->count();
    }

}
