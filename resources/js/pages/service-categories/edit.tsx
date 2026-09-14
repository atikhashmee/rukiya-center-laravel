import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from '@/routes/serviceCategories';
import CategoryForm from './form';
import { ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PageHeader from '@/components/page-header';

interface EditProps {
    category: {
        id: number;
        name: string;
        slug: string;
        description: string | null;
        icon: string | null;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Service Categories', href: index().url },
    { title: 'Edit', href: '#' },
];

export default function Edit({ category }: EditProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Service Category" />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={<>Editing: <span className="text-primary">{category.name}</span></>}
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Categories
                            </Link>
                        </Button>
                    }
                />

                <div className="rounded-xl border bg-card p-5 text-card-foreground shadow-sm">
                    <CategoryForm category={category} />
                </div>
            </div>
        </AppLayout>
    );
}
