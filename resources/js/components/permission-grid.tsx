import React from 'react';
import { Button } from '@/components/ui/button';
import InputError from '@/components/input-error';

export interface PermissionGroup {
    key: string;
    label: string;
    view: string;
    manage: string;
}

const th = 'text-xs font-medium uppercase tracking-wide text-muted-foreground';

export function PermissionGrid({
    groups,
    has,
    toggle,
    onSelectAll,
    onClear,
    error,
    disabled = false,
    note,
    inherited,
}: {
    groups: PermissionGroup[];
    has: (permission: string) => boolean;
    toggle: (group: PermissionGroup, key: 'view' | 'manage', checked: boolean) => void;
    onSelectAll: () => void;
    onClear: () => void;
    error?: string;
    disabled?: boolean;
    note?: React.ReactNode;
    /** Permissions already granted elsewhere (e.g. by the role): shown ticked and locked. */
    inherited?: (permission: string) => boolean;
}) {
    return (
        <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div className="flex flex-wrap items-center justify-between gap-3 border-b px-5 py-4">
                <div>
                    <h2 className="font-semibold">Permissions</h2>
                    <p className="mt-0.5 text-sm text-muted-foreground">
                        View lets the role read a section. Manage also lets it create, edit and delete.
                    </p>
                </div>
                {!disabled && (
                    <div className="flex items-center gap-2">
                        <Button type="button" variant="outline" size="sm" onClick={onSelectAll}>
                            Select all
                        </Button>
                        <Button type="button" variant="ghost" size="sm" onClick={onClear}>
                            Clear
                        </Button>
                    </div>
                )}
            </div>

            {note && <div className="border-b px-5 py-4">{note}</div>}

            <div className="overflow-x-auto">
                <table className="w-full min-w-[420px] text-sm">
                    <thead className="bg-muted/50">
                        <tr>
                            <th className={`px-5 py-2.5 text-left ${th}`}>Section</th>
                            <th className={`w-[100px] px-5 py-2.5 text-center ${th}`}>View</th>
                            <th className={`w-[100px] px-5 py-2.5 text-center ${th}`}>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        {groups.map((group) => (
                            <tr key={group.key} className="border-t transition-colors hover:bg-muted/40">
                                <td className="px-5 py-2.5 font-medium">{group.label}</td>
                                {(['view', 'manage'] as const).map((key) => {
                                    const fromRole = inherited?.(group[key]) ?? false;

                                    return (
                                        <td key={key} className="px-5 py-2.5 text-center">
                                            <input
                                                type="checkbox"
                                                aria-label={`${group.label} ${key}`}
                                                title={fromRole ? 'Granted by the role' : undefined}
                                                className="h-4 w-4 rounded accent-primary disabled:opacity-60"
                                                checked={fromRole || has(group[key])}
                                                disabled={disabled || fromRole}
                                                onChange={(e) => toggle(group, key, e.target.checked)}
                                            />
                                        </td>
                                    );
                                })}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {error && (
                <div className="border-t px-5 py-4">
                    <InputError message={error} />
                </div>
            )}
        </div>
    );
}
