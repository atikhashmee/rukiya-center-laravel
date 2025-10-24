import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Form, Head, useForm } from '@inertiajs/react';
import { create, index, store } from "@/actions/App/Http/Controllers/BlogController";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { Textarea } from "@headlessui/react";
import { Select, SelectContent } from "@/components/ui/select";
import { NativeSelect, NativeSelectOption } from "@/components/ui/native-select";
import InputError from "@/components/input-error";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Blog',
        href: index().url,
    },
    {
        title: 'Create',
        href: create().url,
    },
];


export default function BlogIndex() {

    const {data, setData, post, processing, errors} = useForm({
        title: '',
        content: '',
        featured_image: null,
        status: '',
    });
    return <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Blog Index" />
            <div className="container p-4">
                <div className="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div className="p-3 border rounded-lg bg-gray-100 md:col-span-8">
                        <Form action={store()} method="POST" className="space-y-4 inert:opacity-50 inert:pointer-events-none">
                            {({errors}) => (
                                <>
                                <Label htmlFor="title">Blog Title</Label>
                                <Input type="text" className="bg-white"  id="title" name="title"/>
                                {errors.title && <InputError message={errors.title} />}
                                <Label htmlFor="content">Blog Content</Label>
                                <Textarea className="w-full h-64 p-2 border rounded-md bg-white" id="content" name="content" placeholder="Write your blog content here..."></Textarea>
                                {errors.content && <InputError message={errors.content} />}
                                <Label htmlFor="featured_image">Featured Image</Label>
                                <Input type="file" className="bg-white" id="featured_image" name="featured_image"/>
                                {errors.featured_image && <InputError message={errors.featured_image} />}
                                <Label htmlFor="status">Status</Label>
                                <NativeSelect className="bg-white" name="status">
                                    <NativeSelectOption value="">Select status</NativeSelectOption>
                                    <NativeSelectOption value="draft">Draft</NativeSelectOption>
                                    <NativeSelectOption value="published">Published</NativeSelectOption>
                                    <NativeSelectOption value="archived">Archived</NativeSelectOption>
                                </NativeSelect>
                                {errors.status && <InputError message={errors.status} />}
                                <Button type="submit">Create Blog Post</Button>
                                </>
                            )}
                        </Form>
                    </div>
                    <div className="p-3 border rounded-lg bg-white md:col-span-4">
                    </div>
                </div>
            </div>
    </AppLayout>;
}