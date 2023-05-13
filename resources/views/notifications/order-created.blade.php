<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table style="width: 100%; border-collapse: collapse; border-spacing: 0; height: auto;">
    <tbody>
    <thead>
    <tr style="background: teal; color: #fff;">
        <td colspan="2">
            Заявка <b>{{ $orderId }}</b>
            <br> От пользователя: <b>{{ $user }}</b>
        </td>
        <td colspan="2">
            Телефон: <br>
            <b>{{ $phone }}</b>
        </td>
    </tr>
    <tr>
        <th style="border: 1px solid teal; font-weight: 600; text-align: left; width: 20%;">Изображение</th>
        <th style="border: 1px solid teal; font-weight: 600; text-align: left;">Название блюда</th>
        <th style="border: 1px solid teal; font-weight: 600; text-align: right; width: 10%;">Количество</th>
        <th style="border: 1px solid teal; font-weight: 600; text-align: right; width: 10%;">Сумма</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dishes as $dish)
        <tr>
            <td style="border: 1px solid teal; padding: 3px; width: 20%; height: 35px; vertical-align: top;">
                <img src={{ $dish['img_src'] }} style="min-width: 100px; max-width: 100px; min-height: 100px; max-height: 100px; border-radius: 4px; object-fit: cover;">
            </td>
            <td style="border: 1px solid teal; padding: 3px; width: 30px; height: 35px; vertical-align: top;">{{ $dish['id'] }}</td>
            <td style="border: 1px solid teal; padding: 3px; width: 10%; height: 35px; vertical-align: top; text-align: right; font-weight: 400;">x {{ $dish['pivot']['count'] }}</td>
            <td style="border: 1px solid teal; padding: 3px; width: 10%; height: 35px; vertical-align: top; text-align: right; font-weight: 600;">{{ $dish['pivot']['price'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3" style="border: 1px solid teal; padding: 3px; text-align: right;">Итого</td>
        <td style="border: 1px solid teal; padding: 3px; text-align: right; font-weight: 600;">{{ $totalPrice }}</td>
    </tr>
    </tfoot>
    </tbody>
</table>
</body>
</html>
