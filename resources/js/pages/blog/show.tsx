import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from "@/actions/App/Http/Controllers/BlogController";
import { CornerUpLeft } from 'lucide-react';

interface BlogComment {
    id: number;
    name: string;
    email: string | null;
    comment: string;
    approved: boolean;
    created_at: string;
}

interface BlogPost {
    id: number;
    title: string;
    slug: string;
    content: string;
    featured_image: string | null;
    status: string;
    author_id: number;
    created_at: string;
    comments: BlogComment[];
}

interface BlogShowProps {
    post: BlogPost;
}

export default function BlogShow({ post }: BlogShowProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Blog', href: index().url },
        { title: post.title, href: '#' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={post.title} />
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
                        <div className="mb-4">
                            <span className={`inline-block px-2 py-1 text-xs font-semibold rounded-full ${
                                post.status === 'published' ? 'bg-green-100 text-green-800' :
                                post.status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-gray-100 text-gray-800'
                            }`}>
                                {post.status}
                            </span>
                        </div>

                        <h1 className="text-3xl font-bold text-gray-900 mb-2">{post.title}</h1>
                        <p className="text-sm text-gray-500 mb-6">
                            Slug: {post.slug} &middot; Created: {new Date(post.created_at).toLocaleDateString()}
                        </p>

                        {post.featured_image && (
                            <div className="mb-6">
                                <img
                                    src={post.featured_image}
                                    alt={post.title}
                                    className="w-full max-h-96 object-cover rounded-lg"
                                />
                            </div>
                        )}

                        <div className="prose max-w-none mb-8">
                            <div dangerouslySetInnerHTML={{ __html: post.content }} />
                        </div>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl">
                        <h2 className="text-xl font-bold text-gray-900 mb-4">
                            Comments ({post.comments?.length || 0})
                        </h2>
                        {post.comments && post.comments.length > 0 ? (
                            <div className="space-y-4">
                                {post.comments.map((comment) => (
                                    <div key={comment.id} className="p-4 border rounded-lg bg-gray-50">
                                        <div className="flex justify-between items-center mb-2">
                                            <span className="font-medium text-gray-900">{comment.name}</span>
                                            <span className="text-xs text-gray-500">
                                                {new Date(comment.created_at).toLocaleDateString()}
                                            </span>
                                        </div>
                                        {comment.email && (
                                            <p className="text-xs text-gray-500 mb-1">{comment.email}</p>
                                        )}
                                        <p className="text-sm text-gray-700">{comment.comment}</p>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-gray-500 text-sm">No comments yet.</p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
