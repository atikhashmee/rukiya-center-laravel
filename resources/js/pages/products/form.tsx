import React, { useState } from 'react';
import { useForm, router, Form } from '@inertiajs/react';
import { ProductFormProps, ProductImage } from '@/types/product';
import { index, store, update } from '@/routes/products';
import {Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectLabel, SelectItem } from '@/components/ui/select'
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from "@/components/input-error";
import { X } from 'lucide-react';

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

        // POST with _method=put so Inertia can send files on update
        router.post(isEdit ? update(product.id) : store(), {
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
             <form onSubmit={submit} className="grid gap-6">
                <div className="grid gap-2">
                    <Label htmlFor="category">Category</Label>
                        <Select onValueChange={(value) => setData("category_id", Number(value))}>
                        <SelectTrigger className="w-full">
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
                <div className="grid gap-5 sm:grid-cols-2">
                {[{ name: 'name', type: 'text' }, { name: 'sku', type: 'text' }, { name: 'price', type: 'number' }, { name: 'stock_quantity', type: 'number' }]
                    .map(({ name, type }) => (
                    <div className="grid gap-2" key={name}>
                            <Label htmlFor="{name}" className="capitalize">{name.replace('_', ' ')}</Label>
                        <Input
                            type={type}
                            step={name === 'price' ? '0.01' : '1'}
                            value={data[name as keyof ProductFormData] as string | number}
                            onChange={(e) => setData(name as keyof ProductFormData, type === 'number' ? Number(e.target.value) : e.target.value)}
                        />
                        {errors[name as keyof ProductFormData] && <InputError message={errors[name as keyof ProductFormData]} />}
                    </div>
                ))}
                </div>

                {/* Image Upload Field */}
                <div className="grid gap-2">
                    <Label htmlFor="title">Product Images (New)</Label>
                    <Input
                        type="file"
                        onChange={handleImageChange}
                        className="cursor-pointer"
                    />
                    {errors.images && <InputError message={errors.images} />}
                </div>

                {/* Existing Images (Edit only) */}
                {isEdit && currentImages.length > 0 && (
                    <div className="grid gap-3">
                        <h3 className="text-sm font-medium">Existing Images</h3>
                        <div className="flex flex-wrap gap-4">
                            {currentImages.map((image) => (
                                <div key={image.id} className="group relative h-32 w-32 overflow-hidden rounded-lg border bg-muted/50">
                                    <img src={image.path} alt="Product" className="h-full w-full object-cover" />
                                    <button
                                        type="button"
                                        onClick={() => handleDeleteImage(image.id)}
                                        className="absolute top-1.5 right-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-destructive text-white shadow-sm transition hover:bg-destructive/90 focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                        title="Delete Image"
                                    >
                                        <X className="h-3.5 w-3.5" />
                                    </button>
                                </div>
                            ))}
                        </div>
                        {errors.delete_image_ids && <InputError message={errors.delete_image_ids} />}
                    </div>
                )}

                {/* Submit Button */}
                <div className="flex justify-end gap-2 border-t pt-5">
                    <Button type="submit" disabled={processing}>
                        {isEdit ? 'Update Product' : 'Create Product'}
                    </Button>
                </div>
            </form>
    );
}
