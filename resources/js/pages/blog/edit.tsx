import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/BlogController";
import { CornerUpLeft } from 'lucide-react';

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

    const INPUT_CLASSES = "mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2";

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(update(post.id).url, { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit: ${post.title}`} />
            <div className="container py-4 pl-4 max-w-4xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <Link
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Blog
                        </Link>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl">
                        <h2 className="text-2xl font-bold mb-6 text-indigo-700">
                            Editing: <span className="text-gray-900">{post.title}</span>
                        </h2>

                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div>
                                <label htmlFor="title" className="block text-sm font-medium text-gray-700">Title</label>
                                <input
                                    id="title"
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    className={INPUT_CLASSES}
                                />
                                {errors.title && <p className="mt-1 text-xs text-red-500">{errors.title}</p>}
                            </div>

                            <div>
                                <label htmlFor="content" className="block text-sm font-medium text-gray-700">Content</label>
                                <textarea
                                    id="content"
                                    rows={12}
                                    value={data.content}
                                    onChange={(e) => setData('content', e.target.value)}
                                    className={INPUT_CLASSES}
                                    placeholder="Write your blog content here..."
                                />
                                {errors.content && <p className="mt-1 text-xs text-red-500">{errors.content}</p>}
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="featured_image" className="block text-sm font-medium text-gray-700">Featured Image URL</label>
                                    <input
                                        id="featured_image"
                                        type="text"
                                        value={data.featured_image}
                                        onChange={(e) => setData('featured_image', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.featured_image && <p className="mt-1 text-xs text-red-500">{errors.featured_image}</p>}
                                </div>

                                <div>
                                    <label htmlFor="status" className="block text-sm font-medium text-gray-700">Status</label>
                                    <select
                                        id="status"
                                        value={data.status}
                                        onChange={(e) => setData('status', e.target.value)}
                                        className={INPUT_CLASSES}
                                    >
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    {errors.status && <p className="mt-1 text-xs text-red-500">{errors.status}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end pt-4 border-t border-gray-200">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm disabled:opacity-50"
                                >
                                    {processing ? 'Updating...' : 'Update Blog Post'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
