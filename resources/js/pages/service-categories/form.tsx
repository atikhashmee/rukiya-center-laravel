import React from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from '@/components/input-error';
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
        <form onSubmit={handleSubmit} className="space-y-5">
            <div className="grid gap-2">
                <Label htmlFor="name">Category Name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    aria-invalid={!!errors.name}
                    placeholder="e.g. Hijamah"
                />
                <InputError message={errors.name} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="description">Description</Label>
                <Input
                    id="description"
                    value={data.description}
                    onChange={(e) => setData('description', e.target.value)}
                    aria-invalid={!!errors.description}
                    placeholder="Short blurb shown on the category tile"
                />
                <InputError message={errors.description} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="icon">Icon</Label>
                <Input
                    id="icon"
                    value={data.icon}
                    onChange={(e) => setData('icon', e.target.value)}
                    aria-invalid={!!errors.icon}
                    placeholder="Lucide icon name, e.g. waves, compass, heart-handshake"
                />
                <p className="text-xs text-muted-foreground">
                    Any icon name from <a href="https://lucide.dev/icons" target="_blank" rel="noreferrer" className="text-primary hover:underline">lucide.dev/icons</a>. Leave blank for a default icon.
                </p>
                <InputError message={errors.icon} />
            </div>

            {category && (
                <div className="grid gap-1 rounded-lg border bg-muted/50 px-4 py-3">
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
