@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Picqer\Barcode\BarcodeGeneratorPNG;
    use Picqer\Barcode\BarcodeGenerator;
    $generator = new BarcodeGeneratorPNG();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--<link rel="stylesheet" href="style3.css" />-->
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            margin-top: 0px;
        }

        .main-con {
            width: 21cm;
            padding: 0px;
            margin-top: 2px;
            display: block;
        }

        .table {
            --bs-table-color: var(--bs-body-color);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: var(--bs-body-color);
            --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
            --bs-table-active-color: var(--bs-body-color);
            --bs-table-active-bg: rgba(0, 0, 0, 0.1);
            --bs-table-hover-color: var(--bs-body-color);
            --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
            width: 100%;
            margin-bottom: 1rem;
            color: var(--bs-table-color);
            vertical-align: top;
            border-color: var(--bs-table-border-color)
        }

        .main-con td {
            background-color: white;
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-.5 * var(--bs-gutter-x));
            margin-left: calc(-.5 * var(--bs-gutter-x))
        }

        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * .5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            margin-top: var(--bs-gutter-y)
        }

        .col-6 {
            flex: 0 0 auto;
            width: 50%
        }

        .main-con .police {
            direction: rtl;
            padding: 0;
        }

        .main-con .police table {
            margin: 5px 4px;
        }

        .main-con .police td {
            border: 1px solid black;
        }

        .address {
            width: 80%;
            height: 100px;
            border: 2px solid black;
            padding: 5px 5px;
        }

        .address span {
            width: 280px;
            display: block;
        }

        .address span:nth-child(1) {
            height: 70%;
        }

        .address h1 {
            font-size: 16px;
            display: inline;
        }

        .address p {
            display: inline;
            font-size: 14px;
            font-weight: 500;
        }

        .qr1 {
            width: 20%;
            border: 2px solid black;
        }

        .qr1 div {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr1 h1 {
            font-size: 16px;
            text-align: center;
            font-weight: 700;
            margin-top: -5px;
        }

        .police-num {
            height: 35px;
        }

        .police-num td {
            padding: 1 5px;
        }

        .police-num td h1 {
            font-size: 17px;
            width: 100%;
            text-align: center;
            margin: 0;
            font-weight: 900;
        }

        .police-num td.logo div {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .police-num td.logo img {
            margin: 0 7px;
        }

        .cust-name td {
            font-size: 14px;
            font-weight: 700;
            padding: 2px;
            text-align: center;
            height: 25px;
        }

        .cust-name-input td {
            font-size: 14px;
            font-weight: 700;
            padding: 2px;
            text-align: center;
            height: 25px;
        }

        .cust-name-input td:nth-child(1) {
            width: 196px;
        }

        .numbers td {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            height: 25px;
        }

        .numbers-input td {
            height: 30px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }

        .instru td,
        .instru-input td {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }

        .instru td {
            height: 25px;
        }

        .instru-input td {
            height: 25px;
            min-height: 100px;
            word-wrap: break-word;
        }

        .instru-input td:nth-child(1) {
            width: 199px;
        }

        .instru-input td:nth-child(2) {
            width: 188.5px;
        }

        .barcode td {
            text-align: center;
            height: 40px;
            padding: 5px;
        }

        .barcode td h1 {
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }

        .comments td:nth-child(1) {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            height: 25px;
        }

        .comments td div {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .comments td div img {
            margin: 1px;
        }

        .comments-input td {
            height: 60px;
            width: 287px;
            word-wrap: break-word;
            padding: 5px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
        }

        .connect td {
            font-size: 13px;
            height: 25px;
            text-align: center;
            font-weight: 700;
        }

        .connect-2 td {
            font-size: 13px;
            height: 25px;
            text-align: center;
            font-weight: 700;
        }

        .main-con .police table {
            border-spacing: 0px;
            border: 1px solid;
        }
    </style>
</head>

<body>
    <section class="main-con row">
        @foreach ($orders as $order)
        <div class="col-6 police">
            <table>
                <tr>
                    <td colspan="2" class="address">
                        <span>
                            <h1>العنوان :</h1>
                            <p>{{ $order->address }}</p>
                        </span>
                        <span>
                            <h1>المحافظه :</h1>
                            <p>القاهره</p>
                        </span>
                        <span>
                            <h1>المنطقه :</h1>
                            <p>دار السلام</p>
                        </span>
                    </td>
                    <td class="qr1">
                        <div>
                            <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(60)->generate($order->id_police)) }}" width="80px">
                        </div>
                        <h1>{{ $order->id_police }}</h1>
                    </td>
                </tr>
                <tr class="police-num">
                    <td colspan="2">
                        <h1>{{ $order->id_police }}</h1>
                    </td>
                    <td class="logo">
                        <div>
                            <img src="{{ asset('police/images/logo.png') }}" height="17px">
                        </div>
                    </td>
                </tr>
                <!-- main table -->
                <tr class="cust-name">
                    <td>اسم العميل</td>
                    <td>السعر</td>
                    <td>الراسل</td>
                </tr>
                <tr class="cust-name-input">
                    <td>{{ $order->name_client }}</td>
                    <!-- customer name input -->
                    <td>{{ $order->cost }}</td>
                    <!-- price input -->
                    <td>{{ $order->sender }}</td>
                    <!-- sender input -->
                </tr>
                <tr class="numbers">
                    <td>رقم الهاتف</td>
                    <td colspan="2">رقم الواتس</td>
                </tr>
                <tr class="numbers-input">
                    <td>{{ $order->phone}}</td>
                    <!--  first number input -->
                    <td colspan="2">{{ $order->phone2 }}</td>
                    <!--  Whatsapp number input -->
                </tr>
                <tr class="instru">
                    <td>التعليمات</td>
                    <td colspan="2">اسم المنتج</td>
                </tr>
                <tr class="instru-input">
                    <td>{{ $order->special_intructions2 }}</td>
                    <!--  instrucions input -->
                    <td colspan="2">{{ $order->name_product }}</td>
                    <!--  product name input -->
                </tr>
                <tr class="barcode">
                    <td colspan="3">
                        @php
                            $barcode = $generator->getBarcode($order->id_police, $generator::TYPE_CODE_128);
                        @endphp
                        <img src="data:image/png;base64,{{ base64_encode($barcode) }}" width="170px" height="25px">
                        <!-- barcode input -->
                        <h1>{{ $order->id_police }}</h1>
                    </td>
                </tr>
                <tr class="comments">
                    <td colspan="2">ملاحظات - comments</td>
                    <td rowspan="4">
                        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(60)->generate('https://www.facebook.com/profile.php?id=61551884698382')) }}" width="80px" style="display: block;">
                        <div>
                            <img src="{{ asset('police/images/facebook.png') }}" width="20px">
                            <img src="{{ asset('police/images/global.png') }}" width="16px">
                            <img src="{{ asset('police/images/whatsapp.png') }}" width="25px">
                        </div>
                        <div>
                            <img src="{{ asset('police/images/logo.png') }}" height="17px">
                        </div>
                    </td>
                </tr>
                <tr class="comments-input">
                    <td colspan="2">{{ $order->notes }}</td>
                    <!-- comments input -->
                </tr>
                <tr class="connect">
                    <td colspan="2">ارقام التواصل - Connect with us</td>
                </tr>
                <tr class="connect-2">
                    <td colspan="2">01111828807 - 01507677656 - 01551407492</td>
                </tr>
            </table>
        </div>
        @endforeach
    </section>
</body>

</html>
