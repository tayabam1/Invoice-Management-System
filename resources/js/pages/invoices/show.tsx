import AppLayout from '@/layouts/app-layout';
import { Head, usePage, Link } from '@inertiajs/react';
import { Download, Edit, ArrowLeft, Eye } from 'lucide-react';
import React from 'react';

export default function ShowInvoice() {
    const { invoice } = usePage().props as any;
    
    // Format price with currency
    const formatPrice = (amount: number) => {
        if (invoice.currency) {
            return `${invoice.currency.currency_symbol}${amount.toFixed(2)}`;
        }
        return `$${amount.toFixed(2)}`;
    };
    
    return (
        <AppLayout breadcrumbs={[{ title: 'Invoices', href: '/invoices' }, { title: `Invoice #${invoice.id}`, href: `/invoices/${invoice.id}` }]}>
            <Head title={`Invoice #${invoice.id}`} />
            <div className="w-full p-4">
                {/* Header with title and action buttons */}
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h1 className="text-2xl font-bold">Invoice #{invoice.id}</h1>
                    <div className="flex gap-2">
                        <Link 
                            href="/invoices" 
                            className="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-neutral-800 dark:text-white dark:border-neutral-600 dark:hover:bg-neutral-700 transition-colors duration-200"
                        >
                            <ArrowLeft size={16} />
                            Back to Invoices
                        </Link>
                        <Link 
                            href={`/invoices/${invoice.id}/edit`} 
                            className="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors duration-200"
                        >
                            <Edit size={16} />
                            Edit
                        </Link>
                        <a 
                            href={`/invoices/${invoice.id}/pdf?view=1`} 
                            className="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition-colors duration-200" 
                            title="View PDF in Browser"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <Eye size={16} />
                            View PDF
                        </a>
                        <a 
                            href={`/invoices/${invoice.id}/pdf`} 
                            className="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors duration-200" 
                            title="Download PDF"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <Download size={16} />
                            Download PDF
                        </a>
                    </div>
                </div>

                {/* Header section with invoice details on left and logo on right */}
                <div className="mb-6 flex flex-col md:flex-row justify-between items-start gap-4">
                    {/* Invoice details - left side */}
                    <div className="flex-1">
                        <div className="space-y-2">
                            <div><span className="font-semibold">Client:</span> {invoice.client.name}</div>
                            <div><span className="font-semibold">Status:</span> <span className={invoice.status === 'paid' ? 'text-green-600' : 'text-yellow-600'}>{invoice.status}</span></div>
                            <div><span className="font-semibold">Due Date:</span> {invoice.due_date}</div>
                            {invoice.currency && (
                                <div><span className="font-semibold">Currency:</span> {invoice.currency.currency_code} - {invoice.currency.currency_name}</div>
                            )}
                            <div><span className="font-semibold">Total:</span> {formatPrice(typeof invoice.total === 'number' ? invoice.total : Number(invoice.total))}</div>
                        </div>
                    </div>

                    {/* Logo - right side */}
                    {invoice.logo_url && (
                        <div className="flex flex-col items-end">
                            <img src={invoice.logo_url} alt="Invoice Logo" className="max-h-32 w-auto object-contain rounded shadow border dark:border-neutral-700" />
                            <span className="text-xs text-gray-500 dark:text-gray-300 mt-1">Invoice Logo</span>
                        </div>
                    )}
                </div>
                <div className="mb-4">
                    <h2 className="font-semibold mb-2">Line Items</h2>
                    <table className="w-full bg-white dark:bg-neutral-900 rounded shadow">
                        <thead>
                            <tr className="bg-gray-100 dark:bg-neutral-800">
                                <th className="p-2 text-left">Description</th>
                                <th className="p-2 text-left">Quantity</th>
                                <th className="p-2 text-left">Unit Price</th>
                                <th className="p-2 text-left">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {invoice.line_items.map((item: any, idx: number) => (
                                <tr key={idx} className="border-t border-gray-200 dark:border-neutral-800">
                                    <td className="p-2">{item.description}</td>
                                    <td className="p-2">{item.quantity}</td>
                                    <td className="p-2">{formatPrice(typeof item.unit_price === 'number' ? item.unit_price : Number(item.unit_price))}</td>
                                    <td className="p-2">{formatPrice(typeof item.line_total === 'number' ? item.line_total : Number(item.line_total))}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Total Summary Section - Right Aligned */}
                <div className="flex justify-end mb-4">
                    <div className="bg-gray-50 dark:bg-neutral-800 p-4 rounded shadow border dark:border-neutral-700 min-w-64">
                        <div className="space-y-2">
                            <div className="flex justify-between items-center text-lg font-semibold border-t-2 border-gray-300 dark:border-neutral-600 pt-2">
                                <span>Total Amount:</span>
                                <span className="text-green-600 dark:text-green-400">
                                    {formatPrice(typeof invoice.total === 'number' ? invoice.total : Number(invoice.total))}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
