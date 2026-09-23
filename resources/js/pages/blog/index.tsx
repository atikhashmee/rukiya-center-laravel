import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, destroy } from "@/actions/App/Http/Controllers/BlogController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, Eye, FileText } from 'lucide-react';
import { show } from "@/actions/App/Http/Controllers/BlogController";
import PageHeader from '@/components/page-header';
import { statusClasses, statusLabel } from '@/lib/status';
import { useCan } from '@/lib/permissions';

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

const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

export default function BlogIndex() {
    const { posts } = usePage().props as PostsIndexPageProps;
    const canManage = useCan('blog.manage');

    const handleDelete = (post: BlogPost) => {
        if (window.confirm(`Are you sure you want to delete "${post.title}"?`)) {
            router.delete(destroy(post.id).url, { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Blog" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Blog Posts"
                    description="Manage your blog content and articles"
                    actions={canManage && (
                        <Button onClick={() => window.location.href = create().url}>
                            <Plus className="h-4 w-4" /> New Post
                        </Button>
                    )}
                />

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={th}>Title</TableHead>
                                    <TableHead className={th}>Slug</TableHead>
                                    <TableHead className={`text-center ${th}`}>Status</TableHead>
                                    <TableHead className={th}>Content</TableHead>
                                    <TableHead className={`w-[150px] text-right ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {posts.length > 0 ? (
                                    posts.map((post) => (
                                        <TableRow key={post.id} className="hover:bg-muted/40">
                                            <TableCell className="font-medium">{post.title}</TableCell>
                                            <TableCell className="font-mono text-xs text-muted-foreground">{post.slug}</TableCell>
                                            <TableCell className="text-center">
                                                <span className={statusClasses(post.status)}>
                                                    {statusLabel(post.status)}
                                                </span>
                                            </TableCell>
                                            <TableCell className="max-w-[240px] truncate text-xs text-muted-foreground">
                                                {post.content?.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().substring(0, 80)}...
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex justify-end gap-1">
                                                    <Button variant="ghost" size="icon" className="h-8 w-8" title="View"
                                                        onClick={() => window.location.href = show(post.id).url}>
                                                        <Eye className="h-4 w-4" />
                                                    </Button>
                                                    {canManage && (
                                                        <>
                                                            <Button variant="ghost" size="icon" className="h-8 w-8" title="Edit"
                                                                onClick={() => window.location.href = `/admin/blog/${post.id}/edit`}>
                                                                <Pencil className="h-4 w-4" />
                                                            </Button>
                                                            <Button variant="ghost" size="icon" title="Delete"
                                                                className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                onClick={() => handleDelete(post)}>
                                                                <Trash2 className="h-4 w-4" />
                                                            </Button>
                                                        </>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={5}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <FileText className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No blog posts found. Create your first post.</p>
                                            </div>
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
