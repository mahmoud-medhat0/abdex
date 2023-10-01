    @if ($orders !=null)
    @foreach ($orders as $order)
    <tr>
        <?php foreach ($police_duplicate as $police_duplicate1 => $value) {
            if ($order->id_police == $value) {
             $duplicate[$order->id] = 0;
                    }
                    }
                    ?>
        <td><input type="checkbox" name="{{ 'checkbox-' . $order->id }}"
                value="{{ $order->id }}"></td>
        <td>
            <a class="btn btn-info btn-sm"
                href="{{ route('orderedit', $order->id) }}">{{ $order->id }}</a>
            <a href="{{ route('print', $order->id) }}"
                class="btn btn-primary hidden-print"><svg
                    xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                    fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                    <path
                        d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                </svg></a>
        </td>
        <td>
            @foreach ($companies as $company)
            @if ($order->id_company == $company->id)
            {{ $company->name }}
            @endif
            @endforeach
        </td>
        <td @isset($duplicate[$order->id])
            {{ 'style=background-color:red;' }}
            @endisset>
            {{ $order->id_police }}
        </td>
        <td>{{ $order->name_client }}</td>
        <td>{{ $order->phone }}</td>
        <td>{{ $order->phone2 }}</td>
        <td>
            @if (isset($order->center_id))
            @foreach ($centers as $center)
            @if ($center->id == $order->center_id)
            {{ $center->center_name }}
            @endif
            @endforeach
            @else
            {{ 'فراغات' }}
            @endif
        </td>
        <td>
            @if (isset($order->agent_id)|$order->agent_id!=null)
            @foreach ($agents as $agent)
            @if ($agent->id == $order->agent_id)
            {{ $agent->name }}
            @endif
            @endforeach
            @else
            {{ 'فراغات' }}
            @endif
        </td>
        <td>
            @if ($order->delegate_id == null)
            {{ 'فراغات' }}
            @else
            @foreach ($delegates as $delegate)
            @if ($delegate->id == $order->delegate_id)
            {{ $delegate->name }}
            @endif
            @endforeach
            @endif
        </td>
        <td>
            <textarea disabled type="text" name=""
                id="">{{ $order->address }}</textarea>
        </td>
        <td>{{ $order->cost }}</td>
        <td>
            @foreach ($companies as $company)
            @if ($order->id_company == $company->id)
            {{ $company->commission }}
            @endif
            @endforeach
        </td>
        <td>
            @if ($order->delegate_id == null)
            {{ 'لم يتم تعيينه بعد' }}
            @else
            @foreach ($delegates as $delegate)
            @if ($delegate->id == $order->delegate_id)
            {{ $delegate->commision }}
            @endif
            @endforeach
            @endif
        </td>
        <td>{{ $order->date }}</td>
        <td>{{ $order->created_at }}</td>
        <td>{{ $order->notes }}</td>
        <td>{{ $order->special_intructions }}</td>
        <td>{{ $order->special_intructions2 }}</td>
        <td>{{ $order->name_product }}</td>
        <td>{{ $order->sender }}</td>
        <td>{{ $order->weghit }}</td>
        <td>{{ $order->open }}</td>
        <td>
            @if ($order->status_id == null)
            {{ 'فراغات' }}
            @else
            @foreach ($states as $state)
            @if ($state->id == $order->status_id)
            {{ $state->state }}
            @endif
            @endforeach
            @endif
        </td>
        <td>
            @if ($order->cause_id == null)
            {{ 'فراغات' }}
            @else
            @foreach ($causes as $cause)
            @if ($cause->id == $order->cause_id)
            {{ $cause->cause }}
            @endif
            @endforeach
            @endif
        </td>
        <td>
            @if ($order->delay_id == null)
            {{ 'فراغات' }}
            @else
            @foreach ($delayes as $cause)
            @if ($cause->id == $order->delay_id)
            {{ $cause->cause }}
            @endif
            @endforeach
            @endif
        </td>
        <td>
            @if ($order->delay_date ==null)
            {{ 'فراغات' }}
            @else
            {{ $order->delay_date }}
            @endif
        </td>
        <td>
            @switch($order->delegate_supply)
            @case('0')
            {{ 'لم يتم التوريد' }}
            @break

            @case('1')
            {{ 'تم توريده' }}
            @break
            @endswitch
        </td>
        <td>
            @if ($order->delegate_supply_date == null)
            {{ 'لم يتم التعيين بعد' }}
            @else
            {{ $order->delegate_supply_date }}
            @endif
        </td>
        <td>
            @switch($order->company_supply)
            @case('0')
            {{ 'لم يتم التوريد' }}
            @break

            @case('1')
            {{ 'تم توريدة' }}
            @break
            @endswitch
        </td>
        <td>
            @if ($order->company_supply_date == null)
            {{ 'لم يتم التعيين بعد' }}
            @else
            {{ $order->company_supply_date }}
            @endif
        </td>
        <td>
            @isset($order->gps_delivered)
            <a href="{{ 'https://www.google.com/maps/search/'.$order->gps_delivered }}"
                target="_blank">{{ __('view_map') }}</a>
            @endisset
        </td>
        <td>{{ $order->identy_number }}</td>
        <td>
            @switch($order->order_locate)
            @case('0')
            {{ 'لم يتم الاستلام بعد ' }}
            @break

            @case('1')
            {{ 'بالمقر' }}
            @break

            @case('2')
            {{ 'مع المندوب' }}
            @break

            @case('3')
            {{ 'تم الرد للراسل' }}
            @break

            @case('4')
            {{ 'مطلوب من المندوب' }}
            @break
            @endswitch
        </td>
        <td class="project-actions text-right">
            <a class="btn btn-danger btn-sm"
                href="{{ route('order_delete', $order->id) }}">
                <i class="fas fa-trash">
                </i>
                {{ __("delete") }}
            </a>
        </td>
    </tr>
    @endforeach
    @else
        {{ 'no data avilable' }}
    @endif
