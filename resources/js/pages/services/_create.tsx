import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, router } from '@inertiajs/react';
import InputError from "@/components/input-error";
import { BreadcrumbItem } from '@/types';
import { ServiceType } from '@/types/service';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Textarea } from "@/components/ui/textarea"
import { dashboard } from '@/routes';
import { store, index, create } from "@/actions/App/Http/Controllers/ServiceController";
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

// It's a good practice to define the props and data types
interface CreateServiceProps  {
    serviceTypes: { name: string; value: string }[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Services',
        href: index().url,
    },
    {
        title: 'Create',
        href: create().url,
    },
];

export default function Create({ serviceTypes }: CreateServiceProps) {
    const { data, setData, post, processing, errors, reset } = useForm({
        service_name: '',
        service_type: serviceTypes[0]?.value || '',
        price: '',
        start_date_and_time: '',
        end_date_and_time: '',
        description: '',
        duration: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(store().url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Service" />
            <div className="container p-4">
                <div className="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div className="p-3 border rounded-lg bg-[var(--background)] md:col-span-8">
                        <form onSubmit={submit} method='POST'>
                        <div >
                            <Label htmlFor="service_name"> Service Name </Label>
                            <Input  type="text" value={data.service_name} autoComplete="service_name"
                                onChange={(e) => setData('service_name', e.target.value)}
                            />
                            {errors.service_name && <InputError message={errors.service_name} className="mt-2" />}
                        </div>

                        <div>
                            <Label htmlFor="service_type"> Service Type </Label>
                            <Select value={data.service_type} onValueChange={(value) => setData('service_type', value as ServiceType)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a service type" />
                                </SelectTrigger>
                                <SelectContent>
                                    {serviceTypes.map((type, index) => (
                                        <SelectItem key={index} value={type.toString()}>{type.toString()}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.service_type && <InputError message={errors.service_type} className="mt-2" />}
                        </div>
                        
                        <div>
                            <Label htmlFor="price"> Price </Label>
                            <Input
                                type="number"
                                value={data.price}
                                step="0.01"
                                onChange={(e) => setData('price', e.target.value)}
                            />
                            {errors.price && <InputError message={errors.price} className="mt-2" />}
                        </div>

                        <div>
                            <Label htmlFor="start_date_and_time"> Start Date and Time </Label>
                            <Input
                                type="datetime-local"
                                value={data.start_date_and_time}
                                onChange={(e) => setData('start_date_and_time', e.target.value)}
                            />
                            {errors.start_date_and_time && <InputError message={errors.start_date_and_time} className="mt-2" />}
                        </div>
                        
                        <div>
                            <Label htmlFor="end_date_and_time"> End Date and Time </Label>
                            <Input
                                type="datetime-local"
                                value={data.end_date_and_time}
                                onChange={(e) => setData('end_date_and_time', e.target.value)}
                            />
                            {errors.end_date_and_time && <InputError message={errors.end_date_and_time} className="mt-2" />}
                        </div>

                        <div>
                            <Label htmlFor="duration"> Duration (in minutes, optional) </Label>
                            <Input
                                id="duration"
                                type="number"
                                name="duration"
                                value={data.duration}
                                min="0"
                                onChange={(e) => setData('duration', e.target.value)}
                            />
                            {errors.duration && <InputError message={errors.duration} className="mt-2" />}
                        </div>

                        <div>
                            <Label htmlFor="description"> Description (optional) </Label>
                            <Textarea
                                id="description"
                                name="description"
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                            />
                            {errors.description && <InputError message={errors.description} className="mt-2" />}
                        </div>

                        <div className="flex items-center justify-end mt-6">
                            <Button disabled={processing}>
                                Create Service
                            </Button>
                        </div>
                    </form>
                    </div>
                    <div className="p-3 border rounded-lg bg-[var(--background)] md:col-span-4"></div>
                </div>
                
            </div>
        </AppLayout>
    );
}
