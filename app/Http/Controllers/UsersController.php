<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Micropost;

// 各ユーザーの一覧画面(今回のアプリで言えば、ViewProfileの画面の一覧取得)
// Controllerクラスを継承して、UsersControllerを定義する
class UsersController extends Controller
{   
    // indexメソッドを定義する
    public function index()
    {   
        // $usersプロパティは括弧内で表示方法を定義する(Userモデルにアクセスして、ソートの順番をid順にして(orderBy)、10件で1ページを表示する(paginate(10)))
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // usersフォルダのindexブレイドファイルを表示する
        return view('users.index', [
            
            // Viewprofileはユーザーごとに見せるページを変更する(例：$usersのIDが1なら、/users/1)
            'users' => $users,
        ]);
    }
    
    // showメソッドを定義する(タイムライン)
    public function show($id)
    {   
        // $userプロパティは括弧内で定義する(ユーザーモデルにアクセスして、idを取得する)
        $user = User::find($id);
        
        // $micropostsプロパティは括弧内で定義する(ユーザーIDを投稿記事に反映させて、日付の降順でソートし表示する)
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        // カラムにユーザーIDと投稿記事のIDを入力する
        $data = [
            'user' => $user,
            'microposts' => $microposts,
        ];

        $data += $this->counts($user);

        return view('users.show', $data);
    }
    
    // 自分がフォローしているユーザーの一覧取得
    public function followings($id)
    {   
        
        $user = User::find($id);
        
        // Userクラスのfollowingsメソッドを呼び出している。
        $followings = $user->followings()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followings,
        ];

        $data += $this->counts($user);

        return view('users.followings', $data);
    }
    
    // 自分をフォローしているユーザーの一覧取得
    public function followers($id)
    {
        $user = User::find($id);
        $followers = $user->followers()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followers,
        ];

        $data += $this->counts($user);

        return view('users.followers', $data);
    }
    
    // お気に入り登録している投稿記事の一覧取得
    public function favorites($id)
    {   
        // tinkerコマンドでモデルの情報を呼び出している。
        // 任意のユーザー情報を呼び出して、
        $user = User::find($id);
        
        // そのユーザーのお気に入りページを表示する
        // User.phpのfavoritesメソッド参照してカラムデータを取得
        $favorites = $user->favorites()->paginate(10);
        
        // 配列にユーザー情報と、お気に入りのカラムデータを入れる
        $data = [
            'user' => $user,
            'favorites' => $favorites,
        ];
        
        // $dataの情報を使って、ユーザーのお気に入りの数を表示する
        $data += $this->counts($user);
        
        // $dataの情報をusers.favoritesに渡してviewで表示する
        return view('users.favorites', $data);

    }
}
