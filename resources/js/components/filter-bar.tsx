import React, { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import { Search, X } from 'lucide-react';

export interface FilterOption {
    label: string;
    value: string;
}

interface FilterConfig {
    key: string;
    label: string;
    options: FilterOption[];
}

interface FilterBarProps {
    filters: Record<string, string>;
    filterConfigs: FilterConfig[];
    placeholder?: string;
    baseUrl: string;
}

export default function FilterBar({ filters, filterConfigs, placeholder = 'Search...', baseUrl }: FilterBarProps) {
    const [search, setSearch] = useState(filters.search || '');

    useEffect(() => {
        const timer = setTimeout(() => {
            const params = new URLSearchParams(filters as Record<string, string>);
            if (search) {
                params.set('search', search);
            } else {
                params.delete('search');
            }
            router.get(`${baseUrl}?${params.toString()}`, {}, { preserveState: true, replace: true });
        }, 400);
        return () => clearTimeout(timer);
    }, [search]);

    const handleFilterChange = (key: string, value: string) => {
        const params = new URLSearchParams(filters as Record<string, string>);
        if (value) {
            params.set(key, value);
        } else {
            params.delete(key);
        }
        router.get(`${baseUrl}?${params.toString()}`, {}, { preserveState: true, replace: true });
    };

    const hasActiveFilters = Object.entries(filters).some(([k, v]) => v && k !== 'page');

    const clearAll = () => {
        setSearch('');
        router.get(baseUrl, {}, { preserveState: true, replace: true });
    };

    return (
        <div className="p-4 border rounded-xl bg-white shadow-sm mb-4">
            <div className="flex flex-wrap items-center gap-3">
                {/* Search */}
                <div className="relative flex-1 min-w-[200px]">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                    <input
                        type="text"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        placeholder={placeholder}
                        className="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                {/* Filter dropdowns */}
                {filterConfigs.map((config) => (
                    <select
                        key={config.key}
                        value={filters[config.key] || ''}
                        onChange={(e) => handleFilterChange(config.key, e.target.value)}
                        className="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">{config.label}</option>
                        {config.options.map((opt) => (
                            <option key={opt.value} value={opt.value}>{opt.label}</option>
                        ))}
                    </select>
                ))}

                {/* Clear button */}
                {hasActiveFilters && (
                    <button
                        onClick={clearAll}
                        className="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors"
                    >
                        <X className="h-3 w-3" />
                        Clear
                    </button>
                )}
            </div>
        </div>
    );
}
