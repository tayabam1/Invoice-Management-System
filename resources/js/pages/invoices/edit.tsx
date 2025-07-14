import AppLayout from '@/layouts/app-layout';
import { Head, usePage, router } from '@inertiajs/react';
import React, { useState } from 'react';
import FileUploader from '@/components/FileUploader';
import { TextareaAi } from '@/components/ui/textarea-ai';

interface Client { id: number; name: string; }
interface Currency { id: number; currency_code: string; currency_name: string; currency_symbol: string; }
interface InvoiceItem { description: string; quantity: number; unit_price: number; }

export default function EditInvoice() {
    const { invoice, clients, currencies } = usePage().props as any;
    const [form, setForm] = useState({
        client_id: invoice.client_id,
        currency_id: invoice.currency_id || '',
        due_date: invoice.due_date,
        status: invoice.status,
        items: invoice.line_items.map((item: any) => ({
            description: item.description,
            quantity: item.quantity,
            unit_price: item.unit_price,
        })),
        logo: null as File | null,
    });
    const [errors, setErrors] = useState<any>({});

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleItemChange = (idx: number, e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const items = [...form.items];
        items[idx] = { ...items[idx], [e.target.name]: e.target.value };
        setForm({ ...form, items });
    };

    const handleItemDescriptionChange = (idx: number, description: string) => {
        const items = [...form.items];
        items[idx] = { ...items[idx], description };
        setForm({ ...form, items });
    };

    const addItem = () => setForm({ ...form, items: [...form.items, { description: '', quantity: 1, unit_price: 0 }] });
    const removeItem = (idx: number) => setForm({ ...form, items: form.items.filter((_: any, i: number) => i !== idx) });

    const handleFileSelect = (file: File | null) => {
        setForm({ ...form, logo: file });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data = new FormData();
        data.append('client_id', form.client_id);
        data.append('currency_id', form.currency_id);
        data.append('due_date', form.due_date);
        data.append('status', form.status);
        (form.items as InvoiceItem[]).forEach((item: InvoiceItem, idx: number) => {
            data.append(`items[${idx}][description]`, item.description);
            data.append(`items[${idx}][quantity]`, String(item.quantity));
            data.append(`items[${idx}][unit_price]`, String(item.unit_price));
        });
        if (form.logo) {
            data.append('logo', form.logo);
        }
        router.post(`/invoices/${invoice.id}?_method=put`, data, {
            forceFormData: true,
            onError: (err) => setErrors(err),
        });
    };

    const total = (form.items as InvoiceItem[]).reduce((sum: number, item: InvoiceItem) => sum + (Number(item.quantity) * Number(item.unit_price)), 0);
    const selectedCurrency = currencies.find((c: Currency) => c.id == form.currency_id);
    const currencySymbol = selectedCurrency ? selectedCurrency.currency_symbol : '$';

    return (        <AppLayout breadcrumbs={[{ title: 'Invoices', href: '/invoices' }, { title: `Edit Invoice #${invoice.id}`, href: `/invoices/${invoice.id}/edit` }]}>
            <Head title={`Edit Invoice #${invoice.id}`} />
            <div className="w-full mx-auto p-4">
                <div className="flex items-center justify-between mb-4">
                    <h1 className="text-2xl font-bold">Edit Invoice #{invoice.id}</h1>
                    <div className="flex items-center gap-3">
                        <span className="text-sm text-gray-600 dark:text-gray-400">Invoice Logo:</span>
                        <FileUploader
                            onFileSelect={handleFileSelect}
                            accept={['image/*']}
                            maxSizeMB={1}
                            value={form.logo_url}
                            existingFileUrl={invoice.logo_url}
                            deleteUrl={invoice.logo_url ? `/invoices/${invoice.id}/logo` : undefined}
                            compact={true}
                            label={invoice.logo_url ? "Update Logo" : "Add Logo"}
                        />
                    </div>
                </div>
                <form onSubmit={handleSubmit} className="space-y-4 bg-white dark:bg-neutral-900 p-4 rounded shadow">
                    <div>
                        <label className="block mb-1">Client</label>
                        <select name="client_id" value={form.client_id} onChange={handleChange} className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white" required>
                            <option value="">Select client</option>
                            {clients.map((client: Client) => (
                                <option key={client.id} value={client.id}>{client.name}</option>
                            ))}
                        </select>
                        {errors.client_id && <div className="text-red-600 text-sm">{errors.client_id}</div>}
                    </div>
                    <div>
                        <label className="block mb-1">Currency</label>
                        <select name="currency_id" value={form.currency_id} onChange={handleChange} className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white" required>
                            <option value="">Select currency</option>
                            {currencies.map((currency: Currency) => (
                                <option key={currency.id} value={currency.id}>
                                    {currency.currency_code} - {currency.currency_name} ({currency.currency_symbol})
                                </option>
                            ))}
                        </select>
                        {errors.currency_id && <div className="text-red-600 text-sm">{errors.currency_id}</div>}
                    </div>
                    <div>
                        <label className="block mb-1">Due Date</label>
                        <input type="date" name="due_date" value={form.due_date} onChange={handleChange} className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white" required />
                        {errors.due_date && <div className="text-red-600 text-sm">{errors.due_date}</div>}
                    </div>
                    <div>
                        <label className="block mb-1">Status</label>
                        <select name="status" value={form.status} onChange={handleChange} className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white">
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                        </select>
                        {errors.status && <div className="text-red-600 text-sm">{errors.status}</div>}
                    </div>
                    <div>
                        <label className="block mb-1">Line Items</label>
                        <div className="overflow-x-auto">
                            <table className="w-full mb-2 bg-white dark:bg-neutral-900 rounded">
                                <thead>
                                    <tr className="bg-gray-100 dark:bg-neutral-800">
                                        <th className="p-2 text-left">Description</th>
                                        <th className="p-2 text-left">Quantity</th>
                                        <th className="p-2 text-left">Unit Price</th>
                                        <th className="p-2 text-left">Line Total</th>
                                        <th className="p-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {form.items.map((item: InvoiceItem, idx: number) => (
                                        <tr key={idx}>
                                            <td className="p-2">
                                                <TextareaAi
                                                    name="description"
                                                    value={item.description}
                                                    onChange={(e) => handleItemDescriptionChange(idx, e.target.value)}
                                                    placeholder="Description"
                                                    className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white min-h-[60px] resize-none"
                                                    required
                                                    aiLocaleCode="en"
                                                    aiInitialPrompt={item.description || "Generate a professional invoice item description for: "}
                                                    aiDialogTitle="Generate Item Description"
                                                    aiDialogDescription="Generate a professional description for this invoice line item"
                                                    onAiInsert={(generatedText) => handleItemDescriptionChange(idx, generatedText)}
                                                    onAiError={(error) => console.error('AI Error:', error)}
                                                    aiMaxLength={500}
                                                    showAiCharCount={true}
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input name="quantity" type="number" min="1" value={item.quantity} onChange={e => handleItemChange(idx, e)} placeholder="Qty" className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white" required />
                                            </td>
                                            <td className="p-2">
                                                <input name="unit_price" type="number" min="0" value={item.unit_price} onChange={e => handleItemChange(idx, e)} placeholder="Unit Price" className="border p-2 rounded w-full dark:bg-neutral-800 dark:text-white" required />
                                            </td>
                                            <td className="p-2 font-semibold">
                                                {currencySymbol}{ (Number(item.quantity) * Number(item.unit_price)).toFixed(2) }
                                            </td>
                                            <td className="p-2">
                                                <button type="button" onClick={() => removeItem(idx)} className="text-red-600 dark:text-red-400 px-2">Remove</button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>                        <button type="button" onClick={addItem} className="bg-gray-200 dark:bg-neutral-800 dark:text-white px-2 py-1 rounded mt-2">Add Item</button>
                        {errors.items && <div className="text-red-600 text-sm">{errors.items}</div>}
                    </div>
                    {errors.logo && <div className="text-red-600 text-sm">{errors.logo}</div>}
                    <div className="font-bold">Total: {currencySymbol}{total.toFixed(2)}</div>
                    <div className="flex gap-2 justify-end">
                        <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Invoice</button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
