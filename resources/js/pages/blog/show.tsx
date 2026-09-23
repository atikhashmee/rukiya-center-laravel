import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link, router } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from "@/actions/App/Http/Controllers/BlogController";
import { ArrowLeft, Check, Trash2, MessageSquare } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PageHeader from '@/components/page-header';
import { badgeClasses, statusClasses, statusLabel } from '@/lib/status';
import { useCan } from '@/lib/permissions';

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
    const canManage = useCan('blog.manage');
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Blog', href: index().url },
        { title: post.title, href: '#' },
    ];

    const approveComment = (commentId: number) => {
        router.post(`/admin/blog-comments/${commentId}/approve`, {}, { preserveScroll: true });
    };

    const deleteComment = (commentId: number) => {
        if (confirm('Remove this comment?')) {
            router.delete(`/admin/blog-comments/${commentId}`, { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={post.title} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={post.title}
                    description={
                        <span className="flex flex-wrap items-center gap-2">
                            <span className={statusClasses(post.status)}>{statusLabel(post.status)}</span>
                            <span>Slug: <span className="font-mono">{post.slug}</span> &middot; Created: {new Date(post.created_at).toLocaleDateString()}</span>
                        </span>
                    }
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Blog
                            </Link>
                        </Button>
                    }
                />

                <div className="overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm">
                    {post.featured_image && (
                        <img
                            src={post.featured_image}
                            alt={post.title}
                            className="max-h-96 w-full border-b object-cover"
                        />
                    )}
                    <div className="tiptap-content p-5 text-sm md:p-6">
                        <div dangerouslySetInnerHTML={{ __html: post.content }} />
                    </div>
                </div>

                <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="border-b px-5 py-4">
                        <h2 className="font-semibold">
                            Comments ({post.comments?.length || 0})
                        </h2>
                    </div>
                    <div className="p-5">
                        {post.comments && post.comments.length > 0 ? (
                            <div className="space-y-3">
                                {post.comments.map((comment) => (
                                    <div key={comment.id} className={`rounded-lg border p-4 ${comment.approved ? 'bg-muted/40' : 'border-amber-500/30 bg-amber-500/5'}`}>
                                        <div className="mb-2 flex items-center justify-between gap-2">
                                            <div className="flex items-center gap-2">
                                                <span className="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold uppercase text-primary">
                                                    {comment.name.charAt(0)}
                                                </span>
                                                <div>
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-sm font-medium">{comment.name}</span>
                                                        {!comment.approved && (
                                                            <span className={badgeClasses('warning')}>Pending approval</span>
                                                        )}
                                                    </div>
                                                    {comment.email && (
                                                        <p className="text-xs text-muted-foreground">{comment.email}</p>
                                                    )}
                                                </div>
                                            </div>
                                            <span className="text-xs text-muted-foreground">
                                                {new Date(comment.created_at).toLocaleDateString()}
                                            </span>
                                        </div>
                                        <p className="text-sm leading-relaxed">{comment.comment}</p>
                                        {canManage && (
                                            <div className="mt-3 flex gap-2">
                                                {!comment.approved && (
                                                    <Button size="sm" variant="outline" className="h-7 gap-1 text-xs" onClick={() => approveComment(comment.id)}>
                                                        <Check className="h-3 w-3" /> Approve
                                                    </Button>
                                                )}
                                                <Button size="sm" variant="ghost"
                                                    className="h-7 gap-1 text-xs text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                    onClick={() => deleteComment(comment.id)}>
                                                    <Trash2 className="h-3 w-3" /> Remove
                                                </Button>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                <MessageSquare className="h-8 w-8 opacity-40" />
                                <p className="text-sm">No comments yet.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
