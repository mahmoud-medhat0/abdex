<table>
    <thead>
        <tr>
            <th>حالة الدفع</th>
            <th>رقم الطرد</th>
            <th>اسم الشركة</th>
            <th>رقم البوليصة</th>
            <th>اسم العميل</th>
            <th>رقم الهاتف الاول</th>
            <th>رقم الهاتف الثاني</th>
            <th>العنوان</th>
            <th>السعر</th>
            <th>نسبة الشركة</th>
            <th>الاجمالي</th>
            <th>حالة</th>
        </tr>
    </thead>
    <?php
    $total=0;
    $total1=0;
    $total2=0;
    ?>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>@foreach ($r as $r1=>$v )
                @if ($order->id==$v)
                {{ "تم دفعه" }}
                @endif
                @endforeach
            </td>
            <td>{{ $order->id }}</td>
            <td>{{ $order->company_name }}</td>
            <td>{{ $order->id_police }}</td>
            <td>{{ $order->name_client }}</td>
            <td>{{ $order->phone }}</td>
            <td>{{ $order->phone2 }}</td>
            <td>@isset($order->address)
                {{ $order->address }}
                @endisset
            </td>
            <td>{{ $order->cost }}
                <?php $total= $total + $order->cost;?>
            </td>
            <td>
                @isset($commissions[$order->id])
                {{ $commissions[$order->id] }}
                <?php $total1+=$commissions[$order->id];?>
                @endisset
            </td>
            <td>
                @isset($commissions[$order->id])
                {{ $order->cost - $commissions[$order->id]}}
                <?php $total2+=$order->cost - $commissions[$order->id];?>
                @endisset
            </td>
            <td>{{ $order->state }}</td>
        </tr>
        @endforeach
        <tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>{{ $total1+$total2 }}</td>
            <td>{{ $total1 }}</td>
            <td>{{ $total2 }}</td>
            <td>-</td>
        </tr>
        <tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>Total Payed :</td>
            <td>{{ $payed }}</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
    </tbody>
</table>
