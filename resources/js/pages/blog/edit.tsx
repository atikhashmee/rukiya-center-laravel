import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/BlogController";
import { ArrowLeft } from 'lucide-react';
import RichTextEditor from '@/components/rich-text-editor';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';

interface BlogPost {
    id: number;
    title: string;
    slug: string;
    content: string;
    featured_image: string | null;
    status: string;
}

interface BlogEditProps {
    post: BlogPost;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Blog', href: index().url },
    { title: 'Edit', href: '#' },
];

export default function BlogEdit({ post }: BlogEditProps) {
    const { data, setData, processing, errors, put } = useForm({
        title: post.title,
        content: post.content,
        featured_image: post.featured_image || '',
        status: post.status,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(update(post.id).url, { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit: ${post.title}`} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={<><span className="text-muted-foreground">Editing:</span> {post.title}</>}
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Blog
                            </Link>
                        </Button>
                    }
                />

                <div className="rounded-xl border bg-card p-5 text-card-foreground shadow-sm md:p-6">
                    <form onSubmit={handleSubmit} className="grid gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="title">Title</Label>
                            <Input
                                id="title"
                                type="text"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                            />
                            <InputError message={errors.title} />
                        </div>

                        <div className="grid gap-2">
                            <Label>Content</Label>
                            <RichTextEditor value={data.content} onChange={(html) => setData('content', html)} />
                            <InputError message={errors.content} />
                        </div>

                        <div className="grid gap-5 sm:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="featured_image">Featured Image URL</Label>
                                <Input
                                    id="featured_image"
                                    type="text"
                                    value={data.featured_image}
                                    onChange={(e) => setData('featured_image', e.target.value)}
                                />
                                <InputError message={errors.featured_image} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="status">Status</Label>
                                <NativeSelect
                                    id="status"
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value)}
                                    className="w-full"
                                >
                                    <NativeSelectOption value="draft">Draft</NativeSelectOption>
                                    <NativeSelectOption value="published">Published</NativeSelectOption>
                                    <NativeSelectOption value="archived">Archived</NativeSelectOption>
                                </NativeSelect>
                                <InputError message={errors.status} />
                            </div>
                        </div>

                        <div className="flex justify-end gap-2 border-t pt-5">
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Updating...' : 'Update Blog Post'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
