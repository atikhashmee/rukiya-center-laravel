import React, { useState, useRef, useCallback } from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem, Theme, ThemeFileData } from "@/types";
import { dashboard } from '@/routes';
import { index, edit } from "@/actions/App/Http/Controllers/ThemeController";
import { Button } from "@/components/ui/button";
import { Save, Palette, RefreshCw } from 'lucide-react';
import Editor from '@monaco-editor/react';
import { useEffect } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Themes', href: index().url },
    { title: 'Edit', href: '#' },
];

interface ThemeEditPageProps {
    theme: Theme;
    fileKeys: Record<string, string>;
    fileLabels: Record<string, string>;
}

export default function ThemeEdit() {
    const { theme, fileKeys, fileLabels } = usePage().props as ThemeEditPageProps;

    const fileKeysArray = Object.keys(fileKeys);
    const [activeFile, setActiveFile] = useState<string>(fileKeysArray[0] || '');
    const [fileContents, setFileContents] = useState<Record<string, string>>({});
    const [loading, setLoading] = useState<Record<string, boolean>>({});
    const [saving, setSaving] = useState(false);
    const [saved, setSaved] = useState<Record<string, boolean>>({});
    const editorRef = useRef<any>(null);

    const fetchFileContent = useCallback(async (key: string) => {
        if (fileContents[key] !== undefined) return;

        setLoading(prev => ({ ...prev, [key]: true }));
        try {
            const response = await fetch(`/admin/themes/${theme.id}/file/${key}`);
            const data = await response.json();
            setFileContents(prev => ({ ...prev, [key]: data.content || '' }));
        } catch (error) {
            console.error(`Failed to fetch file: ${key}`, error);
            setFileContents(prev => ({ ...prev, [key]: '// Failed to load file content' }));
        } finally {
            setLoading(prev => ({ ...prev, [key]: false }));
        }
    }, [theme.id, fileContents]);

    useEffect(() => {
        fetchFileContent(activeFile);
    }, [activeFile, fetchFileContent]);

    const handleFileChange = (key: string) => {
        setActiveFile(key);
    };

    const handleEditorChange = (value: string | undefined) => {
        if (value !== undefined && activeFile) {
            setFileContents(prev => ({ ...prev, [activeFile]: value }));
            setSaved(prev => ({ ...prev, [activeFile]: false }));
        }
    };

    const handleSave = async () => {
        if (!activeFile) return;

        setSaving(true);
        try {
            const response = await fetch(`/admin/themes/${theme.id}/file/${activeFile}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': decodeURIComponent(
                        document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || ''
                    ),
                },
                body: JSON.stringify({
                    content: fileContents[activeFile] || '',
                }),
            });

            if (response.ok) {
                setSaved(prev => ({ ...prev, [activeFile]: true }));
                setTimeout(() => {
                    setSaved(prev => ({ ...prev, [activeFile]: false }));
                }, 2000);
            }
        } catch (error) {
            console.error('Failed to save file:', error);
        } finally {
            setSaving(false);
        }
    };

    const handleKeyDown = (e: React.KeyboardEvent) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 's') {
            e.preventDefault();
            handleSave();
        }
    };

    const hasChanges = activeFile && !saved[activeFile] && fileContents[activeFile] !== undefined;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Theme: ${theme.name}`} />
            <div className="flex flex-col h-[calc(100vh-4rem)]" onKeyDown={handleKeyDown}>
                <div className="border-b bg-background px-4 py-2 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <Palette className="h-5 w-5 text-muted-foreground" />
                        <div>
                            <h1 className="text-lg font-semibold">{theme.name}</h1>
                            {theme.description && (
                                <p className="text-sm text-muted-foreground">{theme.description}</p>
                            )}
                        </div>
                    </div>
                    <Button
                        size="sm"
                        onClick={handleSave}
                        disabled={saving || !hasChanges}
                        className="bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50"
                    >
                        {saving ? (
                            <RefreshCw className="h-4 w-4 mr-2 animate-spin" />
                        ) : (
                            <Save className="h-4 w-4 mr-2" />
                        )}
                        {saving ? 'Saving...' : saved[activeFile] ? 'Saved!' : 'Save'}
                    </Button>
                </div>

                <div className="flex border-b bg-muted/30">
                    {fileKeysArray.map((key) => {
                        const isActive = key === activeFile;
                        const isSaved = saved[key];
                        return (
                            <button
                                key={key}
                                onClick={() => handleFileChange(key)}
                                className={`px-4 py-2 text-sm font-medium border-b-2 transition-colors ${
                                    isActive
                                        ? 'border-primary text-primary bg-background'
                                        : 'border-transparent text-muted-foreground hover:text-foreground hover:bg-muted/50'
                                }`}
                            >
                                {fileLabels[key] || key}
                                {isSaved && (
                                    <span className="ml-1 text-green-500">✓</span>
                                )}
                            </button>
                        );
                    })}
                </div>

                <div className="flex-1 overflow-hidden">
                    {loading[activeFile] ? (
                        <div className="flex items-center justify-center h-full">
                            <RefreshCw className="h-6 w-6 animate-spin text-muted-foreground" />
                        </div>
                    ) : (
                        <Editor
                            height="100%"
                            language="html"
                            theme="vs-dark"
                            value={fileContents[activeFile] || ''}
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
        </AppLayout>
    );
}
