<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Test Invoice #{{ $invoiceData['invoice_number'] }}</h1>
    <p>Client: {{ $invoiceData['bill_to'] }}</p>
    <p>Total: {{ $invoiceData['balance_due'] }}</p>
    
    <table border="1">
        <tr>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        @foreach ($invoiceData['items'] as $item)
        <tr>
            <td>{{ $item['item_title'] }}</td>
            <td>{{ $item['quantity'] }}</td>
            <td>${{ number_format($item['unit_price'], 2) }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
