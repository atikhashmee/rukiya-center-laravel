import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { useCan } from '@/lib/permissions';
import { cn, isSameUrl, resolveUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit } from '@/routes/profile';
import { show } from '@/routes/two-factor';
import { edit as editPassword } from '@/routes/user-password';
import { edit as editWhatsApp } from '@/routes/whatsapp';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: edit(),
        icon: null,
    },
    {
        title: 'Password',
        href: editPassword(),
        icon: null,
    },
    {
        title: 'Two-Factor Auth',
        href: show(),
        icon: null,
    },
    {
        title: 'Appearance',
        href: editAppearance(),
        icon: null,
    },
];

// Site-wide WhatsApp config, so it needs the settings section's manage right.
const whatsAppNavItem: NavItem = {
    title: 'WhatsApp',
    href: editWhatsApp(),
    icon: null,
};

export default function SettingsLayout({ children }: PropsWithChildren) {
    const canManageSettings = useCan('settings.manage');

    // When server-side rendering, we only render the layout on the client...
    if (typeof window === 'undefined') {
        return null;
    }

    const currentPath = window.location.pathname;
    const navItems = canManageSettings ? [...sidebarNavItems, whatsAppNavItem] : sidebarNavItems;

    return (
        <div className="p-4 md:p-6">
            <Heading
                title="Settings"
                description="Manage your profile and account settings"
            />

            <div className="flex flex-col lg:flex-row lg:space-x-10">
                <aside className="w-full max-w-xl lg:w-52">
                    <nav className="flex flex-col space-y-1 space-x-0">
                        {navItems.map((item, index) => (
                            <Button
                                key={`${resolveUrl(item.href)}-${index}`}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn(
                                    'relative w-full justify-start text-muted-foreground hover:bg-muted/60 hover:text-foreground',
                                    {
                                        'bg-primary/10 font-medium text-primary before:absolute before:inset-y-1.5 before:left-0 before:w-[3px] before:rounded-r-full before:bg-primary hover:bg-primary/10 hover:text-primary':
                                            isSameUrl(currentPath, item.href),
                                    },
                                )}
                            >
                                <Link href={item.href}>
                                    {item.icon && (
                                        <item.icon className="h-4 w-4" />
                                    )}
                                    {item.title}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="my-6 lg:hidden" />

                <div className="flex-1 md:max-w-2xl">
                    <section className="max-w-2xl space-y-12 rounded-xl border bg-card p-6 text-card-foreground shadow-sm">
                        {children}
                    </section>
                </div>
            </div>
        </div>
    );
}
