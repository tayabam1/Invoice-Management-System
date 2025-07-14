import AppLayout from '@/layouts/app-layout';
import { Head, usePage, router, Link } from '@inertiajs/react';
import { Download, Eye, Edit, Trash2 } from 'lucide-react';
import React from 'react';

interface Invoice {
    id: number;
    client: { id: number; name: string };
    currency?: { id: number; currency_code: string; currency_name: string; currency_symbol: string };
    status: string;
    due_date: string;
    total: number;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export default function Invoices() {
    const { invoices, flash } = usePage().props as any;
    
    // Format price with currency
    const formatPrice = (invoice: Invoice) => {
        if (invoice.currency) {
            return `${invoice.currency.currency_symbol}${(typeof invoice.total === 'number' ? invoice.total : Number(invoice.total)).toFixed(2)}`;
        }
        return `$${(typeof invoice.total === 'number' ? invoice.total : Number(invoice.total)).toFixed(2)}`;
    };
    
    return (
        <AppLayout breadcrumbs={[{ title: 'Invoices', href: '/invoices' }]}> 
            <Head title="Invoices" />
            <div className="w-full mx-auto p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl font-bold">Invoices</h1>
                    <Link href="/invoices/create" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Add Invoice</Link>
                </div>
                {flash && flash.success && <div className="mb-4 p-2 bg-green-100 text-green-800 rounded dark:bg-green-900 dark:text-green-200">{flash.success}</div>}
                <table className="w-full bg-white dark:bg-neutral-900 rounded shadow">
                    <thead>                        <tr className="bg-gray-100 dark:bg-neutral-800">
                            <th className="p-2 text-left">Client</th>
                            <th className="p-2 text-left">Currency</th>
                            <th className="p-2 text-left">Status</th>
                            <th className="p-2 text-left">Due Date</th>
                            <th className="p-2 text-left">Total</th>
                            <th className="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>                        {invoices.data.map((invoice: Invoice) => (
                            <tr key={invoice.id} className="border-t border-gray-200 dark:border-neutral-800">
                                <td className="p-2">{invoice.client?.name}</td>
                                <td className="p-2">
                                    {invoice.currency ? (
                                        <span className="text-sm">
                                            <span className="font-medium">{invoice.currency.currency_code}</span>
                                            <br />
                                            <span className="text-gray-500 dark:text-gray-400 text-xs">{invoice.currency.currency_symbol}</span>
                                        </span>
                                    ) : (
                                        <span className="text-gray-400 text-sm">No currency</span>
                                    )}
                                </td>
                                <td className="p-2 capitalize">{invoice.status}</td>
                                <td className="p-2">{invoice.due_date}</td>
                                <td className="p-2 font-medium">{formatPrice(invoice)}</td>                                <td className="p-2">
                                    <div className="flex gap-1 items-center flex-wrap">
                                        <Link 
                                            href={`/invoices/${invoice.id}`} 
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-md transition-colors duration-200"
                                            title="View Invoice"
                                        >
                                            <Eye size={12} />
                                            View
                                        </Link>
                                        <Link 
                                            href={`/invoices/${invoice.id}/edit`} 
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 hover:bg-yellow-200 dark:text-yellow-400 dark:bg-yellow-900 dark:hover:bg-yellow-800 rounded-md transition-colors duration-200"
                                            title="Edit Invoice"
                                        >
                                            <Edit size={12} />
                                            Edit
                                        </Link>
                                        <a 
                                            href={`/invoices/${invoice.id}/pdf?view=1`} 
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800 rounded-md transition-colors duration-200" 
                                            title="View PDF"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <Eye size={12} />
                                            PDF
                                        </a>
                                        <a 
                                            href={`/invoices/${invoice.id}/pdf`} 
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-400 dark:bg-green-900 dark:hover:bg-green-800 rounded-md transition-colors duration-200" 
                                            title="Download PDF"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <Download size={12} />
                                            Download
                                        </a>
                                        <button 
                                            onClick={() => { 
                                                if (confirm('Are you sure you want to delete this invoice?')) router.delete(`/invoices/${invoice.id}`); 
                                            }} 
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 dark:text-red-400 dark:bg-red-900 dark:hover:bg-red-800 rounded-md transition-colors duration-200"
                                            title="Delete Invoice"
                                        >
                                            <Trash2 size={12} />
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                {/* Pagination */}
                <div className="mt-4 flex justify-center gap-2">
                    {invoices.links && invoices.links.map((link: PaginationLink, i: number) => (
                        <button key={i} disabled={!link.url} onClick={() => link.url && router.visit(link.url)} className={`px-2 py-1 rounded ${link.active ? 'bg-blue-600 text-white dark:bg-blue-800' : 'bg-gray-200 dark:bg-neutral-800 dark:text-white'}`}>{link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</button>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
