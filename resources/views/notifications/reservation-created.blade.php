<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservation created #{{ $id }}</title>
</head>
<body>
<div>
    <p>Имя: <b>{{ $name }}</b></p>
    <p>Телефон: <b>{{ $phone }}</b></p>
    <p>Кол-во гостей: <b>{{ $guests }}</b></p>
    <p>Дата для бронирования: <b>@php((new \Carbon\Carbon($date))->toDateString())</b></p>
    <p>Время для бронирования: <b>@php((new \Carbon\Carbon($date))->toTimeString())</b></p>
</div>
</body>
</html>
