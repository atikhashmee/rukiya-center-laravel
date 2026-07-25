import React, { useState, useRef, useCallback, useEffect, useMemo } from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, usePage } from '@inertiajs/react';
import { BreadcrumbItem, Theme } from "@/types";
import { dashboard } from '@/routes';
import { index } from "@/actions/App/Http/Controllers/ThemeController";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from "@/components/ui/collapsible";
import { Save, Palette, RefreshCw, Search, ChevronDown, ChevronRight, ExternalLink, CheckCircle2, AlertCircle, CornerUpLeft, Circle } from 'lucide-react';
import Editor from '@monaco-editor/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Themes', href: index().url },
    { title: 'Edit', href: '#' },
];

interface ThemeEditPageProps {
    theme: Theme;
    fileKeys: Record<string, string>;
    fileLabels: Record<string, string>;
    pageGroups: Record<string, string[]>;
    previewUrls: Record<string, string>;
}

function getXsrfToken(): string {
    return decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || '');
}

export default function ThemeEdit() {
    const { theme, fileKeys, fileLabels, pageGroups, previewUrls } = usePage().props as unknown as ThemeEditPageProps;

    const allKeys = useMemo(() => Object.keys(fileKeys), [fileKeys]);
    const [activeKey, setActiveKey] = useState<string>(allKeys[0] || '');
    const [fileContents, setFileContents] = useState<Record<string, string>>({});
    const [savedContents, setSavedContents] = useState<Record<string, string>>({});
    const [loading, setLoading] = useState<Record<string, boolean>>({});
    const [saving, setSaving] = useState(false);
    const [saveMessage, setSaveMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
    const [search, setSearch] = useState('');
    const [openGroups, setOpenGroups] = useState<Record<string, boolean>>(
        () => Object.fromEntries(Object.keys(pageGroups).map((g) => [g, true]))
    );
    const editorRef = useRef<any>(null);

    const fetchFileContent = useCallback(async (key: string) => {
        if (fileContents[key] !== undefined) return;

        setLoading((prev) => ({ ...prev, [key]: true }));
        try {
            const response = await fetch(`/admin/themes/${theme.id}/file/${key}`);
            const data = await response.json();
            const content = data.content || '';
            setFileContents((prev) => ({ ...prev, [key]: content }));
            setSavedContents((prev) => ({ ...prev, [key]: content }));
        } catch (error) {
            console.error(`Failed to fetch file: ${key}`, error);
            setFileContents((prev) => ({ ...prev, [key]: '' }));
        } finally {
            setLoading((prev) => ({ ...prev, [key]: false }));
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [theme.id]);

    useEffect(() => {
        if (activeKey) fetchFileContent(activeKey);
    }, [activeKey, fetchFileContent]);

    const handleSelect = (key: string) => {
        setActiveKey(key);
        setSaveMessage(null);
    };

    const handleEditorChange = (value: string | undefined) => {
        if (value !== undefined && activeKey) {
            setFileContents((prev) => ({ ...prev, [activeKey]: value }));
        }
    };

    const isDirty = (key: string) => fileContents[key] !== undefined && fileContents[key] !== savedContents[key];
    const activeDirty = isDirty(activeKey);

    const handleSave = useCallback(async () => {
        if (!activeKey) return;

        setSaving(true);
        setSaveMessage(null);
        try {
            const response = await fetch(`/admin/themes/${theme.id}/file/${activeKey}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': getXsrfToken(),
                },
                body: JSON.stringify({ content: fileContents[activeKey] || '' }),
            });

            if (response.ok) {
                setSavedContents((prev) => ({ ...prev, [activeKey]: fileContents[activeKey] || '' }));
                setSaveMessage({ type: 'success', text: 'Saved successfully.' });
            } else {
                setSaveMessage({ type: 'error', text: 'Failed to save - please try again.' });
            }
        } catch (error) {
            console.error('Failed to save file:', error);
            setSaveMessage({ type: 'error', text: 'Failed to save - check your connection.' });
        } finally {
            setSaving(false);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [activeKey, fileContents, theme.id]);

    useEffect(() => {
        if (!saveMessage) return;
        const t = setTimeout(() => setSaveMessage(null), 3000);
        return () => clearTimeout(t);
    }, [saveMessage]);

    const handleKeyDown = (e: React.KeyboardEvent) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 's') {
            e.preventDefault();
            handleSave();
        }
    };

    const toggleGroup = (group: string) => {
        setOpenGroups((prev) => ({ ...prev, [group]: !prev[group] }));
    };

    const matchesSearch = (key: string) => {
        if (!search.trim()) return true;
        const q = search.trim().toLowerCase();
        return key.toLowerCase().includes(q) || (fileLabels[key] || '').toLowerCase().includes(q);
    };

    const dirtyCount = allKeys.filter((k) => isDirty(k)).length;
    const previewUrl = previewUrls[activeKey];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Theme: ${theme.name}`} />
            <div className="flex flex-col h-[calc(100vh-4rem)]" onKeyDown={handleKeyDown}>
                {/* Top bar */}
                <div className="border-b bg-background px-4 py-2.5 flex items-center justify-between gap-4">
                    <div className="flex items-center gap-3 min-w-0">
                        <a href={index().url} className="text-muted-foreground hover:text-foreground transition-colors flex-shrink-0" title="Back to Themes">
                            <CornerUpLeft className="h-4 w-4" />
                        </a>
                        <Palette className="h-5 w-5 text-muted-foreground flex-shrink-0" />
                        <div className="min-w-0">
                            <div className="flex items-center gap-2">
                                <h1 className="text-base font-semibold truncate">{theme.name}</h1>
                                <Badge variant={theme.is_active ? 'default' : 'secondary'} className="text-[10px]">
                                    {theme.is_active ? 'Active' : 'Inactive'}
                                </Badge>
                                {dirtyCount > 0 && (
                                    <Badge variant="outline" className="text-[10px] text-amber-600 border-amber-300">
                                        {dirtyCount} unsaved
                                    </Badge>
                                )}
                            </div>
                            {theme.description && (
                                <p className="text-xs text-muted-foreground truncate">{theme.description}</p>
                            )}
                        </div>
                    </div>

                    <div className="flex items-center gap-3 flex-shrink-0">
                        {saveMessage && (
                            <span className={`text-xs flex items-center gap-1 ${saveMessage.type === 'success' ? 'text-green-600' : 'text-red-600'}`}>
                                {saveMessage.type === 'success' ? <CheckCircle2 className="h-3.5 w-3.5" /> : <AlertCircle className="h-3.5 w-3.5" />}
                                {saveMessage.text}
                            </span>
                        )}
                        <Button
                            size="sm"
                            onClick={handleSave}
                            disabled={saving || !activeDirty}
                            className="bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50"
                        >
                            {saving ? <RefreshCw className="h-4 w-4 mr-2 animate-spin" /> : <Save className="h-4 w-4 mr-2" />}
                            {saving ? 'Saving...' : 'Save'}
                        </Button>
                    </div>
                </div>

                <div className="flex flex-1 overflow-hidden">
                    {/* Sidebar */}
                    <aside className="w-72 flex-shrink-0 border-r bg-muted/20 flex flex-col overflow-hidden">
                        <div className="p-3 border-b">
                            <div className="relative">
                                <Search className="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-muted-foreground" />
                                <Input
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search pages..."
                                    className="pl-8 h-8 text-sm"
                                />
                            </div>
                        </div>

                        <div className="flex-1 overflow-y-auto py-2">
                            {Object.entries(pageGroups).map(([group, keys]) => {
                                const visibleKeys = keys.filter(matchesSearch);
                                if (visibleKeys.length === 0) return null;
                                const isOpen = search.trim() ? true : (openGroups[group] ?? true);

                                return (
                                    <Collapsible key={group} open={isOpen} onOpenChange={() => toggleGroup(group)}>
                                        <CollapsibleTrigger asChild>
                                            <button className="w-full flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider hover:text-foreground transition-colors">
                                                {isOpen ? <ChevronDown className="h-3 w-3" /> : <ChevronRight className="h-3 w-3" />}
                                                {group}
                                                <span className="ml-auto font-normal normal-case text-[10px] text-muted-foreground/70">{visibleKeys.length}</span>
                                            </button>
                                        </CollapsibleTrigger>
                                        <CollapsibleContent>
                                            {visibleKeys.map((key) => {
                                                const isActive = key === activeKey;
                                                const dirty = isDirty(key);
                                                return (
                                                    <button
                                                        key={key}
                                                        onClick={() => handleSelect(key)}
                                                        className={`w-full flex items-center gap-2 pl-7 pr-3 py-1.5 text-sm text-left transition-colors ${
                                                            isActive
                                                                ? 'bg-blue-50 text-blue-700 font-medium border-r-2 border-blue-600'
                                                                : 'text-foreground/80 hover:bg-muted/60'
                                                        }`}
                                                    >
                                                        <span className="truncate flex-1">{fileLabels[key] || key}</span>
                                                        {dirty && <Circle className="h-2 w-2 fill-amber-500 text-amber-500 flex-shrink-0" />}
                                                    </button>
                                                );
                                            })}
                                        </CollapsibleContent>
                                    </Collapsible>
                                );
                            })}
                        </div>
                    </aside>

                    {/* Editor */}
                    <div className="flex-1 flex flex-col overflow-hidden">
                        <div className="border-b bg-background px-4 py-2 flex items-center justify-between">
                            <div className="text-sm">
                                <span className="font-medium">{fileLabels[activeKey] || activeKey}</span>
                                <span className="text-muted-foreground ml-2 font-mono text-xs">{fileKeys[activeKey]}</span>
                            </div>
                            {previewUrl && (
                                <a
                                    href={previewUrl}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="text-xs text-muted-foreground hover:text-foreground transition-colors flex items-center gap-1"
                                >
                                    View Live <ExternalLink className="h-3 w-3" />
                                </a>
                            )}
                        </div>

                        <div className="flex-1 overflow-hidden">
                            {loading[activeKey] ? (
                                <div className="flex items-center justify-center h-full">
                                    <RefreshCw className="h-6 w-6 animate-spin text-muted-foreground" />
                                </div>
                            ) : (
                                <Editor
                                    height="100%"
                                    language="html"
                                    theme="vs-dark"
                                    value={fileContents[activeKey] || ''}
                                    onChange={handleEditorChange}
                                    onMount={(editor) => {
                                        editorRef.current = editor;
                                    }}
                                    options={{
                                        minimap: { enabled: true },
                                        fontSize: 14,
                                        lineNumbers: 'on',
                                        wordWrap: 'on',
                                        automaticLayout: true,
                                        scrollBeyondLastLine: false,
                                        renderWhitespace: 'selection',
                                        bracketPairColorization: { enabled: true },
                                    }}
                                />
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
