import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from "@/actions/App/Http/Controllers/ServiceController";
import { ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { badgeClasses } from '@/lib/status';

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

    const facts = [
        { label: 'ID Code', value: <span className="font-mono">{service.id_code}</span> },
        { label: 'Order', value: service.order },
        { label: 'Price', value: priceLabel },
        { label: 'Assessment', value: service.requires_custom_assessment ? 'Required' : 'No' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={service.title} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <div>
                    <Button variant="outline" asChild>
                        <Link href={index().url}>
                            <ArrowLeft className="h-4 w-4" /> Back to Services
                        </Link>
                    </Button>
                </div>

                <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="border-b px-5 py-5">
                        <div className="mb-3 flex flex-wrap items-center gap-2">
                            <span className={badgeClasses('info')}>{service.category}</span>
                            <span className={badgeClasses('neutral')}>{service.price_type}</span>
                        </div>
                        <h1 className="text-2xl font-semibold tracking-tight">{service.title}</h1>
                        {service.tagline && (
                            <p className="mt-1 text-muted-foreground">{service.tagline}</p>
                        )}
                    </div>

                    <dl className="grid grid-cols-2 gap-px border-b bg-border md:grid-cols-4">
                        {facts.map((f) => (
                            <div key={f.label} className="bg-card px-5 py-4">
                                <dt className="text-xs font-medium uppercase tracking-wide text-muted-foreground">{f.label}</dt>
                                <dd className="mt-1 text-sm font-medium">{f.value}</dd>
                            </div>
                        ))}
                    </dl>

                    <div className="space-y-6 p-5">
                        {service.description && (
                            <div>
                                <h2 className="mb-2 font-semibold">Description</h2>
                                <div className="prose max-w-none text-sm text-muted-foreground dark:prose-invert" dangerouslySetInnerHTML={{ __html: service.description }} />
                            </div>
                        )}

                        {service.features && service.features.length > 0 && (
                            <div>
                                <h2 className="mb-2 font-semibold">Features</h2>
                                <ul className="list-inside list-disc space-y-1 text-sm text-muted-foreground">
                                    {service.features.map((feature, i) => (
                                        <li key={i}>{feature}</li>
                                    ))}
                                </ul>
                            </div>
                        )}

                        {service.required_form_fields && service.required_form_fields.length > 0 && (
                            <div>
                                <h2 className="mb-2 font-semibold">Required Form Fields</h2>
                                <div className="flex flex-wrap gap-2">
                                    {service.required_form_fields.map((field, i) => (
                                        <span key={i} className={badgeClasses('neutral')}>
                                            {field}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}

                        {service.submit_button_text && (
                            <p className="text-sm text-muted-foreground">
                                Submit button text: <span className="font-medium text-foreground">{service.submit_button_text}</span>
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
