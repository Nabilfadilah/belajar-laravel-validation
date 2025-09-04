<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        {{-- error directive --}}
        <form action="/form" method="post">
            @csrf
            <label>Username : @error('username')
                    {{ $message }}
                @enderror
                <input type="text" name="username" value="{{ old('username') }}"></label> <br>
            <label>Password : @error('password')
                    {{ $message }}
                @enderror
                <input type="password" name="password" value="{{ old('password') }}"></label><br>
            <input type="submit" value="Login">
        </form>

        {{-- <form action="/form" method="POST">
            @csrf
            <label>Username : <input type="text" name="username"></label>
            <label>Password : <input type="password" name="password"></label>
            <input type="submit" value="Login">
        </form> --}}
    </div>
</body>

</html>
