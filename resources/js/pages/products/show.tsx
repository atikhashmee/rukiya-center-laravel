import { Head, Link } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { InertiaProps, Product } from '@/types/product';
import { edit, index } from '@/routes/products';

// Define specific props for the Show page
interface ProductShowProps extends InertiaProps {
    product: Product;
}

export default function Show({ product }: ProductShowProps) {
    return (
        <AppLayout>
            <Head title={product.name} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div className="mb-6">
                            <h3 className="text-2xl font-bold mb-4">{product.name}</h3>
                            <p><strong>SKU:</strong> {product.sku}</p>
                            <p><strong>Category:</strong> {product.category.name}</p>
                            <p><strong>Price:</strong> <span className="text-green-600">${product.price.toFixed(2)}</span></p>
                            <p><strong>Stock:</strong> {product.stock_quantity}</p>
                            <p><strong>Status:</strong> <span className={`font-semibold ${product.is_active ? 'text-green-500' : 'text-red-500'}`}>{product.is_active ? 'Active' : 'Inactive'}</span></p>
                            <p className="mt-4"><strong>Description:</strong> {product.description || 'N/A'}</p>
                        </div>
                        
                        <h4 className="text-xl font-semibold mb-3 border-b pb-1">Images</h4>
                        <div className="flex flex-wrap gap-4">
                            {product.images.length > 0 ? (
                                product.images.map((image) => (
                                    <img key={image.id} src={image.path} alt={`Image of ${product.name}`} 
                                         className="w-40 h-40 object-cover rounded shadow" />
                                ))
                            ) : (
                                <p className="text-gray-500">No images available.</p>
                            )}
                        </div>

                        <div className="mt-6 flex gap-4">
                            <Link href={ edit(product.id)} className="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                Edit Product
                            </Link>
                            <Link href={index()} className="text-gray-600 hover:text-gray-800 py-2 px-4 rounded border">
                                Back to List
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}