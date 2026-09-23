import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { Product } from '@/types/product';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, show, destroy, edit } from '@/routes/products';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, Eye, Package } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';
import { useCan } from '@/lib/permissions';

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

const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

export default function Index({ products, categories, filters }: ProductsIndexProps) {
    const { flash } = usePage().props as any;
    const canManage = useCan('products.manage');

    const handleDelete = (productId: number) => {
        if (confirm("Are you sure you want to delete this product? This action is irreversible.")) {
            router.delete(destroy(productId), { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                {flash?.success && (
                    <div className="rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        {flash.success}
                    </div>
                )}

                <PageHeader
                    title="Products"
                    description={`${products.total} total products`}
                    actions={canManage && (
                        <Button onClick={() => window.location.href = create().url}>
                            <Plus className="h-4 w-4" /> Add Product
                        </Button>
                    )}
                />

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

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={th}>Name</TableHead>
                                    <TableHead className={th}>SKU</TableHead>
                                    <TableHead className={th}>Category</TableHead>
                                    <TableHead className={`text-right ${th}`}>Price</TableHead>
                                    <TableHead className={`text-center ${th}`}>Stock</TableHead>
                                    <TableHead className={`text-center ${th}`}>Status</TableHead>
                                    <TableHead className={`w-[150px] text-right ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {products.data.length > 0 ? (
                                    products.data.map((product: Product) => (
                                        <TableRow key={product.id} className="hover:bg-muted/40">
                                            <TableCell className="font-medium">{product.name}</TableCell>
                                            <TableCell className="font-mono text-xs text-muted-foreground">{product.sku}</TableCell>
                                            <TableCell className="text-muted-foreground">{product.category?.name || '-'}</TableCell>
                                            <TableCell className="text-right font-medium tabular-nums">£{product.price.toFixed(2)}</TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses(
                                                    product.stock_quantity > 10 ? 'success' :
                                                    product.stock_quantity > 0 ? 'warning' : 'danger'
                                                )}>
                                                    {product.stock_quantity}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses(product.is_active ? 'success' : 'neutral')}>
                                                    {product.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex justify-end gap-1">
                                                    <Button variant="ghost" size="icon" className="h-8 w-8" title="View"
                                                        onClick={() => window.location.href = show(product.id).url}>
                                                        <Eye className="h-4 w-4" />
                                                    </Button>
                                                    {canManage && (
                                                        <>
                                                            <Button variant="ghost" size="icon" className="h-8 w-8" title="Edit"
                                                                onClick={() => window.location.href = edit(product.id).url}>
                                                                <Pencil className="h-4 w-4" />
                                                            </Button>
                                                            <Button variant="ghost" size="icon" title="Delete"
                                                                className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                onClick={() => handleDelete(product.id)}>
                                                                <Trash2 className="h-4 w-4" />
                                                            </Button>
                                                        </>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={7}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <Package className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No products found matching your filters.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={products.links} />
                </div>
            </div>
        </AppLayout>
    );
}
