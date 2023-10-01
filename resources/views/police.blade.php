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
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .a4-size {
            width: 21cm;
            padding: 0 10px;
        }

        img.logo {
            margin-bottom: -10px;
        }

        .one {
            width: 100%;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            position: relative;
        }

        .flex {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .one {
            position: relative;
        }

        .one table {
            width: 100%;
            height: 61px;
            border: 1px solid black;
            border-spacing: 0;
            margin-top: 33px;
        }

        .one table tr td {
            font-size: 13px;

        }

        .one .bar3 {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .one .bar3 img {
            width: 85%;
            height: 61px;
        }

        .one .bar4 {
            height: 110px;
            top: -50px;
            right: 0;
            position: absolute;
        }

        .one .bar4 img {
            height: 60%;
        }

        .one .bar4 p {
            font-weight: 600;
            margin-top: 5px;
            font-size: 12px;
        }

        .one table thead tr td {
            background-color: rgb(175, 175, 175);
            padding: 7px;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            font-size: 20px;
        }

        .one table thead tr td:nth-child(1),
        .one table thead tr td:nth-child(3) {
            border-right: 1px solid black;
            border-left: 1px solid black;
        }

        .one table tbody tr td {
            padding: 7px;
            background-color: white;
            font-size: 20px;
        }

        .one table tbody tr td:nth-child(1),
        .one table tbody tr td:nth-child(3) {
            border-right: 1px solid black;
            border-left: 1px solid black;
        }

        .table-1 {
            width: 100%;
            /*position: relative;*/
            height: 10%;
        }

        .table-1>h1 {
            position: absolute;
            top: -50px;
        }

        .content-1 {
            background-color: white;
            width: 100%;
            border: 2px solid black;
            border-collapse: collapse;
        }

        .content-1 td {
            text-align: center;
            border-left: 2px solid black;
            height: 50px;
        }

        .content-1 .title-1 {
            background-color: rgb(175, 175, 175);
            text-align: center;
            border: 2px solid black;
            width: 100%;
        }

        .content-1 .title-1 td {
            font-size: 14px;
            width: 100%;
            border: 2px solid black;
            height: 20px;
        }

        .content-1 .title-1 td h2 {
            font-size: 14px;
            display: inline;
            margin: 5px;
            font-weight: 900;
        }

        .content-1 .input-1 td {
            font-size: 25px;
        }

        .content-1 .title-1 td:nth-child(1) {
            width: 30%;
        }

        .content-1 .title-1 td:nth-child(2) {
            width: 30%;
        }

        .content-1 .title-1 td:nth-child(3) {
            width: 30%;
        }

        .table-1 .title-1 td:nth-child(4) {
            width: 15%;
        }

        .content-1 tbody tr {
            height: 80px;
        }

        .info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 5px;
        }

        .inf-img {
            width: 140px;
        }

        .icons {
            display: flex;
            gap: 30px;
            justify-content: center;
        }

        .info p {
            font-size: 22px;
            text-align: center;
            line-height: 1.6;
            font-weight: bold;
        }

        .icons img {
            width: 30px;
        }

        .bar1 {
            padding: 0 0.5px;
        }

        .bar1 img {
            width: 90%;
            height: -webkit-fill-available;
            padding: 45px 0;
            z-index: -1;
        }

        .c td:nth-child(1) {
            width: 385px;
        }

        .c td:nth-child() {
            width: 220px;
        }

        .content-1 .title td {
            display: flex;
            justify-content: space-around;
            align-items: center;
            font-weight: 300;
        }

        .one table tbody tr td {
            font-weight: bold !important;
        }

        .one table thead tr td {
            font-weight: bold !important;
        }
    </style>
</head>

<body>
    <div class="a4-size">
        @foreach ($orders as $order)
            <div class="table-1" style="padding-top: 5px;">
                <img class="logo" style="margin-bottom:-42px; height:30px;" src="{{ asset('police/img/logo1.png') }}" />
                <img style="width:40px; margin-left:730px; margin-bottom: 0; margin-top: 4px; "
                    src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(60)->generate($order->id_police)) }}" />

                <div class="one">
                    <table style="width:50%; border-bottom: none; margin-top:20px;">
                        <thead>
                            <tr>
                                <td style="font-size:13px; font-weight:bold; text-align:center; padding:0 50px;">فارغ
                                </td>
                                <td style="font-size:13px; font-weight:bold; text-align:center; padding:0 50px;">الوزن
                                    Weight</td>
                                <td style="font-size:13px; font-weight:bold; text-align:center; padding:0 50px;">التاريخ
                                    Date</td>
                                <td style="font-size:13px; font-weight:bold; text-align:center; padding:0 250px;">رقم
                                    البوليصة </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-size:13px; font-weight:bold; text-align:center;"></td>
                                <td style="font-size:13px; font-weight:bold; text-align:center;">{{ $order->weghit }}
                                </td>
                                <td style="font-size:13px; font-weight:bold; text-align:center;">Date
                                    {{ $order->date }}</td>
                                <td style="font-size:13px; font-weight:bold; text-align:center;">{{ $order->id_police }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <table class="content-1" style="direction: rtl;">
                    <tr class="title-1">
                        <td style="font-size: 10px;" >
                            <h2>الراسل sender</h2>
                        </td>
                        <td style="font-size: 10px;">
                            <h2>المحتويات contents</h2>
                        </td>
                        <td style="font-size: 10px;">
                            <h2>إسم العميل client name</h2>
                        </td>
                        <td style="font-size: 10px;">
                            <h2>الإجمالى total</h2>
                        </td>

                    </tr>
                    <tr class="input-1">
                        <td style="font-size: 13px;">{{ $order->sender }}</td>
                        <td style="font-size: 13px;">{{ $order->name_product }}</td>
                        <td style="font-size: 13px;">{{ $order->name_client }}</td>
                        <td style="font-size: 13px;">{{ $order->cost }}</td>
                    </tr>
                    <tr class="title-1">
                        <td style="font-size: 12px;" colspan="2">
                            <h2 style="font-size: 14px;">ملاحظات notes</h2>
                        </td>
                        <td style="font-size: 12px;" style="width: 35%">
                            <h2 style="font-size: 14px;">العنوان address</h2>
                        </td>
                        <td style="font-size: 9px;">
                            <h2>معلوماتنا information</h2>
                        </td>
                    </tr>
                    <tr class="input-1" style="height: 10px;">

                        <!--<td></td>-->
                    </tr>
                    <tr class="input-1" style="height: 10px; border-bottom: 1px solid black;">
                        <td colspan="2">
                            <p style="text-align: left; padding-left: 10px; font-size: 10px;">{{ $order->notes }}</p>
                        </td>
                        <td style="text-align:center; font-size: 10px;">{{ $order->address }}</td> 
                        <td rowspan="10" style="position: relative; padding:10px 0; border-bottom: 2px solid black;">
                            <div class="info">
                                <div class="inf-img">
                                <img style="max-width: 65%; height: 30px;" src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(60)->generate('https://www.facebook.com/SPEA1R')) }}" />
                                </div>
                                <div class="icons">
                                    <img style="width:20px;"
                                        src="{{ asset('police/img/png-transparent-iphone-telephone-call-computer-icons-call-volume-call-electronics-text-hand.png') }}" />

                                    <img style="width:20px;"
                                        src="{{ asset('police/img/png-clipart-whatsapp-whatsapp.png') }}" />
                                    <img style="width:20px;" src="{{ asset('police/img/20673.png') }}" />
                                </div>
                                <h1 style="font-size:12px; margin:5px 0;">010070465157</h1>
                                <h1 style="font-size:12px;">01129033543</h1>
                            </div>
                        </td>
                    </tr>
                    <tr class="title-1">
                        <td style="font-size: 12px;" colspan="2">
                            <h2 style="font-size: 14px;">باركود بوليصة الشحن numbers</h2>
                        </td>
                        <td style="font-size: 12px;" style="width: 35%">
                            <h2 style="font-size: 14px;">أرقام الهواتف numbers</h2>
                        </td>
                    </tr>
                    <tr class="input-1">
                        <td colspan="2">
                            @php
                                $barcode = $generator->getBarcode($order->id_police, $generator::TYPE_CODE_128);
                            @endphp
                            <img style="max-width: 100%; height: 30px;"
                                src="data:image/png;base64,{{ base64_encode($barcode) }}" />
                        <td style="padding:0 20px; font-size: 14px;">{{ $order->phone . '-' . $order->phone2 }}</td>
                    </tr>
                    <!--<tr class="input-1">-->
                    <!--  <td colspan="2"><p style="font-size: 32px">spear-1305</p></td>-->
                    <!--  <td>k</td>-->
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
</body>

</html>
