<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing:0;
            height: auto;
        }
        .table td,
        .table th {
            border: 1px solid teal;
        }
        .table td,
        .table th {
            padding: 3px;
            width: 30px;
            height: 35px;
            vertical-align: top;
        }
        .table th {
            font-weight: 600;
            text-align: left;
        }
        .table .total {
            text-align: right;
        }
        .table .image {
            width: 20%;
        }
        .table .image img {
            --size-img: 100px;
            min-width: var(--size-img);
            max-width: var(--size-img);
            min-height: var(--size-img);
            max-height: var(--size-img);
            border-radius: 4px;
            object-fit: cover;
        }
        .table .amount,
        .table .price  {
            width: 10%;
            text-align: right;
            font-weight: 600;
        }
        .table td.amount {
            font-weight: 400;
        }
        .table .info td {
            background: teal;
            color: #fff;
        }
    </style>

</head>
<body>
<table class="table">
    <tbody>
    <thead>
    <tr class="info">
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
        <th class="image">Изображение</th>
        <th>Название блюда</th>
        <th class="amount">Количество</th>
        <th class="price">Сумма</th>
    </tr>
    </thead>

    <tbody>
    @foreach($dishes as $dish)
        <tr>
            <td class="image">
                <img src={{ $dish['img_src'] }}>
            </td>
            <td>{{ $dish['id'] }}</td>
            <td class="amount">x {{ $dish['pivot']['count'] }}</td>
            <td class="price">500 {{ $dish['pivot']['price'] }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <td colspan="3" class="total">Итого</td>
        <td class="price">{{ $totalPrice }}</td>
    </tr>
    </tfoot>
    </tbody>
</table>

</body>
</html>

