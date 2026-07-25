import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, destroy } from "@/actions/App/Http/Controllers/BlogController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, Eye } from 'lucide-react';
import { show } from "@/actions/App/Http/Controllers/BlogController";

interface BlogPost {
    id: number;
    title: string;
    slug: string;
    content: string;
    status: string;
    created_at: string;
}

interface PostsIndexPageProps {
    posts: BlogPost[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Blog', href: index().url },
];

export default function BlogIndex() {
    const { posts } = usePage().props as PostsIndexPageProps;

    const handleDelete = (post: BlogPost) => {
        if (window.confirm(`Are you sure you want to delete "${post.title}"?`)) {
            router.delete(destroy(post.id).url, { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Blog" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Blog Posts</h2>
                            <p className="text-sm text-gray-500 mt-1">Manage your blog content and articles</p>
                        </div>
                        <Button onClick={() => window.location.href = create().url} className="bg-blue-600 hover:bg-blue-700 text-white">
                            <PlusCircle className="mr-2 h-4 w-4" /> New Post
                        </Button>
                    </div>

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>A list of all blog posts.</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead className="font-bold text-gray-700">Title</TableHead>
                                    <TableHead className="font-bold text-gray-700">Slug</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Status</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Content</TableHead>
                                    <TableHead className="text-center w-[150px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {posts.length > 0 ? (
                                    posts.map((post) => (
                                        <TableRow key={post.id} className="hover:bg-indigo-50/50 transition-colors">
                                            <TableCell className="font-semibold text-gray-800">{post.title}</TableCell>
                                            <TableCell className="text-sm text-gray-500 font-mono">{post.slug}</TableCell>
                                            <TableCell className="text-center">
                                                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                                                    post.status === 'published' ? 'bg-green-100 text-green-800' :
                                                    post.status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
                                                    'bg-gray-100 text-gray-800'
                                                }`}>
                                                    {post.status}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center text-sm text-gray-500 max-w-[200px] truncate">
                                                {post.content?.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().substring(0, 80)}...
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex gap-2 justify-center">
                                                    <Button variant="outline" size="icon" className="h-8 w-8"
                                                        onClick={() => window.location.href = show(post.id).url}>
                                                        <Eye className="h-4 w-4" />
                                                    </Button>
                                                    <Button variant="outline" size="icon" className="h-8 w-8"
                                                        onClick={() => window.location.href = `/admin/blog/${post.id}/edit`}>
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    <Button variant="destructive" size="icon" className="h-8 w-8"
                                                        onClick={() => handleDelete(post)}>
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={5} className="h-24 text-center text-gray-500">
                                            No blog posts found. Create your first post.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
