import React, { useEffect, useMemo, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, update, uploadImage, reset } from '@/actions/App/Http/Controllers/PageContentController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/input-error';
import RichTextEditor from '@/components/rich-text-editor';
import { badgeClasses } from '@/lib/status';
import { ArrowLeft, CheckCircle, ExternalLink, RotateCcw, Save, XCircle } from 'lucide-react';

interface ContentField {
    key: string;
    label: string;
    type: 'text' | 'html' | 'image';
    default: string;
    value: string | null;
}

interface ContentEditProps {
    page: { key: string; label: string };
    fields: ContentField[];
    previewUrl: string | null;
    regionLabel: string;
}

/** UTF-8 safe base64 - values hold Bangla and HTML, which btoa alone would choke on. */
function toBase64(value: string): string {
    const bytes = new TextEncoder().encode(value);
    let binary = '';
    bytes.forEach((byte) => {
        binary += String.fromCharCode(byte);
    });

    return btoa(binary);
}

export default function Edit({ page, fields, previewUrl, regionLabel }: ContentEditProps) {
    const { flash, errors } = usePage().props as any;

    const initial = useMemo(
        () => Object.fromEntries(fields.filter((f) => f.type !== 'image').map((f) => [f.key, f.value ?? ''])),
        [fields],
    );

    const [values, setValues] = useState<Record<string, string>>(initial);
    const [saving, setSaving] = useState(false);

    // The server is the truth after any save, upload or reset.
    useEffect(() => setValues(initial), [initial]);

    const dirtyKeys = Object.keys(values).filter((key) => values[key] !== initial[key]);

    const handleSave = () => {
        if (dirtyKeys.length === 0) return;

        const changed = Object.fromEntries(dirtyKeys.map((key) => [key, values[key]]));

        setSaving(true);
        // Base64 so firewalls don't mistake page content (HTML tags) for an attack.
        router.put(
            update(page.key).url,
            { values_b64: toBase64(JSON.stringify(changed)) },
            { preserveScroll: true, onFinish: () => setSaving(false) },
        );
    };

    const handleUpload = (key: string, file: File) => {
        router.post(uploadImage([page.key, key]).url, { image: file }, { forceFormData: true, preserveScroll: true });
    };

    const handleReset = (key: string) => {
        router.delete(reset(page.key).url, { data: { key }, preserveScroll: true });
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Page content', href: index().url },
        { title: page.label, href: '#' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Page content: ${page.label}`} />
            <div className="flex flex-1 flex-col">
                <div className="sticky top-0 z-10 flex flex-wrap items-center justify-between gap-3 border-b bg-card px-4 py-3 md:px-6">
                    <div className="min-w-0">
                        <h1 className="truncate text-lg font-semibold tracking-tight">{page.label}</h1>
                        <p className="text-xs text-muted-foreground">
                            {regionLabel} site &middot; {fields.length} editable field{fields.length === 1 ? '' : 's'}
                        </p>
                    </div>

                    <div className="flex flex-wrap items-center gap-2">
                        {dirtyKeys.length > 0 && (
                            <span className={badgeClasses('warning')}>
                                {dirtyKeys.length} unsaved change{dirtyKeys.length === 1 ? '' : 's'}
                            </span>
                        )}

                        <Button variant="outline" size="sm" asChild>
                            <a href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back
                            </a>
                        </Button>

                        {previewUrl && (
                            <Button variant="outline" size="sm" asChild>
                                <a href={previewUrl} target="_blank" rel="noreferrer">
                                    View live <ExternalLink className="h-4 w-4" />
                                </a>
                            </Button>
                        )}

                        <Button size="sm" onClick={handleSave} disabled={saving || dirtyKeys.length === 0}>
                            <Save className="h-4 w-4" /> {saving ? 'Saving...' : 'Save'}
                        </Button>
                    </div>
                </div>

                <div className="mx-auto flex w-full max-w-3xl flex-col gap-4 p-4 md:p-6">
                    {flash?.success && (
                        <div className="flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                            <CheckCircle className="h-4 w-4 shrink-0" />
                            <p>{flash.success}</p>
                        </div>
                    )}

                    {flash?.error && (
                        <div className="flex items-center gap-3 rounded-lg border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-medium text-rose-700 dark:text-rose-300">
                            <XCircle className="h-4 w-4 shrink-0" />
                            <p>{flash.error}</p>
                        </div>
                    )}

                    {fields.length === 0 && (
                        <p className="rounded-xl border bg-card p-5 text-sm text-muted-foreground">
                            This page is not yet set up for simple editing.
                        </p>
                    )}

                    {fields.map((field) => (
                        <div key={field.key} className="rounded-xl border bg-card text-card-foreground shadow-sm">
                            <div className="grid content-start gap-2 p-5">
                                <div className="flex flex-wrap items-center justify-between gap-2">
                                    <Label htmlFor={`field-${field.key}`}>{field.label}</Label>
                                    {field.value !== null && (
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="sm"
                                            className="h-7 text-xs text-muted-foreground"
                                            onClick={() => handleReset(field.key)}
                                        >
                                            <RotateCcw className="h-3.5 w-3.5" /> Reset to original
                                        </Button>
                                    )}
                                </div>

                                {field.type === 'image' ? (
                                    <>
                                        {(field.value ?? field.default) && (
                                            <img
                                                src={field.value ?? field.default}
                                                alt={field.label}
                                                className="max-h-48 w-auto rounded-lg border bg-muted object-contain"
                                            />
                                        )}
                                        <Input
                                            id={`field-${field.key}`}
                                            type="file"
                                            accept="image/jpeg,image/png,image/webp,image/gif"
                                            onChange={(e) => {
                                                const file = e.target.files?.[0];
                                                if (file) handleUpload(field.key, file);
                                                e.target.value = '';
                                            }}
                                        />
                                        <InputError message={errors?.image} />
                                    </>
                                ) : field.type === 'html' ? (
                                    <RichTextEditor
                                        value={values[field.key] ?? ''}
                                        onChange={(html) => setValues((prev) => ({ ...prev, [field.key]: html }))}
                                    />
                                ) : field.default.length < 80 ? (
                                    <Input
                                        id={`field-${field.key}`}
                                        value={values[field.key] ?? ''}
                                        placeholder={field.default}
                                        onChange={(e) => setValues((prev) => ({ ...prev, [field.key]: e.target.value }))}
                                    />
                                ) : (
                                    <Textarea
                                        id={`field-${field.key}`}
                                        rows={3}
                                        value={values[field.key] ?? ''}
                                        placeholder={field.default}
                                        onChange={(e) => setValues((prev) => ({ ...prev, [field.key]: e.target.value }))}
                                    />
                                )}

                                {field.type === 'html' && field.default && (
                                    <p className="text-xs text-muted-foreground">
                                        Original wording: {field.default.replace(/<[^>]*>/g, ' ').trim().slice(0, 160)}
                                    </p>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
