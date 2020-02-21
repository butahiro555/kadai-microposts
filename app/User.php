<?php

// このファイルはユーザーインスタンスを作成するためのモデル

// 名前空間
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    // Userは複数の記事を投稿できるため、複数のmicroposts(hasMany)を持つことが出来る(1対多)
    public function microposts()
    {   
        // $thisは変数や関数を呼び出されたインスタンス自身を指すという方が、より正しい理解となります。(Lesson.7)
        // 今回でいえば、$thisはクラスを定義したインスタンス(ユーザー)を表している
        return $this->hasMany(Micropost::class);
    }
    
    // Userは複数の投稿記事をお気に入り登録することができる(多対多)
    public function favorites()
    {
        // Micoropostクラスにアクセスして、投稿記事のidをfavoritesテーブルのmicropost_idに入れる。
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    // Userは複数のユーザーをフォローできる(多対多)
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    // Userは複数のユーザーにフォローされることができる(多対多)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
        
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist || $its_me) {
            
            // 既にフォローしていれば何もしない
            return false;
        } else {
            
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;
    
        if ($exist && !$its_me) {
            
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    public function favorite($micropostId)
    {
        // 既にお気に入り登録しているかの確認
        $exist = $this->is_registering($micropostId);
    
        if ($exist) {
            
            // 既に登録していれば何もしない
            return false;
        } else {
            
            // 未登録であれば登録する
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    public function unfavorite($micropostId)
    {
        // 既に登録しているかの確認
        $exist = $this->is_registering($micropostId);
    
        if ($exist) {
            
            // 既に登録していれば登録を外す
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            
            // 未登録であれば何もしない
            return false;
        }
    }
    
    public function is_registering($micropostId)
    {
        // ''はカラム、そのカラムに変数を渡す
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
    
}