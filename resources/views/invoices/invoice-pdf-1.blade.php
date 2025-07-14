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
            margin: 10mm;
        }
        /* Ensure the content fits within A4 page size (210mm x 297mm) */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            /*background-color: #f4f4f9;*/
            color: #333;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        h1, h2, h3 {
            font-family: 'DejaVu Sans', sans-serif;
        }
        .w-100 {
            width: 100%;
        }
        .w-50 {
            width: 50%;
        }

        .fs-8 { font-size: 8px; }
        .fs-9 { font-size: 9px; }
        .fs-10 { font-size: 10px; }
        .fs-11 { font-size: 11px; }
        .fs-12 { font-size: 12px; }
        .fs-13 { font-size: 13px; }
        .fs-14 { font-size: 14px; }
        .fs-15 { font-size: 15px; }
        .fs-16 { font-size: 16px; }
        .fs-17 { font-size: 17px; }
        .fs-18 { font-size: 18px; }
        .fs-19 { font-size: 19px; }
        .fs-20 { font-size: 20px; }
        .fs-21 { font-size: 21px; }
        .fs-22 { font-size: 22px; }
        .fs-23 { font-size: 23px; }
        .fs-24 { font-size: 24px; }
        .fs-25 { font-size: 25px; }
        .fs-26 { font-size: 26px; }
        .fs-27 { font-size: 27px; }
        .fs-28 { font-size: 28px; }
        .fs-29 { font-size: 29px; }
        .fs-30 { font-size: 30px; }
        .fs-31 { font-size: 31px; }
        .fs-32 { font-size: 32px; }
        .fs-33 { font-size: 33px; }
        .fs-34 { font-size: 34px; }
        .fs-35 { font-size: 35px; }
        .fs-36 { font-size: 36px; }
        .fs-37 { font-size: 37px; }
        .fs-38 { font-size: 38px; }
        .fs-39 { font-size: 39px; }
        .fs-40 { font-size: 40px; }

        /* Margin top and bottom values */
        .m-0 { margin: 0; }
        .mt-5 { margin-top: 5px; }
        .mt-10 { margin-top: 10px; }
        .mt-15 { margin-top: 15px; }
        .mt-20 { margin-top: 20px; }
        .mt-25 { margin-top: 25px; }
        .mt-30 { margin-top: 30px; }

        .mb-5 { margin-bottom: 5px; }
        .mb-10 { margin-bottom: 10px; }
        .mb-15 { margin-bottom: 15px; }
        .mb-20 { margin-bottom: 20px; }
        .mb-25 { margin-bottom: 25px; }
        .mb-30 { margin-bottom: 30px; }

        /* Padding top and bottom values */
        .p-0 { padding: 0; }
        .pt-5 { padding-top: 5px; }
        .pt-7 { padding-top: 7px; }
        .pt-10 { padding-top: 10px; }
        .pt-15 { padding-top: 15px; }
        .pt-20 { padding-top: 20px; }
        .pt-25 { padding-top: 25px; }
        .pt-30 { padding-top: 30px; }

        .pb-5 { padding-bottom: 5px; }
        .pb-7 { padding-bottom: 7px; }
        .pb-10 { padding-bottom: 10px; }
        .pb-15 { padding-bottom: 15px; }
        .pb-20 { padding-bottom: 20px; }
        .pb-25 { padding-bottom: 25px; }
        .pb-30 { padding-bottom: 30px; }

        /* Padding left and right values */
        .pl-5 { padding-left: 5px; }
        .pl-10 { padding-left: 10px; }
        .pl-15 { padding-left: 15px; }
        .pl-20 { padding-left: 20px; }
        .pl-25 { padding-left: 25px; }
        .pl-30 { padding-left: 30px; }

        .pr-5 { padding-right: 5px; }
        .pr-10 { padding-right: 10px; }
        .pr-15 { padding-right: 15px; }
        .pr-20 { padding-right: 20px; }
        .pr-25 { padding-right: 25px; }
        .pr-30 { padding-right: 30px; }

        /* Padding all sides */
        .p-5 { padding: 5px; }
        .p-7 { padding: 7px; }
        .p-10 { padding: 10px; }
        .p-15 { padding: 15px; }
        .p-20 { padding: 20px; }
        .p-25 { padding: 25px; }
        .p-30 { padding: 30px; }

        .bold {
            font-weight: bold;
        }

        .bolder {
            font-weight: bolder;
        }
        .border-none {
            border: none;
        }

        table, table tr, table tr td {
            border: none;
        }

        .row {
            display: block;
            clear: both;
        }

        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            box-sizing: border-box;
            margin: 0 auto;
        }

        /* Header using inline-block layout */
        .header {
            width: 100%;
            margin-bottom: 20px;
            display: block;
            overflow: hidden;
        }
        .header-invoice {
            display: block;
        }

        /* Left side: Logo */
        .header .logo {
            max-width: 150px;
            height: 110px;
            float: left;
        }

        .header .logo img {
            width: 100%;
            height: auto;
        }

        /* Right side: Invoice details */
        .header .invoice-details {
            float: right;
            text-align: right;
            max-width: 450px;
        }

        .header .invoice-details h2 {
            margin: 0;
            margin-bottom: 15px;
        }

        .header .invoice-details p {
            margin: 5px 0;
        }

        /* Clearfix to ensure proper alignment */
        .header::after {
            content: "";
            display: table;
            clear: both;
        }
        .bill-to {
            float: left;
        }
        .due-date {
            float: right;
        }

        /* Ensure content stays within page limits */
        .content {
            margin-top: 20px;
        }
        .background-dark {
            background: #2d2a2a;
        }
        .color-white {
            color: #ffffff;
        }
        .color-gray {
            color: #6c6a6a;
        }
        .border-radius-10 {
            border-radius: 10px;
        }
        .border-collapse {
            border-collapse: collapse;
        }
        .items-table thead td:first-child {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }
        .items-table thead td:last-child {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .totals {
            text-align: right;
            margin-top: 20px;
            margin-left: auto; /* Align the content to the right */
            width: 50%; /* Adjust width if needed */
            float: right; /* Ensure it stays on the right side */
        }
        .totals table tbody tr td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .totals .label {
            display: inline-block;
            width: 200px;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Invoice Header -->
    <div class="header">
        <div class="row">
            <!-- Left side: Logo -->
            <div class="logo">
                @if(!empty($invoiceData['logo']))
                    <img src="{{ $invoiceData['logo']['absolute_path'] }}" alt="Logo">
                @endif
            </div>
            <!-- Right side: Invoice number and Date -->
            <div class="invoice-details">
                <h2 class="fs-25">INVOICE #{{ $invoiceData['invoice_number'] }}</h2>
                <div class="fs-14">
                    <p>From: {{ $invoiceData['from_name'] }}</p>
                </div>
            </div>
        </div>
        <div class="row pt-20">
            <div class="bill-to w-50">
                <p class="fs-14"><span class="color-gray">Bill To:</span> <br/><strong>{{ $invoiceData['bill_to'] }}</strong></p>
                @if(!empty($invoiceData['ship_to']))
                    <p class="fs-14"><strong>Ship To:</strong> {{ $invoiceData['ship_to'] }}</p>
                @endif
            </div>
            <div class="due-date w-50">
                <table class="w-100 border-none">
                    <tr>
                        <td class="text-right fs-14 w-50 color-gray">{{ $invoiceData['date_label'] }}:</td>
                        <td class="text-right fs-14 w-50 pr-10">{{ formatDate($invoiceData['date'], 'M d, Y') }}</td>
                    </tr>
                </table>
                <table class="w-100 border-none mt-5 pt-5 pb-5 m-0" style="background: #f2f2f2; border: none; border-radius: 5px">
                    <tr>
                        <td class="text-right fs-14 w-50 bold">{{ $invoiceData['balance_due_label'] }}:</td>
                        <td class="text-right fs-14 w-50 bold pr-10">{{ formatCurrency($invoiceData['balance_due'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Additional content goes here -->
    <div class="content">
        <!-- Invoice Items Table -->
        <table class="w-100 border-collapse items-table">
            <thead>
            <tr class="background-dark color-white border-radius-10">
                <td class="text-left pl-10 pt-7 pb-7 pr-5 fs-14">{{ $invoiceData['headings']['description'] }}</td>
                <td class="text-center p-7 fs-14">{{ $invoiceData['headings']['quantity'] }}</td>
                <td class="text-center p-7 fs-14">{{ $invoiceData['headings']['unit_price'] }}</td>
                <td class="text-center p-7 fs-14">{{ $invoiceData['headings']['total_amount'] }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($invoiceData['items'] as $item)
                <tr>
                    <td class="fs-14 pl-10 pt-5 pb-5">{{ $item['item_title'] }}</td>
                    <td class="text-center fs-14 pt-5 pb-5">{{ $item['quantity'] }}</td>
                    <td class="text-center fs-14 pt-5 pb-5">{{ formatCurrency($item['unit_price'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                    <td class="text-center fs-14 pt-5 pb-5">{{ formatCurrency($item['quantity'] * $item['unit_price'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Subtotal, Tax, and Balance -->
        <div class="row">
            <div class="totals">
                <table class="w-100 fs-14">
                    <tr>
                        <td class="label color-gray">{{ $invoiceData['subtotal_label'] }}:</td>
                        <td>{{ formatCurrency($invoiceData['subtotal'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                    </tr>
                    @if ($invoiceData['tax'] > 0)
                        <tr>
                            <td class="label color-gray">
                                {{ $invoiceData['tax_label'] }}
                                @if (!empty($invoiceData['tax_type']) && $invoiceData['tax_type'] === 'fixed')
                                    (fixed)
                                @elseif (!empty($invoiceData['tax_type']) && $invoiceData['tax_type'] === 'percentage')
                                    ({{ $invoiceData['tax'] }}%)
                                @endif
                            </td>
                            <td>
                                @if (!empty($invoiceData['tax_type']) && $invoiceData['tax_type'] === 'fixed')
                                    {{ formatCurrency($invoiceData['tax'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}
                                @elseif (!empty($invoiceData['tax_type']) && $invoiceData['tax_type'] === 'percentage')
                                    {{ formatCurrency(($invoiceData['subtotal'] * $invoiceData['tax'] / 100), true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}
                                @endif
                            </td>
                        </tr>
                    @endif

                    @if ($invoiceData['discount'] > 0)
                        <tr>
                            <td class="label color-gray">{{ $invoiceData['discount_label'] }}:</td>
                            <td>{{ formatCurrency($invoiceData['discount'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                        </tr>
                    @endif

                    @if ($invoiceData['shipping'] > 0)
                        <tr>
                            <td class="label color-gray">{{ $invoiceData['shipping_label'] }}:</td>
                            <td>{{ formatCurrency($invoiceData['shipping'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="label color-gray">{{ $invoiceData['balance_due_label'] }}:</td>
                        <td>{{ formatCurrency($invoiceData['balance_due'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                    </tr>
                    <tr>
                        <td class="label color-gray">{{ $invoiceData['amount_paid_label'] }}:</td>
                        <td>{{ formatCurrency($invoiceData['amount_paid'], true, $invoiceData['currency'], $invoiceData['user']['company']['currency_position'] ?? 'left', false) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <!-- Notes Section -->
            @if (!empty($invoiceData['notes']))
                <div class="notes fs-14">
                    <p>{{ $invoiceData['notes_label'] }}:</p>
                    <p>{{ $invoiceData['notes'] }}</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="row">
            <div class="footer fs-14">
                <p>Thank you for your business!</p>
                <p>If you have any questions, please feel free to contact us.</p>
            </div>
        </div>
    </div>

</div>

</body>
</html>
