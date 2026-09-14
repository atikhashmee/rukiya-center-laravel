import React, { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import { Search, X } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';

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
        <div className="rounded-xl border bg-card p-3 shadow-sm">
            <div className="flex flex-wrap items-center gap-3">
                <div className="relative flex-1 min-w-[200px]">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        type="text"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        placeholder={placeholder}
                        className="pl-9"
                    />
                </div>

                {filterConfigs.map((config) => (
                    <NativeSelect
                        key={config.key}
                        value={filters[config.key] || ''}
                        onChange={(e) => handleFilterChange(config.key, e.target.value)}
                    >
                        <NativeSelectOption value="">{config.label}</NativeSelectOption>
                        {config.options.map((opt) => (
                            <NativeSelectOption key={opt.value} value={opt.value}>{opt.label}</NativeSelectOption>
                        ))}
                    </NativeSelect>
                ))}

                {hasActiveFilters && (
                    <Button variant="ghost" onClick={clearAll} className="text-destructive hover:bg-destructive/10 hover:text-destructive">
                        <X className="h-4 w-4" />
                        Clear
                    </Button>
                )}
            </div>
        </div>
    );
}
