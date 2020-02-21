    <!-- 今回はmicropostのIDを渡してやれば良い -->
    <!-- Auth::user()でユーザーインスタンスが出来ている。(User::find(id)のイメージ) -->
    @if (Auth::user()->is_registering($micropost->id))
        {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfavorites', ['class' => "btn btn-secondary btn-sm"]) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
            {!! Form::submit('Favorites', ['class' => "btn btn-success btn-sm"]) !!}
        {!! Form::close() !!}
    @endif