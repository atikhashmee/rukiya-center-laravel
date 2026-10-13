import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, edit, update, uploadImage } from '@/actions/App/Http/Controllers/PageContentController';
import { Button } from '@/components/ui/button';
import { badgeClasses } from '@/lib/status';
import { ArrowLeft, ExternalLink, ListTree, MousePointerClick, RotateCcw, Save } from 'lucide-react';

interface ContentField {
    key: string;
    label: string;
    type: 'text' | 'html' | 'image';
    default: string;
    value: string | null;
}

interface VisualEditorProps {
    page: { key: string; label: string };
    previewUrl: string | null;
    fields: ContentField[];
    regionLabel: string;
}

/** UTF-8 safe base64 — the sites carry Bangla text, and firewalls reject raw HTML bodies. */
const toBase64 = (value: string) => {
    const bytes = new TextEncoder().encode(value);
    let binary = '';
    bytes.forEach((byte) => {
        binary += String.fromCharCode(byte);
    });

    return btoa(binary);
};

export default function VisualEditor({ page, previewUrl, fields, regionLabel }: VisualEditorProps) {
    const frame = useRef<HTMLIFrameElement>(null);
    const [pending, setPending] = useState<Record<string, string>>({});
    const [frameKey, setFrameKey] = useState(0);
    const [saving, setSaving] = useState(false);
    const [showFields, setShowFields] = useState(false);
    const [uploadingFor, setUploadingFor] = useState<string | null>(null);
    const filePicker = useRef<HTMLInputElement>(null);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Page content', href: index().url },
        { title: page.label, href: '#' },
    ];

    const pendingCount = Object.keys(pending).length;

    /** Messages come from the page inside the frame (see public/js/content-editor.js). */
    useEffect(() => {
        const onMessage = (event: MessageEvent) => {
            const data = event.data || {};

            if (data.source !== 'content-editor' || event.source !== frame.current?.contentWindow) {
                return;
            }

            if (data.type === 'change' && data.page === page.key) {
                setPending((previous) => ({ ...previous, [data.key]: data.value }));
            }

            if (data.type === 'image' && data.page === page.key) {
                setUploadingFor(data.key);
                filePicker.current?.click();
            }
        };

        window.addEventListener('message', onMessage);

        return () => window.removeEventListener('message', onMessage);
    }, [page.key]);

    const reloadFrame = useCallback(() => setFrameKey((value) => value + 1), []);

    const save = () => {
        if (pendingCount === 0) return;

        setSaving(true);
        router.put(
            update(page.key).url,
            { values_b64: toBase64(JSON.stringify(pending)) },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setPending({});
                    reloadFrame();
                },
                onFinish: () => setSaving(false),
            },
        );
    };

    const discard = () => {
        setPending({});
        reloadFrame();
    };

    const onImageChosen = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];
        event.target.value = '';

        if (!file || !uploadingFor) return;

        router.post(
            uploadImage({ page: page.key, key: uploadingFor }).url,
            { image: file },
            {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: reloadFrame,
                onFinish: () => setUploadingFor(null),
            },
        );
    };

    const focusField = (key: string) => {
        frame.current?.contentWindow?.postMessage({ source: 'content-admin', type: 'focus', key }, '*');
    };

    const editableFields = useMemo(() => fields.filter((field) => field.type !== 'image'), [fields]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${page.label}`} />

            <input ref={filePicker} type="file" accept="image/*" className="hidden" onChange={onImageChosen} />

            <div className="flex h-[calc(100vh-4rem)] flex-col">
                <div className="flex flex-wrap items-center justify-between gap-3 border-b bg-card px-4 py-3">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="sm" asChild>
                            <Link href={index().url}><ArrowLeft className="h-4 w-4" /> Back</Link>
                        </Button>
                        <div>
                            <h1 className="font-semibold leading-tight">{page.label}</h1>
                            <p className="text-xs text-muted-foreground">{regionLabel} site · double-click any highlighted text</p>
                        </div>
                        {pendingCount > 0 && (
                            <span className={badgeClasses('warning')}>{pendingCount} unsaved</span>
                        )}
                    </div>

                    <div className="flex flex-wrap items-center gap-2">
                        <Button variant="ghost" size="sm" onClick={() => setShowFields((value) => !value)}>
                            <ListTree className="h-4 w-4" /> Fields
                        </Button>
                        {pendingCount > 0 && (
                            <Button variant="ghost" size="sm" onClick={discard}>
                                <RotateCcw className="h-4 w-4" /> Discard
                            </Button>
                        )}
                        <Button size="sm" onClick={save} disabled={pendingCount === 0 || saving}>
                            <Save className="h-4 w-4" /> {saving ? 'Saving…' : 'Save changes'}
                        </Button>
                    </div>
                </div>

                {previewUrl ? (
                    <div className="flex min-h-0 flex-1">
                        {showFields && (
                            <aside className="w-64 shrink-0 overflow-y-auto border-r bg-card p-3">
                                <p className="px-1 pb-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                    Editable text
                                </p>
                                <ul className="space-y-1">
                                    {editableFields.map((field) => (
                                        <li key={field.key}>
                                            <button
                                                type="button"
                                                onClick={() => focusField(field.key)}
                                                className="w-full rounded-md px-2 py-1.5 text-left text-sm hover:bg-muted"
                                            >
                                                <span className="block truncate font-medium">{field.label}</span>
                                                <span className="block truncate text-xs text-muted-foreground">
                                                    {pending[field.key] ?? field.value ?? field.default}
                                                </span>
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </aside>
                        )}

                        <iframe
                            key={frameKey}
                            ref={frame}
                            src={previewUrl}
                            title={`${page.label} preview`}
                            className="min-h-0 flex-1 border-0 bg-white"
                        />
                    </div>
                ) : (
                    <div className="flex flex-1 items-center justify-center p-10">
                        <div className="max-w-md space-y-3 text-center">
                            <MousePointerClick className="mx-auto h-8 w-8 text-muted-foreground opacity-50" />
                            <h2 className="font-semibold">This page can't be previewed on its own</h2>
                            <p className="text-sm text-muted-foreground">
                                It needs a record to display (a product or a booking, for example), so it has no fixed
                                address to open. You can still edit its wording in the form view.
                            </p>
                            <Button asChild>
                                <Link href={edit(page.key).url}>Open form view</Link>
                            </Button>
                        </div>
                    </div>
                )}
            </div>

            {previewUrl && (
                <a
                    href={previewUrl.split('?')[0]}
                    target="_blank"
                    rel="noopener"
                    className="sr-only"
                >
                    Open live page <ExternalLink className="h-3 w-3" />
                </a>
            )}
        </AppLayout>
    );
}
