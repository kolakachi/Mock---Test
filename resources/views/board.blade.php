<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="margin: 0px;">
    <div id="app">
        @csrf
        <example-component></example-component>
     </div>
     <input type="hidden" id="columns" value="{{ json_encode($columns) }}">
     <input type="hidden" id="boardId" value="{{$boardId}}">
     <input type="hidden" id="store-url" value="{{ route('user.board.store') }}">

    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>