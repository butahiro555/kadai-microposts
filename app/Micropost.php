<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];
    
    // 1つの記事を投稿できるのは1人のUserしか存在しない(belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // お気に入り記事は複数のユーザーが登録できる
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }
}
