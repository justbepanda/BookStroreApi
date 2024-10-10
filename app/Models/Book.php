<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['author_id', 'title', 'annotation', 'publication_date', 'character_count'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function updateCharacterCount()
    {
        $this->character_count = $this->chapters()->sum('character_count');
        $this->save();
    }
}
