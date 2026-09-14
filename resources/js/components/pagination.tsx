import React from 'react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginationProps {
    links: PaginationLink[];
}

const Pagination: React.FC<PaginationProps> = ({ links }) => {
    if (links.length <= 3) {
        return null;
    }

    return (
        <nav className="flex flex-wrap items-center justify-center gap-1 px-4 py-4">
            {links.map((link, index) => {
                const isDisabled = link.url === null;

                return (
                    <Link
                        key={index}
                        href={link.url || '#'}
                        disabled={isDisabled}
                        className={cn(
                            'inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm font-medium transition-colors',
                            link.active
                                ? 'bg-primary text-primary-foreground shadow-sm'
                                : isDisabled
                                    ? 'cursor-default text-muted-foreground/50'
                                    : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                        )}
                        preserveScroll
                        preserveState
                        // Laravel labels contain &laquo; / &raquo; entities
                        dangerouslySetInnerHTML={{ __html: link.label }}
                    />
                );
            })}
        </nav>
    );
};

export default Pagination;
