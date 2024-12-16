<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = [
        'user_id',
        'title',
        'image',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function createPost($data)
    {
        $imagePath = $data['image']->store('images', 'public');
        $data['image'] = basename($imagePath);

        return self::create($data);
    }

    public function updatePost($data)
    {
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('images', 'public');
            $data['image'] = basename($imagePath);
        }

        return $this->update($data);
    }
}
