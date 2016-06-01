@extends('outside')

@section('content')
    <form method="POST">
        {!! csrf_field() !!}

        <div class="form-group">
            <label>
            @if (\Statamic\API\Config::get('users.login_type') === 'email')
                {{ trans_choice('cp.emails', 1) }}
            @else
                {{ trans('cp.username') }}
            @endif
            </label>
            <input type="text" class="form-control" name="username" value="{{ old('username') }}" autofocus>
        </div>

        <div class="form-group">
            <label>{{ trans_choice('cp.passwords', 1) }}</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>


        <div class="form-group">
            <input type="checkbox" class="form-control" name="remember" id="checkbox-0">
            <label for="checkbox-0" class="normal">{{ trans('cp.remember_me') }}</label>
        </div>

        <div>
            <button type="submit" class="btn btn-outside btn-block">{{ trans('cp.login') }}</button>
        </div>
    </form>
@endsection
