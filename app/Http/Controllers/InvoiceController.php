<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Currencies;
use App\Models\InvoiceLineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['client', 'currency'])->latest()->paginate(10);
        return Inertia::render('invoices/index', [
            'invoices' => $invoices
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $currencies = Currencies::where('is_active', true)->orderBy('currency_code')->get();
        $defaultCurrency = Currencies::where('is_default', true)->first();
        return Inertia::render('invoices/create', [
            'clients' => $clients,
            'currencies' => $currencies,
            'defaultCurrency' => $defaultCurrency
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,bmp,svg|max:1024',
        ]);
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('invoice_logos', 'public');
        }
        $invoice = Invoice::create([
            'user_id' => auth()->id,
            'client_id' => $validated['client_id'],
            'currency_id' => $validated['currency_id'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'total' => collect($validated['items'])->sum(function($item) {
                return $item['quantity'] * $item['unit_price'];
            }),
            'logo' => $logoPath,
        ]);
        foreach ($validated['items'] as $item) {
            $invoice->lineItems()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $item['quantity'] * $item['unit_price'],
            ]);
        }
        return Redirect::route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['client', 'currency', 'lineItems'])->findOrFail($id);
        return Inertia::render('invoices/show', [
            'invoice' => $invoice->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['lineItems', 'currency'])->findOrFail($id);
        $clients = Client::all();
        $currencies = Currencies::where('is_active', true)->orderBy('currency_code')->get();
        return Inertia::render('invoices/edit', [
            'invoice' => $invoice->toArray(),
            'clients' => $clients,
            'currencies' => $currencies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,bmp,svg|max:1024',
        ]);
        $logoPath = $invoice->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('invoice_logos', 'public');
        }
        $invoice->update([
            'client_id' => $validated['client_id'],
            'currency_id' => $validated['currency_id'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'total' => collect($validated['items'])->sum(function($item) {
                return $item['quantity'] * $item['unit_price'];
            }),
            'logo' => $logoPath,
        ]);
        $invoice->lineItems()->delete();
        foreach ($validated['items'] as $item) {
            $invoice->lineItems()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $item['quantity'] * $item['unit_price'],
            ]);
        }
        return Redirect::route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->lineItems()->delete();
        $invoice->delete();
        return Redirect::route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Delete the logo of the specified resource.
     */
    public function deleteLogo($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->logo) {
            Storage::disk('public')->delete($invoice->logo);
            $invoice->logo = null;
            $invoice->save();
        }
        return redirect()->back()->with('success', 'Logo deleted successfully.');
    }

    /**
     * Generate and download/stream PDF for the specified invoice.
     */
    public function downloadPDF($id, Request $request)
    {
        try {
            $invoice = Invoice::with(['client', 'currency', 'lineItems'])->findOrFail($id);
            
            // Prepare invoice data for PDF template
            $invoiceData = $this->prepareInvoiceDataForPDF($invoice);
            
            // Generate PDF with better error handling
            $pdf = Pdf::loadView('invoices.invoice-pdf-1', compact('invoiceData'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Set options for better rendering
            $pdf->setOptions([
                'isRemoteEnabled' => true, // Enable for local file access
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false, // Disable PHP in templates for security
                'defaultFont' => 'DejaVu Sans',
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
                'chroot' => [public_path(), storage_path('app/public')], // Allow access to these directories
            ]);
            
            $filename = 'invoice-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT) . '.pdf';
            
            // Check if view parameter is set for streaming (viewing in browser)
            if ($request->has('view') && $request->get('view') == '1') {
                // Stream PDF to browser for viewing
                return $pdf->stream($filename);
            }
            
            // Download PDF
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'invoice_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare invoice data for PDF template.
     */
    private function prepareInvoiceDataForPDF($invoice)
    {
        $currency = $invoice->currency ?? Currencies::where('is_default', true)->first();
        
        return [
            'invoice_number' => str_pad($invoice->id, 6, '0', STR_PAD_LEFT),
            'from_name' => config('app.name', 'Invoice System'),
            'bill_to' => $invoice->client->name,
            'ship_to' => $invoice->client->address ?? '',
            'date_label' => 'Date',
            'date' => $invoice->created_at,
            'balance_due_label' => 'Balance Due',
            'balance_due' => $invoice->total,
            'subtotal_label' => 'Subtotal',
            'subtotal' => $invoice->total,
            'tax_label' => 'Tax',
            'tax' => 0, // No tax in current system
            'tax_type' => 'percentage',
            'discount_label' => 'Discount',
            'discount' => 0, // No discount in current system
            'shipping_label' => 'Shipping',
            'shipping' => 0, // No shipping in current system
            'amount_paid_label' => 'Amount Paid',
            'amount_paid' => $invoice->status === 'paid' ? $invoice->total : 0,
            'notes_label' => 'Notes',
            'notes' => 'Thank you for your business!',
            'currency' => $currency ? $currency->currency_code : 'USD',
            'logo' => $invoice->logo ? [
                'absolute_path' => storage_path('app/public/' . $invoice->logo)
            ] : null,
            'headings' => [
                'description' => 'Description',
                'quantity' => 'Qty',
                'unit_price' => 'Unit Price',
                'total_amount' => 'Total',
            ],
            'items' => $invoice->lineItems->map(function ($item) {
                return [
                    'item_title' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            })->toArray(),
            'user' => [
                'company' => [
                    'currency_position' => 'left'
                ]
            ]
        ];
    }
}
