import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, visual } from '@/actions/App/Http/Controllers/PageContentController';
import { ChevronRight, FileText } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';

interface ContentPage {
    key: string;
    label: string;
    fields: number;
    edited: number;
    group: string;
}

interface ContentIndexProps {
    pages: ContentPage[];
    regionLabel: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Page content', href: index().url },
];

export default function Index({ pages, regionLabel }: ContentIndexProps) {
    // Keep the order the server sent, grouped by heading.
    const groups = pages.reduce<Record<string, ContentPage[]>>((acc, page) => {
        (acc[page.group] ??= []).push(page);
        return acc;
    }, {});

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Page content" />
            <div className="mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Page content"
                    description={`You are editing the ${regionLabel} site - switch region in the header to edit another one.`}
                />

                {Object.entries(groups).map(([group, groupPages]) => (
                    <div key={group} className="grid content-start gap-2">
                        <h2 className="text-xs font-medium uppercase tracking-wide text-muted-foreground">{group}</h2>

                        <div className="divide-y overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm">
                            {groupPages.map((page) =>
                                page.fields === 0 ? (
                                    <div key={page.key} className="flex items-center gap-3 px-4 py-3 opacity-60">
                                        <FileText className="h-4 w-4 shrink-0 text-muted-foreground" />
                                        <div className="min-w-0">
                                            <p className="truncate text-sm font-medium text-muted-foreground">{page.label}</p>
                                            <p className="text-xs text-muted-foreground">Not yet set up for simple editing</p>
                                        </div>
                                    </div>
                                ) : (
                                    <div key={page.key} className="flex items-center gap-3 px-4 py-3 transition-colors hover:bg-muted/50">
                                        <FileText className="h-4 w-4 shrink-0 text-muted-foreground" />
                                        {/* Primary way in is the visual editor: the real page, edited in place. */}
                                        <a href={visual(page.key).url} className="min-w-0 flex-1">
                                            <p className="truncate text-sm font-medium">{page.label}</p>
                                            <p className="text-xs text-muted-foreground">
                                                {page.fields} editable field{page.fields === 1 ? '' : 's'}
                                            </p>
                                        </a>
                                        {page.edited > 0 && <span className={badgeClasses('info')}>{page.edited} edited</span>}
                                        <a href={visual(page.key).url} className="shrink-0" aria-label={`Edit ${page.label} visually`}>
                                            <ChevronRight className="h-4 w-4 text-muted-foreground" />
                                        </a>
                                    </div>
                                ),
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </AppLayout>
    );
}
