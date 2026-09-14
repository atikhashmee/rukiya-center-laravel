import React from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from '@/components/input-error';
import { store, update } from '@/routes/productCategories';

interface CategoryFormProps {
    category?: {
        id: number;
        name: string;
        slug: string;
    };
}

export default function CategoryForm({ category }: CategoryFormProps) {
    const { data, setData, post, put, processing, errors } = useForm({
        name: category?.name || '',
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
        <form onSubmit={handleSubmit} className="grid gap-6">
            <div className="grid gap-2">
                <Label htmlFor="name">Category Name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    placeholder="e.g. Essential Oils"
                />
                <InputError message={errors.name} />
            </div>

            {category && (
                <div className="grid gap-1.5 rounded-lg border bg-muted/50 px-4 py-3">
                    <Label>Slug</Label>
                    <p className="font-mono text-sm">{category.slug}</p>
                    <p className="text-xs text-muted-foreground">Auto-generated from name on save.</p>
                </div>
            )}

            <div className="flex justify-end gap-2 border-t pt-5">
                <Button type="submit" disabled={processing}>
                    {processing ? 'Saving...' : category ? 'Update Category' : 'Create Category'}
                </Button>
            </div>
        </form>
    );
}
