<table>
    <thead>
        <tr>
            <th>Voucher Number</th>
            <th>Supplier Name</th>
            <th>Purchase Date</th>
            <th>Payment Status</th>
            <th>Net Total Amount</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($getRecord as $value)
            <tr>
                <td>{{ $value->voucher_number }}</td>
                <td>{{ $value->supplier_name }}</td>
                <td>{{ $value->purchase_date }}</td>
                <td>
                    @if($value->payment_status == 1)
                        Pending
                    @elseif($value->payment_status == 2)
                        Accepted
                    @else
                        Rejected
                    @endif
                </td>
                <td>{{ number_format($value->net_total, 2) }}</td>
                <td>{{ $value->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
