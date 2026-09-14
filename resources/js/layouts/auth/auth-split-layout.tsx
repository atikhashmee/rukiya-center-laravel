import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

interface AuthLayoutProps {
    title?: string;
    description?: string;
}

export default function AuthSplitLayout({
    children,
    title,
    description,
}: PropsWithChildren<AuthLayoutProps>) {
    const { name, quote } = usePage<SharedData>().props;

    return (
        <div className="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div className="relative hidden h-full flex-col overflow-hidden bg-sidebar p-10 text-sidebar-foreground lg:flex dark:border-r">
                <div className="absolute inset-0 bg-gradient-to-br from-sidebar via-sidebar to-primary/50" />
                <div className="absolute -right-24 -bottom-24 size-96 rounded-full bg-sidebar-primary/10 blur-3xl" />
                <Link
                    href={home()}
                    className="relative z-20 flex items-center gap-3 text-lg font-semibold tracking-tight"
                >
                    <span className="flex size-10 items-center justify-center rounded-lg bg-sidebar-primary shadow-sm">
                        <AppLogoIcon className="size-6 fill-current text-sidebar-primary-foreground" />
                    </span>
                    {name}
                </Link>
                {quote && (
                    <div className="relative z-20 mt-auto border-l-2 border-sidebar-primary pl-6">
                        <blockquote className="space-y-2">
                            <p className="text-lg leading-relaxed">
                                &ldquo;{quote.message}&rdquo;
                            </p>
                            <footer className="text-sm text-sidebar-primary">
                                {quote.author}
                            </footer>
                        </blockquote>
                    </div>
                )}
            </div>
            <div className="w-full lg:p-8">
                <div className="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <Link
                        href={home()}
                        className="relative z-20 flex items-center justify-center lg:hidden"
                    >
                        <AppLogoIcon className="h-10 fill-current text-primary sm:h-12" />
                    </Link>
                    <div className="flex flex-col items-start gap-2 text-left sm:items-center sm:text-center">
                        <h1 className="text-2xl font-semibold tracking-tight">
                            {title}
                        </h1>
                        <p className="text-sm text-balance text-muted-foreground">
                            {description}
                        </p>
                    </div>
                    {children}
                </div>
            </div>
        </div>
    );
}
