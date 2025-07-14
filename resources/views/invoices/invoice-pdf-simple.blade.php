<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceData['invoice_number'] }}</title>
    <style>
        /* Page size setup for PDF (A4 size) */
        @page {
            size: A4;
            margin: 20mm;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        
        h1, h2, h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .invoice-info > div {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .bill-to {
            padding-right: 20px;
        }
        
        .invoice-details {
            text-align: right;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .totals {
            float: right;
            width: 300px;
        }
        
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals td {
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .totals .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(!empty($invoiceData['logo']) && file_exists($invoiceData['logo']['absolute_path']))
            <img src="{{ $invoiceData['logo']['absolute_path'] }}" alt="Logo" class="logo">
        @endif
        
        <div class="invoice-title">INVOICE #{{ $invoiceData['invoice_number'] }}</div>
        <div><strong>From:</strong> {{ $invoiceData['from_name'] }}</div>
    </div>
    
    <div class="invoice-info">
        <div class="bill-to">
            <h3>Bill To:</h3>
            <strong>{{ $invoiceData['bill_to'] }}</strong>
            @if(!empty($invoiceData['ship_to']))
                <br><br>
                <strong>Ship To:</strong><br>
                {{ $invoiceData['ship_to'] }}
            @endif
        </div>
        
        <div class="invoice-details">
            <table style="width: 100%;">
                <tr>
                    <td><strong>{{ $invoiceData['date_label'] }}:</strong></td>
                    <td style="text-align: right;">{{ formatDate($invoiceData['date'], 'M d, Y') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ $invoiceData['balance_due_label'] }}:</strong></td>
                    <td style="text-align: right;">{{ formatCurrency($invoiceData['balance_due'], true, $invoiceData['currency'], 'left', false) }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>{{ $invoiceData['headings']['description'] }}</th>
                <th class="text-center">{{ $invoiceData['headings']['quantity'] }}</th>
                <th class="text-center">{{ $invoiceData['headings']['unit_price'] }}</th>
                <th class="text-center">{{ $invoiceData['headings']['total_amount'] }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceData['items'] as $item)
                <tr>
                    <td>{{ $item['item_title'] }}</td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-center">{{ formatCurrency($item['unit_price'], true, $invoiceData['currency'], 'left', false) }}</td>
                    <td class="text-center">{{ formatCurrency($item['quantity'] * $item['unit_price'], true, $invoiceData['currency'], 'left', false) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="clearfix">
        <div class="totals">
            <table>
                <tr>
                    <td>{{ $invoiceData['subtotal_label'] }}:</td>
                    <td class="text-right">{{ formatCurrency($invoiceData['subtotal'], true, $invoiceData['currency'], 'left', false) }}</td>
                </tr>
                @if ($invoiceData['tax'] > 0)
                    <tr>
                        <td>{{ $invoiceData['tax_label'] }}:</td>
                        <td class="text-right">{{ formatCurrency($invoiceData['tax'], true, $invoiceData['currency'], 'left', false) }}</td>
                    </tr>
                @endif
                @if ($invoiceData['discount'] > 0)
                    <tr>
                        <td>{{ $invoiceData['discount_label'] }}:</td>
                        <td class="text-right">{{ formatCurrency($invoiceData['discount'], true, $invoiceData['currency'], 'left', false) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td><strong>{{ $invoiceData['balance_due_label'] }}:</strong></td>
                    <td class="text-right"><strong>{{ formatCurrency($invoiceData['balance_due'], true, $invoiceData['currency'], 'left', false) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
    
    @if (!empty($invoiceData['notes']))
        <div style="margin-top: 50px; clear: both;">
            <h3>{{ $invoiceData['notes_label'] }}:</h3>
            <p>{{ $invoiceData['notes'] }}</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>If you have any questions, please feel free to contact us.</p>
    </div>
</body>
</html>
