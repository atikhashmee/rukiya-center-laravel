import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { Product, InertiaProps } from '@/types/product';
import { index, edit } from '@/routes/products';
import { CornerUpLeft } from 'lucide-react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';

interface ProductShowProps extends InertiaProps {
    product: Product;
}

export default function Show({ product }: ProductShowProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Products', href: index().url },
        { title: product.name, href: '#' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={product.name} />
            <div className="container py-4 pl-4 max-w-4xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <Link
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Products
                        </Link>
                        <Link
                            href={edit(product.id)}
                            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center shadow-sm"
                        >
                            Edit Product
                        </Link>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl">
                        <h1 className="text-3xl font-bold text-gray-900 mb-4">{product.name}</h1>

                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                            <div>
                                <span className="font-medium text-gray-500">SKU</span>
                                <p className="text-gray-900 font-mono">{product.sku}</p>
                            </div>
                            <div>
                                <span className="font-medium text-gray-500">Category</span>
                                <p className="text-gray-900">{product.category?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <span className="font-medium text-gray-500">Price</span>
                                <p className="text-gray-900 font-semibold">£{product.price.toFixed(2)}</p>
                            </div>
                            <div>
                                <span className="font-medium text-gray-500">Stock</span>
                                <p className="text-gray-900">{product.stock_quantity}</p>
                            </div>
                        </div>

                        <div className="mb-6">
                            <span className="font-medium text-gray-500 text-sm">Status: </span>
                            <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                                product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            }`}>
                                {product.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>

                        {product.description && (
                            <div className="mb-6">
                                <h2 className="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                                <p className="text-gray-700">{product.description}</p>
                            </div>
                        )}
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl">
                        <h2 className="text-lg font-semibold text-gray-900 mb-4">Images</h2>
                        {product.images && product.images.length > 0 ? (
                            <div className="flex flex-wrap gap-4">
                                {product.images.map((image) => (
                                    <img
                                        key={image.id}
                                        src={image.path}
                                        alt={product.name}
                                        className="w-40 h-40 object-cover rounded-lg shadow"
                                    />
                                ))}
                            </div>
                        ) : (
                            <p className="text-gray-500 text-sm">No images available.</p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
