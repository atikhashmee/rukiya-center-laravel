import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { Product, ProductsIndexProps } from '@/types/product';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, show, destroy, edit } from '@/routes/products';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, Eye } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Products', href: index().url },
];

export default function Index({ products }: ProductsIndexProps) {
    const handleDelete = (productId: number) => {
        if (confirm("Are you sure you want to delete this product? This action is irreversible.")) {
            router.delete(destroy(productId), { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Products</h2>
                            <p className="text-sm text-gray-500 mt-1">Manage your product inventory</p>
                        </div>
                        <Button onClick={() => window.location.href = create().url} className="bg-blue-600 hover:bg-blue-700 text-white">
                            <PlusCircle className="mr-2 h-4 w-4" /> Add Product
                        </Button>
                    </div>

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>A list of all products.</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead className="font-bold text-gray-700">Name</TableHead>
                                    <TableHead className="font-bold text-gray-700">SKU</TableHead>
                                    <TableHead className="text-right font-bold text-gray-700">Price</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Stock</TableHead>
                                    <TableHead className="text-center w-[150px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {products.map((product: Product) => (
                                    <TableRow key={product.id} className="hover:bg-indigo-50/50 transition-colors">
                                        <TableCell className="font-semibold text-gray-800">{product.name}</TableCell>
                                        <TableCell className="text-sm text-gray-500 font-mono">{product.sku}</TableCell>
                                        <TableCell className="text-right font-semibold text-gray-800">£{product.price.toFixed(2)}</TableCell>
                                        <TableCell className="text-center">
                                            <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                                                product.stock_quantity > 10 ? 'bg-green-100 text-green-800' :
                                                product.stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-red-100 text-red-800'
                                            }`}>
                                                {product.stock_quantity}
                                            </span>
                                        </TableCell>
                                        <TableCell className="text-center">
                                            <div className="flex gap-2 justify-center">
                                                <Button variant="outline" size="icon" className="h-8 w-8"
                                                    onClick={() => window.location.href = show(product.id).url}>
                                                    <Eye className="h-4 w-4" />
                                                </Button>
                                                <Button variant="outline" size="icon" className="h-8 w-8"
                                                    onClick={() => window.location.href = edit(product.id).url}>
                                                    <Pencil className="h-4 w-4" />
                                                </Button>
                                                <Button variant="destructive" size="icon" className="h-8 w-8"
                                                    onClick={() => handleDelete(product.id)}>
                                                    <Trash2 className="h-4 w-4" />
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
