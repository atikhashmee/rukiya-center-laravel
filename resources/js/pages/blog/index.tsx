import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem, Post } from "@/types";
import { dashboard } from '@/routes';
import {  Head,  usePage } from '@inertiajs/react';
import { create, index, store } from "@/actions/App/Http/Controllers/BlogController";
import { Button } from "@/components/ui/button";
import { Table, TableCaption, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/components/ui/table";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Blog',
        href: index().url,
    },
];


interface PostsIndexPageProps {
    posts: Post[]
}


export default function BlogIndex() {
    
    const {posts } = usePage().props as PostsIndexPageProps
    return <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Blog Index" />
            <div className="container p-4">
                <div className="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <Button className="md:col-span-12 mb-4" onClick={() => window.location.href = create().url}>Create New Post</Button>
                    <div className="p-3 border rounded-lg bg-gray-100 md:col-span-12">
                        <Table>
                            <TableCaption>A list of your recent Blogs.</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="w-[100px]">SL. NO.</TableHead>
                                    <TableHead>Title</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Content</TableHead>
                                    <TableHead>Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                { posts.length > 0 && posts.map(post => (
                                    <TableRow key={post.title}>
                                        <TableCell className="font-medium">1</TableCell>
                                        <TableCell>{post.title}</TableCell>
                                        <TableCell>{post.status}</TableCell>
                                        <TableCell>{post.content}</TableCell>
                                        <TableCell className="text-right flex flex-row gap-3">
                                            <Button> Edit</Button>
                                            <Button className="bg-red-900"> Delete</Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </div>
    </AppLayout>;
}