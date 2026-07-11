import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from "@/actions/App/Http/Controllers/ServiceController";
import { CornerUpLeft } from 'lucide-react';

interface Service {
    id: number;
    id_code: string;
    category: string;
    title: string;
    tagline: string | null;
    description: string | null;
    icon: string | null;
    card_color: string | null;
    features: string[] | null;
    order: number;
    price_type: string;
    price_value: number | null;
    min_donation: number | null;
    requires_custom_assessment: boolean;
    required_form_fields: string[] | null;
    submit_button_text: string | null;
}

interface ServiceShowProps {
    service: Service;
}

export default function ServiceShow({ service }: ServiceShowProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Services', href: index().url },
        { title: service.title, href: '#' },
    ];

    const priceLabel = service.price_type === 'FIXED'
        ? `£${service.price_value}`
        : service.price_type === 'DONATION'
            ? `Min £${service.min_donation}`
            : service.price_type;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={service.title} />
            <div className="container py-4 pl-4 max-w-4xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <Link
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Services
                        </Link>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl">
                        <div className="flex items-center gap-3 mb-2">
                            <span className={`inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800`}>
                                {service.category}
                            </span>
                            <span className="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                {service.price_type}
                            </span>
                        </div>

                        <h1 className="text-3xl font-bold text-gray-900 mb-1">{service.title}</h1>
                        {service.tagline && (
                            <p className="text-lg text-gray-500 mb-4">{service.tagline}</p>
                        )}

                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                            <div>
                                <span className="font-medium text-gray-500">ID Code</span>
                                <p className="text-gray-900 font-mono">{service.id_code}</p>
                            </div>
                            <div>
                                <span className="font-medium text-gray-500">Order</span>
                                <p className="text-gray-900">{service.order}</p>
                            </div>
                            <div>
                                <span className="font-medium text-gray-500">Price</span>
                                <p className="text-gray-900">{priceLabel}</p>
                            </div>
                            <div>
                                <span className="font-medium text-gray-500">Assessment</span>
                                <p className="text-gray-900">{service.requires_custom_assessment ? 'Required' : 'No'}</p>
                            </div>
                        </div>

                        {service.description && (
                            <div className="mb-6">
                                <h2 className="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                                <div className="prose max-w-none text-gray-700" dangerouslySetInnerHTML={{ __html: service.description }} />
                            </div>
                        )}

                        {service.features && service.features.length > 0 && (
                            <div className="mb-6">
                                <h2 className="text-lg font-semibold text-gray-900 mb-2">Features</h2>
                                <ul className="list-disc list-inside space-y-1 text-gray-700">
                                    {service.features.map((feature, i) => (
                                        <li key={i}>{feature}</li>
                                    ))}
                                </ul>
                            </div>
                        )}

                        {service.required_form_fields && service.required_form_fields.length > 0 && (
                            <div className="mb-6">
                                <h2 className="text-lg font-semibold text-gray-900 mb-2">Required Form Fields</h2>
                                <div className="flex flex-wrap gap-2">
                                    {service.required_form_fields.map((field, i) => (
                                        <span key={i} className="inline-block px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                            {field}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}

                        {service.submit_button_text && (
                            <p className="text-sm text-gray-500 mt-4">
                                Submit button text: <span className="font-medium text-gray-700">{service.submit_button_text}</span>
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
