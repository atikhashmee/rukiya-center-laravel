import React from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { store, update } from '@/routes/serviceCategories';

interface CategoryFormProps {
    category?: {
        id: number;
        name: string;
        slug: string;
        description: string | null;
        icon: string | null;
    };
}

export default function CategoryForm({ category }: CategoryFormProps) {
    const { data, setData, post, put, processing, errors } = useForm({
        name: category?.name || '',
        description: category?.description || '',
        icon: category?.icon || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (category) {
            put(update.url(category.id));
        } else {
            post(store.url());
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            <div>
                <Label htmlFor="name" className="text-sm font-medium text-gray-700">
                    Category Name
                </Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    className="mt-1 block w-full"
                    placeholder="e.g. Hijamah"
                />
                {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
            </div>

            <div>
                <Label htmlFor="description" className="text-sm font-medium text-gray-700">
                    Description
                </Label>
                <Input
                    id="description"
                    value={data.description}
                    onChange={(e) => setData('description', e.target.value)}
                    className="mt-1 block w-full"
                    placeholder="Short blurb shown on the category tile"
                />
                {errors.description && <p className="text-xs text-red-500 mt-1">{errors.description}</p>}
            </div>

            <div>
                <Label htmlFor="icon" className="text-sm font-medium text-gray-700">
                    Icon
                </Label>
                <Input
                    id="icon"
                    value={data.icon}
                    onChange={(e) => setData('icon', e.target.value)}
                    className="mt-1 block w-full"
                    placeholder="Lucide icon name, e.g. waves, compass, heart-handshake"
                />
                <p className="text-xs text-gray-400 mt-1">
                    Any icon name from <a href="https://lucide.dev/icons" target="_blank" rel="noreferrer" className="underline">lucide.dev/icons</a>. Leave blank for a default icon.
                </p>
                {errors.icon && <p className="text-xs text-red-500 mt-1">{errors.icon}</p>}
            </div>

            {category && (
                <div>
                    <Label className="text-sm font-medium text-gray-700">Slug</Label>
                    <p className="mt-1 text-sm text-gray-500 font-mono">{category.slug}</p>
                    <p className="text-xs text-gray-400 mt-1">Auto-generated from name on save.</p>
                </div>
            )}

            <div className="flex justify-end">
                <Button type="submit" disabled={processing} className="bg-blue-600 hover:bg-blue-700 text-white">
                    {processing ? 'Saving...' : category ? 'Update Category' : 'Create Category'}
                </Button>
            </div>
        </form>
    );
}
