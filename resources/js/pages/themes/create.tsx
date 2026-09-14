import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, store } from "@/actions/App/Http/Controllers/ThemeController";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";
import PageHeader from "@/components/page-header";
import { ArrowLeft } from 'lucide-react';

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
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Create New Theme"
                    description="Set up a new theme for the public website"
                    actions={
                        <Button variant="outline" asChild>
                            <a href={index().url}>
                                <ArrowLeft className="h-4 w-4" />
                                Back to Themes
                            </a>
                        </Button>
                    }
                />

                <form onSubmit={handleSubmit} className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="grid gap-5 p-5">
                        <div className="grid gap-2">
                            <Label htmlFor="name">Theme Name *</Label>
                            <Input
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                placeholder="e.g. Summer Theme"
                            />
                            <InputError message={errors.name} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="description">Description (optional)</Label>
                            <Input
                                id="description"
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                placeholder="A brief description of this theme"
                            />
                            <InputError message={errors.description} />
                        </div>
                    </div>
                    <div className="flex justify-end gap-2 border-t px-5 py-4">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => window.location.href = index().url}
                        >
                            Cancel
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Creating...' : 'Create Theme'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
