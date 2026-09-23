import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { Product, InertiaProps } from '@/types/product';
import { index, edit } from '@/routes/products';
import { ArrowLeft, ImageOff, Pencil } from 'lucide-react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Button } from '@/components/ui/button';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';
import { useCan } from '@/lib/permissions';

interface ProductShowProps extends InertiaProps {
    product: Product;
}

export default function Show({ product }: ProductShowProps) {
    const canManage = useCan('products.manage');
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Products', href: index().url },
        { title: product.name, href: '#' },
    ];

    const details = [
        { label: 'SKU', value: <span className="font-mono">{product.sku}</span> },
        { label: 'Category', value: product.category?.name || 'N/A' },
        { label: 'Price', value: <span className="font-semibold tabular-nums">£{product.price.toFixed(2)}</span> },
        { label: 'Stock', value: <span className="tabular-nums">{product.stock_quantity}</span> },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={product.name} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={
                        <span className="flex flex-wrap items-center gap-3">
                            {product.name}
                            <span className={badgeClasses(product.is_active ? 'success' : 'danger')}>
                                {product.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </span>
                    }
                    actions={
                        <>
                            <Button variant="outline" asChild>
                                <Link href={index().url}>
                                    <ArrowLeft className="h-4 w-4" /> Back to Products
                                </Link>
                            </Button>
                            {canManage && (
                                <Button asChild>
                                    <Link href={edit(product.id)}>
                                        <Pencil className="h-4 w-4" /> Edit Product
                                    </Link>
                                </Button>
                            )}
                        </>
                    }
                />

                <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="border-b px-5 py-4">
                        <h2 className="font-semibold">Details</h2>
                    </div>
                    <div className="p-5">
                        <dl className="grid grid-cols-2 gap-5 text-sm md:grid-cols-4">
                            {details.map((d) => (
                                <div key={d.label}>
                                    <dt className="text-xs font-medium uppercase tracking-wide text-muted-foreground">{d.label}</dt>
                                    <dd className="mt-1">{d.value}</dd>
                                </div>
                            ))}
                        </dl>

                        {product.description && (
                            <div className="mt-6 border-t pt-5">
                                <h3 className="mb-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">Description</h3>
                                <p className="text-sm leading-relaxed">{product.description}</p>
                            </div>
                        )}
                    </div>
                </div>

                <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="border-b px-5 py-4">
                        <h2 className="font-semibold">Images</h2>
                    </div>
                    <div className="p-5">
                        {product.images && product.images.length > 0 ? (
                            <div className="flex flex-wrap gap-4">
                                {product.images.map((image) => (
                                    <img
                                        key={image.id}
                                        src={image.path}
                                        alt={product.name}
                                        className="h-40 w-40 rounded-lg border object-cover"
                                    />
                                ))}
                            </div>
                        ) : (
                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                <ImageOff className="h-8 w-8 opacity-40" />
                                <p className="text-sm">No images available.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
