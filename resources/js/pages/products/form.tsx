import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { ProductFormProps, ProductImage } from '@/types/product';
import { index, store } from '@/routes/products';
import { update } from '@/routes/profile';

// Define the shape of the form data
interface ProductFormData {
    category_id: number | '';
    name: string;
    description: string;
    sku: string;
    price: number | '';
    stock_quantity: number | '';
    is_active: boolean;
    // For creation
    images: File[]; 
    // For update
    new_images: File[];
    delete_image_ids: number[];
    _method: 'put' | 'post' | undefined; // For Inertia PUT/POST file handling
}

export default function ProductForm({ auth, product, categories, breadcrumbs }: ProductFormProps) {
    const isEdit = !!product;

    const { data, setData, errors, processing } = useForm<ProductFormData>({
        category_id: product?.category_id || '',
        name: product?.name || '',
        description: product?.description || '',
        sku: product?.sku || '',
        price: product?.price || '',
        stock_quantity: product?.stock_quantity || '',
        is_active: product?.is_active || true,
        images: [],
        new_images: [],
        delete_image_ids: [],
        _method: isEdit ? 'put' : 'post',
    });
    
    // State to manage images visible in the UI for deletion in Edit mode
    const [currentImages, setCurrentImages] = useState<ProductImage[]>(product?.images || []);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        const routeName = isEdit ? 'products.update' : 'products.store';
        const routeParams = isEdit ? product.product_id : undefined;

        // Use Inertia's router for file uploads
        router.post( isEdit ? update() : store(), {
            ...data,
            // Only include relevant image fields based on mode
            images: !isEdit ? data.images : undefined, 
            new_images: isEdit ? data.new_images : undefined,
        } as any, { // Using 'as any' here because Inertia expects FormData which can't be fully typed here
            forceFormData: true, 
            onSuccess: () => {
                router.visit(index());
            },
        });
    };
    
    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.target.files || []) as File[];
        
        if (isEdit) {
            setData('new_images', files);
        } else {
            setData('images', files);
        }
    };
    
    const handleDeleteImage = (imageId: number) => {
        if (isEdit) {
            // 1. Update form data to signal deletion to the backend
            setData('delete_image_ids', [...data.delete_image_ids, imageId]);
            // 2. Optimistically update UI
            setCurrentImages(prev => prev.filter(img => img.id !== imageId));
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={isEdit ? 'Edit Product' : 'Create Product'} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <form onSubmit={submit}>
                            
                            {/* Category Select */}
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700">Category</label>
                                <select 
                                    value={data.category_id}
                                    onChange={(e) => setData('category_id', Number(e.target.value))}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                >
                                    <option value="">Select a Category</option>
                                    {categories.map(cat => (
                                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                                    ))}
                                </select>
                                {errors.category_id && <div className="text-red-500 text-sm mt-1">{errors.category_id}</div>}
                            </div>
                            
                            {/* Basic Product Fields */}
                            {[{ name: 'name', type: 'text' }, { name: 'sku', type: 'text' }, { name: 'price', type: 'number' }, { name: 'stock_quantity', type: 'number' }]
                                .map(({ name, type }) => (
                                <div className="mb-4" key={name}>
                                    <label className="block text-sm font-medium text-gray-700 capitalize">{name.replace('_', ' ')}</label>
                                    <input
                                        type={type}
                                        step={name === 'price' ? '0.01' : '1'}
                                        value={data[name as keyof ProductFormData] as string | number}
                                        onChange={(e) => setData(name as keyof ProductFormData, type === 'number' ? Number(e.target.value) : e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    />
                                    {errors[name as keyof ProductFormData] && <div className="text-red-500 text-sm mt-1">{errors[name as keyof ProductFormData]}</div>}
                                </div>
                            ))}
                            
                            {/* Image Upload Field */}
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700">Product Images (New)</label>
                                <input
                                    type="file"
                                    multiple
                                    onChange={handleImageChange}
                                    className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                {errors.images && <div className="text-red-500 text-sm mt-1">{errors.images}</div>}
                            </div>
                            
                            {/* Existing Images (Edit only) */}
                            {isEdit && currentImages.length > 0 && (
                                <div className="mb-4">
                                    <h3 className="text-lg font-semibold mb-2">Existing Images</h3>
                                    <div className="flex flex-wrap gap-4">
                                        {currentImages.map((image) => (
                                            <div key={image.id} className="relative w-32 h-32 border rounded shadow">
                                                <img src={image.path} alt="Product" className="w-full h-full object-cover rounded" />
                                                <button 
                                                    type="button"
                                                    onClick={() => handleDeleteImage(image.id)} 
                                                    className="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-800"
                                                    title="Delete Image"
                                                >
                                                    &times;
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                    {errors.delete_image_ids && <div className="text-red-500 text-sm mt-1">{errors.delete_image_ids}</div>}
                                </div>
                            )}

                            {/* Submit Button */}
                            <div className="flex items-center justify-end mt-4">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    {isEdit ? 'Update Product' : 'Create Product'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}