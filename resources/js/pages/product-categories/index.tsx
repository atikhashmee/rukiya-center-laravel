import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, destroy, edit } from '@/routes/productCategories';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';

interface ProductCategory {
    id: number;
    name: string;
    slug: string;
    products_count: number;
}

interface PaginatedCategories {
    data: ProductCategory[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface CategoriesIndexProps {
    categories: PaginatedCategories;
    filters: Record<string, string>;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Categories', href: index().url },
];

export default function Index({ categories, filters }: CategoriesIndexProps) {
    const { flash } = usePage().props as any;

    const handleDelete = (categoryId: number) => {
        if (confirm("Are you sure you want to delete this category? This action is irreversible.")) {
            router.delete(destroy(categoryId), { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Product Categories" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    {flash?.success && (
                        <div className="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}

                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Categories</h2>
                            <p className="text-sm text-gray-500 mt-1">{categories.total} total categories</p>
                        </div>
                        <Button onClick={() => window.location.href = create().url} className="bg-blue-600 hover:bg-blue-700 text-white">
                            <PlusCircle className="mr-2 h-4 w-4" /> Add Category
                        </Button>
                    </div>

                    <FilterBar
                        filters={filters}
                        placeholder="Search by name..."
                        baseUrl={index().url}
                        filterConfigs={[]}
                    />

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>A list of all product categories.</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead className="font-bold text-gray-700">Name</TableHead>
                                    <TableHead className="font-bold text-gray-700">Slug</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Products</TableHead>
                                    <TableHead className="text-center w-[150px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {categories.data.length > 0 ? (
                                    categories.data.map((category) => (
                                        <TableRow key={category.id} className="hover:bg-gray-50 transition-colors">
                                            <TableCell className="font-semibold text-gray-800">{category.name}</TableCell>
                                            <TableCell className="text-sm text-gray-500 font-mono">{category.slug}</TableCell>
                                            <TableCell className="text-center">
                                                <span className="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                                    {category.products_count}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex gap-2 justify-center">
                                                    <Button variant="outline" size="icon" className="h-8 w-8"
                                                        onClick={() => window.location.href = edit(category.id).url}>
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    <Button variant="destructive" size="icon" className="h-8 w-8"
                                                        onClick={() => handleDelete(category.id)}>
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={4} className="h-24 text-center text-gray-400">
                                            No categories found.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={categories.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
