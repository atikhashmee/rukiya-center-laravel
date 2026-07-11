import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, store } from "@/actions/App/Http/Controllers/ThemeController";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";
import { Palette } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Themes', href: index().url },
    { title: 'Create', href: '#' },
];

export default function ThemeCreate() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        description: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(store().url, {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Theme" />
            <div className="container p-4">
                <div className="max-w-2xl">
                    <h1 className="text-2xl font-bold flex items-center gap-2 mb-6">
                        <Palette className="h-6 w-6" />
                        Create New Theme
                    </h1>
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="space-y-2">
                            <Label htmlFor="name">Theme Name</Label>
                            <Input
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                placeholder="e.g. Summer Theme"
                            />
                            <InputError message={errors.name} />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="description">Description (optional)</Label>
                            <Input
                                id="description"
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                placeholder="A brief description of this theme"
                            />
                            <InputError message={errors.description} />
                        </div>
                        <p className="text-sm text-muted-foreground">
                            After creating, you can edit each page's blade template using the built-in code editor.
                        </p>
                        <div className="flex gap-2">
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Creating...' : 'Create Theme'}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => window.location.href = index().url}
                            >
                                Cancel
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
