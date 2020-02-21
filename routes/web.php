<?php

// このファイルは、リクエストに対してどのコントローラーを使用するかを導くためのファイル

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MicropostsController@index');

//  ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

//  ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

//  ユーザー機能
// ミドルウェアに一度接続して、ユーザー認証を行う
Route::group(['middleware' => ['auth']], function () {
    
    // UsersControllerでは、indexメソッドとshowメソッドのみ操作する
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    
    // ここはテキストのLesson15の10.2の例文を見た方がいい
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        
        // お気に入り登録の一覧表示を取得するためのルートコード
        // 第1引数の'favorites'はページのURL、第2引数は操作をする'コントローラ名'@'メソッド名'、 最後はページを表示するbladeファイルのリンク先の名前を決めている
        Route::get('favorites', 'UsersController@favorites')->name('users.favorites');
    });
    
    // お気に入り登録をするための操作をFavoritesControllerで操作するためのルーティング
    Route::group(['prefix' => 'microposts/{id}'], function () {
        
        // FavoritesControllerのstoreメソッドでお気に入り登録をするためのルーティング
        Route::post('favorite', 'FavoritesController@store')->name('favorites.favorite');
        
        // FavoritesControllerのdestroyメソッドでお気に入り登録を解除するためのルーティング
        Route::delete('unfavorite', 'FavoritesController@destroy')->name('favorites.unfavorite');
    });
    
    // MicropostsControllerでstoreメソッドとdestroyメソッドのみ操作するためのルーティング
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});