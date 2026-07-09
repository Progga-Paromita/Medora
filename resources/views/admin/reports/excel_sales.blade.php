<table>
    <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Customer Name</th>
            <th>Customer Contact</th>
            <th>Invoice Date</th>
            <th>Gross Subtotal</th>
            <th>Discount Deducted</th>
            <th>VAT / Tax (%)</th>
            <th>Net Grand Total</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($getRecord as $value)
            <tr>
                <td>{{ $value->invoice_number }}</td>
                <td>{{ $value->customer_name }}</td>
                <td>{{ $value->customer_phone }}</td>
                <td>{{ $value->invoice_date }}</td>
                <td>{{ number_format($value->total_amount, 2) }}</td>
                <td>{{ number_format($value->total_discount, 2) }}</td>
                <td>{{ number_format($value->tax, 1) }}</td>
                <td>{{ number_format($value->net_total, 2) }}</td>
                <td>{{ $value->created_at }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="7" style="text-align: right; font-weight: bold;">Aggregate Total Revenue:</td>
            <td style="font-weight: bold;">{{ number_format($netRevenue, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
