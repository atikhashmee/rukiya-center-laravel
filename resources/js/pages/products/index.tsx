import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { ProductsIndexProps } from '@/types/product'; 
import { BreadcrumbItem} from "@/types";
import { dashboard } from '@/routes';
import { index, create, show, destroy, edit } from '@/routes/products';
import { Button } from "@/components/ui/button";
import { Table, TableCaption, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/components/ui/table";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Products',
        href: index().url,
    },
];

export default function Index({ auth, products }: ProductsIndexProps) {
    const handleDelete = (productId: number) => {
        if (confirm("Are you sure you want to delete this product? This action is irreversible.")) {
            router.delete(destroy(productId), {
                onSuccess: () => {
                    console.log('Product deleted!');
                },
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            <div className="container py-4 pl-4">
                <div className="flex justify-end mb-4">
                    <Button onClick={() => window.location.href = create().url}> Add New Product</Button>
                        </div>
                        
                        <div className="p-3 border rounded-lg bg-[var(--background)] md:col-span-12">
                            <Table className="min-w-full divide-y divide-gray-200">
                                 <TableCaption>A list of your recent Products.</TableCaption>
                                 <TableHeader>
                                <TableRow>
                                    <TableHead className="w-[100px]">SL. NO.</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>SKU</TableHead>
                                    <TableHead>Price</TableHead>
                                    <TableHead>Stock</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                              <TableBody>
                                    {products.data.map((product) => (
                                        <TableRow key={product.name}>
                                        <TableCell className="font-medium">1</TableCell>
                                        <TableCell>{product.name}</TableCell>
                                        <TableCell>{product.sku}</TableCell>
                                        <TableCell>{product.price.toFixed(2)}</TableCell>
                                        <TableCell>{product.stock_quantity}</TableCell>
                                        <TableCell className="text-right flex flex-row gap-3">
                                            <Button onClick={() => show(product.id)}> show</Button>
                                            <Button onClick={() => edit(product.id)}> Edit</Button>
                                            <Button className="bg-red-900" onClick={() => handleDelete(product.id)}> Delete</Button>
                                        </TableCell>
                                    </TableRow>
                                    ))}
                              </TableBody>
                            </Table>
                        </div>
            </div>
        </AppLayout>
    );
}