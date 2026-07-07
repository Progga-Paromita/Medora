<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice - {{ $invoice->invoice_number }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            color: #000000;
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 5px;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-label {
            font-weight: bold;
        }
        .table th, .table td {
            border-color: #000000 !important;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-box {
                border: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print text-center mb-4">
    <button onclick="window.print()" class="btn btn-primary btn-lg"><i class="bi bi-printer"></i> Print Invoice</button>
    <button onclick="window.close()" class="btn btn-secondary btn-lg ms-2">Close Tab</button>
</div>

<div class="invoice-box">
    <!-- Pharmacy Header -->
    <div class="receipt-header">
        <h2 class="receipt-title">Medora Pharmacy</h2>
        <p class="mb-1">123 Health Care Avenue, Dhaka, Bangladesh</p>
        <p class="mb-0">Tel: +880 2-9876543 | Email: contact@medora.com</p>
        <hr class="my-3">
        <h4 class="fw-bold">SALES RECEIPT</h4>
    </div>

    <!-- Meta Details -->
    <div class="row mb-4">
        <div class="col-6">
            <p class="mb-1"><span class="meta-label">Invoice No:</span> {{ $invoice->invoice_number }}</p>
            <p class="mb-0"><span class="meta-label">Invoice Date:</span> {{ date('d-M-Y', strtotime($invoice->invoice_date)) }}</p>
        </div>
        <div class="col-6 text-end">
            <p class="mb-1"><span class="meta-label">Customer:</span> {{ optional($invoice->getCustomerName)->name ?? 'Walk-in' }}</p>
            <p class="mb-0"><span class="meta-label">Contact:</span> {{ optional($invoice->getCustomerName)->phone ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <table class="table table-bordered table-striped table-sm mb-4">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th style="width: 45%;">Medicine Name</th>
                <th style="width: 25%;">Generic Name</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-end" style="width: 15%;">MRP ($)</th>
                <th class="text-end" style="width: 15%;">Total ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ optional($item->getMedicine)->name }} ({{ optional($item->getMedicine)->packaging }})</td>
                    <td>{{ optional($item->getMedicine)->generic_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">${{ number_format($item->selling_price, 2) }}</td>
                    <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Summary -->
    <div class="row justify-content-end mb-5">
        <div class="col-md-5 col-7">
            <table class="table table-sm table-borderless">
                <tr>
                    <td class="meta-label">Gross Subtotal:</td>
                    <td class="text-end">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="meta-label text-danger">Total Discount:</td>
                    <td class="text-end text-danger">-${{ number_format($invoice->total_discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">VAT / Tax ({{ number_format($invoice->tax, 1) }}%):</td>
                    <td class="text-end">${{ number_format(($invoice->tax / 100) * ($invoice->total_amount - $invoice->total_discount), 2) }}</td>
                </tr>
                <tr class="border-top border-dark">
                    <td class="meta-label fs-5">Net Payable:</td>
                    <td class="text-end fw-bold fs-5">${{ number_format($invoice->net_total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Receipt Footer -->
    <hr class="my-4">
    <div class="text-center text-muted small">
        <p class="mb-1 fw-bold">Thank you for choosing Medora Pharmacy!</p>
        <p class="mb-0">Get well soon.</p>
    </div>
</div>

<script>
    // Automatically trigger print on load
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>
