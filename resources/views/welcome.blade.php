<!-- ここはログイン認証前後の一覧画面を表示するファイル --> 

@extends('layouts.app')

@section('content')
    
    <!-- ログイン認証が出来ていればelseまでを表示する -->
    @if (Auth::check())
        <div class="row">
            
            <!-- 12カラムの内、カラムを4本使う -->
            <aside class="col-sm-4">
                
                <!-- userフォルダの中のcardのbladeファイルを呼び出して、ユーザー認証を行う -->
                @include('users.card', ['user' => Auth::user()])
            </aside>
            
            <!-- 12カラムの内、4本をカードの表示に使っているので、残りの8本を使う -->
            <div class="col-sm-8">
                
                <!-- もしユーザー認証が出来れば、ユーザーIDを取得する -->
                @if (Auth::id() == $user->id)
                    
                    <!-- micropostsフォルダのstoreメソッドに送信する -->
                    {!! Form::open(['route' => 'microposts.store']) !!}
                        
                        <!-- form-groupを入れて、複数の入力欄のレイアウトを整える -->
                        <div class="form-group">
                            
                            <!-- 投稿欄を実装するコード(oldで、前の情報を残しておける) -->
                            {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '']) !!}
                            
                            <!-- 投稿内容を送信するボタンを設置するコード(btn-primaryは青色で、btn-blockはボタンのサイズをカラムに合わせるという意味 -->
                            {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block']) !!}
                        </div>
                    {!! Form::close() !!}
                @endif
                
                <!-- もし記事が投稿されたら、 -->
                @if (count($microposts) > 0)
                
                <!-- micropostsフォルダのmicropostsブレードファイルを呼び出して表示する -->
                    @include('microposts.microposts', ['microposts' => $microposts])
                @endif
            </div>
        </div>
        
    <!-- ログイン認証が出来ていなければ、以下を表示する -->
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Microposts</h1>
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection