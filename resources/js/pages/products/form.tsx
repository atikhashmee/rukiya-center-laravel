import React, { useState } from 'react';
import { useForm, router, Form } from '@inertiajs/react';
import { ProductFormProps, ProductImage } from '@/types/product';
import { index, store } from '@/routes/products';
import { update } from '@/routes/profile';
import {Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectLabel, SelectItem } from '@/components/ui/select'
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from "@/components/input-error";

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

export default function ProductForm({ product, categories }: ProductFormProps) {
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
             <form onSubmit={submit}>
                <div className="mb-4">
                    <Label htmlFor="category">Category</Label>
                        <Select onValueChange={(value) => setData("category_id", Number(value))}>
                        <SelectTrigger className="w-full bg-[var(--color-input)] ">
                            <SelectValue placeholder="Select a Category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                            {categories.map((cat) => (
                                <SelectItem key={cat.id} value={cat.id.toString()}>{cat.name}</SelectItem>
                            ))}
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                        {errors.category_id && <InputError message={errors.category_id} />}
                </div>
                
                {/* Basic Product Fields */}
                {[{ name: 'name', type: 'text' }, { name: 'sku', type: 'text' }, { name: 'price', type: 'number' }, { name: 'stock_quantity', type: 'number' }]
                    .map(({ name, type }) => (
                    <div className="mb-4" key={name}>
                            <Label htmlFor="{name}">{name.replace('_', ' ')}</Label>
                        <Input
                            type={type}
                            step={name === 'price' ? '0.01' : '1'}
                            value={data[name as keyof ProductFormData] as string | number}
                            onChange={(e) => setData(name as keyof ProductFormData, type === 'number' ? Number(e.target.value) : e.target.value)}
                            className='bg-[var(--color-input)]'
                        />
                        {errors[name as keyof ProductFormData] && <InputError message={errors[name as keyof ProductFormData]} />}
                    </div>
                ))}
                
                {/* Image Upload Field */}
                <div className="mb-4">
                    <Label htmlFor="title">Product Images (New)</Label>
                    <Input
                        type="file"
                        // multiple
                        onChange={handleImageChange}
                        className='bg-[var(--color-input)]'
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
                    <Button>
                        {isEdit ? 'Update Product' : 'Create Product'}
                    </Button>
                </div>
            </form>
    );
}