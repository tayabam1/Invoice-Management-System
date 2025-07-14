<?php

// Simple test script to verify PDF generation functionality
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Invoice;
use App\Http\Controllers\InvoiceController;
use Barryvdh\DomPDF\Facade\Pdf;

echo "Testing PDF generation functionality...\n";

// Get the first invoice
$invoice = Invoice::with(['client', 'currency', 'lineItems'])->first();

if (!$invoice) {
    echo "No invoice found in database.\n";
    exit(1);
}

echo "Found invoice ID: {$invoice->id}\n";
echo "Client: {$invoice->client->name}\n";
echo "Currency: " . ($invoice->currency ? $invoice->currency->currency_code : 'No currency') . "\n";
echo "Line items: {$invoice->lineItems->count()}\n";

// Test the data preparation method
$controller = new InvoiceController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('prepareInvoiceDataForPDF');
$method->setAccessible(true);

try {
    $invoiceData = $method->invoke($controller, $invoice);
    echo "Invoice data prepared successfully!\n";
    echo "Invoice number: {$invoiceData['invoice_number']}\n";
    echo "Bill to: {$invoiceData['bill_to']}\n";
    echo "Currency: {$invoiceData['currency']}\n";
    echo "Items count: " . count($invoiceData['items']) . "\n";
    
    // Test PDF generation
    echo "Generating PDF...\n";
    $pdf = Pdf::loadView('invoices.invoice-pdf-1', compact('invoiceData'));
    $pdf->setPaper('A4', 'portrait');
    
    // Save to file for testing
    $outputPath = __DIR__ . '/storage/app/test-invoice.pdf';
    $pdf->save($outputPath);
    
    if (file_exists($outputPath)) {
        $fileSize = filesize($outputPath);
        echo "PDF generated successfully! File size: {$fileSize} bytes\n";
        echo "PDF saved to: {$outputPath}\n";
    } else {
        echo "PDF generation failed - file not created.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "Test completed.\n";
