import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { Product } from '@/types/product';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, show, destroy, edit } from '@/routes/products';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, Eye } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';

interface Category {
    id: number;
    name: string;
    slug: string;
}

interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface ProductsIndexProps {
    products: PaginatedProducts;
    categories: Category[];
    filters: Record<string, string>;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Products', href: index().url },
];

export default function Index({ products, categories, filters }: ProductsIndexProps) {
    const { flash } = usePage().props as any;

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
                    {flash?.success && (
                        <div className="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}

                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Products</h2>
                            <p className="text-sm text-gray-500 mt-1">{products.total} total products</p>
                        </div>
                        <Button onClick={() => window.location.href = create().url} className="bg-blue-600 hover:bg-blue-700 text-white">
                            <PlusCircle className="mr-2 h-4 w-4" /> Add Product
                        </Button>
                    </div>

                    <FilterBar
                        filters={filters}
                        placeholder="Search by name or SKU..."
                        baseUrl={index().url}
                        filterConfigs={[
                            {
                                key: 'category_id',
                                label: 'All Categories',
                                options: categories.map(c => ({ label: c.name, value: String(c.id) })),
                            },
                            {
                                key: 'stock',
                                label: 'All Stock',
                                options: [
                                    { label: 'In Stock', value: 'in_stock' },
                                    { label: 'Out of Stock', value: 'out_of_stock' },
                                ],
                            },
                            {
                                key: 'status',
                                label: 'All Status',
                                options: [
                                    { label: 'Active', value: 'active' },
                                    { label: 'Inactive', value: 'inactive' },
                                ],
                            },
                        ]}
                    />

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>A list of all products.</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead className="font-bold text-gray-700">Name</TableHead>
                                    <TableHead className="font-bold text-gray-700">SKU</TableHead>
                                    <TableHead className="font-bold text-gray-700">Category</TableHead>
                                    <TableHead className="text-right font-bold text-gray-700">Price</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Stock</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Status</TableHead>
                                    <TableHead className="text-center w-[150px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {products.data.length > 0 ? (
                                    products.data.map((product: Product) => (
                                        <TableRow key={product.id} className="hover:bg-gray-50 transition-colors">
                                            <TableCell className="font-semibold text-gray-800">{product.name}</TableCell>
                                            <TableCell className="text-sm text-gray-500 font-mono">{product.sku}</TableCell>
                                            <TableCell className="text-sm text-gray-600">{product.category?.name || '-'}</TableCell>
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
                                                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${product.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'}`}>
                                                    {product.is_active ? 'Active' : 'Inactive'}
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
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={7} className="h-24 text-center text-gray-400">
                                            No products found matching your filters.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={products.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
