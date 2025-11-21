import React from 'react';
import { Link } from '@inertiajs/react';

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
        <div className="flex flex-wrap items-center justify-center mt-6">
            <nav className="isolate inline-flex -space-x-px rounded-md shadow-sm">
                {links.map((link, index) => {
                    const isDisabled = link.url === null;
                    
                    return (
                        <Link
                            key={index}
                            href={link.url || '#'} // Fallback if url is null
                            disabled={isDisabled}
                            className={`
                                relative inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-150 ease-in-out
                                ${link.active 
                                    ? 'z-10 bg-indigo-600 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'
                                    : isDisabled 
                                        ? 'text-gray-400 bg-gray-50 border border-gray-300 cursor-default'
                                        : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                                }
                                ${index === 0 ? 'rounded-l-md' : ''}
                                ${index === links.length - 1 ? 'rounded-r-md' : ''}
                            `}
                            // This attribute is vital for Inertia navigation
                            preserveScroll 
                            preserveState
                            // Dangerously setting inner HTML to display the &laquo; and &raquo; entities
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    );
                })}
            </nav>
        </div>
    );
};

export default Pagination;