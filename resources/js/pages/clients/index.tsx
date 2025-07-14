import AppLayout from '@/layouts/app-layout';
import { Head, usePage, router } from '@inertiajs/react';
import React, { useState, ChangeEvent, FormEvent } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogClose } from '@/components/ui/dialog';

interface Client {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export default function Clients() {
    const { clients, flash } = usePage().props as any;
    const [form, setForm] = useState<{ name: string; email: string; phone: string; address: string; id: number | null }>({ name: '', email: '', phone: '', address: '', id: null });
    const [editing, setEditing] = useState(false);
    const [open, setOpen] = useState(false);

    const handleChange = (e: ChangeEvent<HTMLInputElement>) => setForm({ ...form, [e.target.name]: e.target.value });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        if (editing && form.id !== null) {
            router.put(`/clients/${form.id}`, form, {
                onSuccess: () => { setForm({ name: '', email: '', phone: '', address: '', id: null }); setEditing(false); setOpen(false); },
            });
        } else {
            router.post('/clients', form, {
                onSuccess: () => { setForm({ name: '', email: '', phone: '', address: '', id: null }); setOpen(false); },
            });
        }
    };

    const handleEdit = (client: Client) => {
        setForm({
            name: client.name,
            email: client.email,
            phone: client.phone || '',
            address: client.address || '',
            id: client.id,
        });
        setEditing(true);
        setOpen(true);
    };

    const handleAdd = () => {
        setForm({ name: '', email: '', phone: '', address: '', id: null });
        setEditing(false);
        setOpen(true);
    };

    const handleDelete = (id: number) => {
        if (confirm('Are you sure?')) router.delete(`/clients/${id}`);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Clients', href: '/clients' }]}>
            <Head title="Clients" />
            <div className="w-full mx-auto p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl font-bold">Clients</h1>
                    <Dialog open={open} onOpenChange={setOpen}>
                        <DialogTrigger asChild>
                            <button onClick={handleAdd} className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Add Client</button>
                        </DialogTrigger>
                        <DialogContent className="dark:bg-neutral-900">
                            <DialogHeader>
                                <DialogTitle>{editing ? 'Edit Client' : 'Add Client'}</DialogTitle>
                            </DialogHeader>
                            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input name="name" value={form.name} onChange={handleChange} placeholder="Name" className="border p-2 rounded bg-white dark:bg-neutral-800 dark:text-white" required />
                                <input name="email" value={form.email} onChange={handleChange} placeholder="Email" className="border p-2 rounded bg-white dark:bg-neutral-800 dark:text-white" required type="email" />
                                <input name="phone" value={form.phone} onChange={handleChange} placeholder="Phone" className="border p-2 rounded bg-white dark:bg-neutral-800 dark:text-white" />
                                <input name="address" value={form.address} onChange={handleChange} placeholder="Address" className="border p-2 rounded bg-white dark:bg-neutral-800 dark:text-white" />
                                <div className="col-span-2 flex gap-2 justify-end">
                                    <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">{editing ? 'Update' : 'Add'} Client</button>
                                    <DialogClose asChild>
                                        <button type="button" className="bg-gray-300 dark:bg-neutral-700 dark:text-white px-4 py-2 rounded">Cancel</button>
                                    </DialogClose>
                                </div>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
                {flash && flash.success && <div className="mb-4 p-2 bg-green-100 text-green-800 rounded dark:bg-green-900 dark:text-green-200">{flash.success}</div>}
                <table className="w-full bg-white dark:bg-neutral-900 rounded shadow">
                    <thead>
                        <tr className="bg-gray-100 dark:bg-neutral-800">
                            <th className="p-2 text-left">Name</th>
                            <th className="p-2 text-left">Email</th>
                            <th className="p-2 text-left">Phone</th>
                            <th className="p-2 text-left">Address</th>
                            <th className="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {clients.data.map((client: Client) => (
                            <tr key={client.id} className="border-t border-gray-200 dark:border-neutral-800">
                                <td className="p-2">{client.name}</td>
                                <td className="p-2">{client.email}</td>
                                <td className="p-2">{client.phone}</td>
                                <td className="p-2">{client.address}</td>
                                <td className="p-2 flex gap-2">
                                    <button onClick={() => handleEdit(client)} className="text-blue-600 hover:underline dark:text-blue-400">Edit</button>
                                    <button onClick={() => handleDelete(client.id)} className="text-red-600 hover:underline dark:text-red-400">Delete</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                {/* Pagination */}
                <div className="mt-4 flex justify-center gap-2">
                    {clients.links && clients.links.map((link: PaginationLink, i: number) => (
                        <button key={i} disabled={!link.url} onClick={() => link.url && router.visit(link.url)} className={`px-2 py-1 rounded ${link.active ? 'bg-blue-600 text-white dark:bg-blue-800' : 'bg-gray-200 dark:bg-neutral-800 dark:text-white'}`}>{link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</button>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
