import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from '@/routes/serviceCategories';
import CategoryForm from './form';
import { CornerUpLeft } from 'lucide-react';

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
            <div className="container py-4 pl-4 max-w-4xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <Link
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Categories
                        </Link>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl">
                        <h2 className="text-2xl font-bold mb-6 text-indigo-700">
                            Editing: <span className="text-gray-900">{category.name}</span>
                        </h2>
                        <CategoryForm category={category} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
