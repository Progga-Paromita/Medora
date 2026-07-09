<table>
    <thead>
        <tr>
            <th>Medicine Name</th>
            <th>Packaging</th>
            <th>Batch Number</th>
            <th>Expiry Date</th>
            <th>Current Quantity</th>
            <th>Purchase Rate</th>
            <th>Selling MRP</th>
            <th>Inventory Valuation</th>
        </tr>
    </thead>
    <tbody>
        @foreach($getRecord as $value)
            <tr>
                <td>{{ $value->medicine_name }}</td>
                <td>{{ $value->packaging }}</td>
                <td>{{ $value->batch_id }}</td>
                <td>{{ $value->expiry_date }}</td>
                <td>{{ $value->quantity }}</td>
                <td>{{ number_format($value->rate, 2) }}</td>
                <td>{{ number_format($value->mrp, 2) }}</td>
                <td>{{ number_format($value->quantity * $value->rate, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
