import { Head } from '@inertiajs/react';
import { Service } from '@/types/service'; // Assuming you have a types file
import AppLayout from '@/layouts/app-layout';
import { create, index } from "@/actions/App/Http/Controllers/ServiceController";
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { Button } from '@/components/ui/button';
import { Table, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Services',
        href: index().url,
    },
];

export default function Index({ services }: any) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Services" />
            <div className="container p-4 bg-[var(--background)]">
                <div className="flex flex-col justify-between gap-3 w-full ">
                    <div className="grid grid-cols-1 md:grid-cols-12 gap-x-1">
                        <div className="md:col-start-11 md:col-span-2 justify-self-end">
                            <Button className='text-end' onClick={() => window.location.href = create().url}>Create new Service</Button>
                        </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-1 w-full border rounded-lg">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Sl</TableHead>
                                    <TableHead>Service Name</TableHead>
                                    <TableHead>Service Category</TableHead>
                                    <TableHead>Start Date</TableHead>
                                    <TableHead>End Date</TableHead>
                                    <TableHead>Price</TableHead>
                                    <TableHead>No. Registraion</TableHead>
                                    <TableHead>Action</TableHead>
                                </TableRow>
                                {services.length > 0 && services.map((service: Service) => (
                                    <TableRow key={service.id}>
                                        <TableCell>{service.id}</TableCell>
                                        <TableCell>{service.service_name}</TableCell>
                                        <TableCell>{service.service_type}</TableCell>
                                        <TableCell>{service.start_date_and_time}</TableCell>
                                        <TableCell>{service.end_date_and_time}</TableCell>
                                        <TableCell>{service.price}</TableCell>
                                        <TableCell>0</TableCell>
                                        <TableCell>
                                             <Button> Edit</Button>
                                            <Button className="bg-red-900"> Delete</Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableHeader>
                        </Table>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
