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

        <form action="/form" method="POST">
            @csrf
            <label>Username : <input type="text" name="username"></label>
            <label>Password : <input type="password" name="password"></label>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>
