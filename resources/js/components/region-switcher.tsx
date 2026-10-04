import { router, usePage } from '@inertiajs/react';
import { Globe } from 'lucide-react';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';

interface RegionOption {
    value: string;
    label: string;
    short: string;
}

/**
 * Chooses which country's records the admin screens show. "All regions" clears the
 * filter; picking one narrows every list and tags anything newly created with it.
 */
export function RegionSwitcher() {
    const { region } = usePage().props as unknown as {
        region?: { current: string; options: RegionOption[] };
    };

    if (!region || region.options.length < 2) {
        return null;
    }

    const change = (value: string) => {
        router.post('/admin/region', { region: value }, { preserveScroll: true });
    };

    return (
        <label className="flex items-center gap-2" title="Which country's records you are working on">
            <Globe className="h-4 w-4 text-muted-foreground" />
            <span className="sr-only">Region</span>
            <NativeSelect
                className="h-8 py-1 text-xs"
                value={region.current}
                onChange={(e) => change(e.target.value)}
            >
                <NativeSelectOption value="all">All regions</NativeSelectOption>
                {region.options.map((option) => (
                    <NativeSelectOption key={option.value} value={option.value}>
                        {option.label}
                    </NativeSelectOption>
                ))}
            </NativeSelect>
        </label>
    );
}
