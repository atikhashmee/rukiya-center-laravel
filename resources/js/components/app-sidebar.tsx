import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { LayoutGrid, Rss, PackageSearch, Kanban, User, Book } from 'lucide-react';
import AppLogo from './app-logo';
import {index} from '@/actions/App/Http/Controllers/BlogController';
import productIndex from '@/actions/App/Http/Controllers/ProductController';
import serviceIndex from '@/actions/App/Http/Controllers/ServiceController';
import customer from '@/actions/App/Http/Controllers/CustomerController';
import booking from '@/actions/App/Http/Controllers/BookingController';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Blog',
        href: index(),
        icon: Rss,
    },
    {
        title: 'Product',
        href: productIndex.index(),
        icon: PackageSearch,
    },
    {
        title: 'Service',
        href: serviceIndex.index(),
        icon: Kanban,
    },
    {
        title: 'Customers',
        href: customer.index(),
        icon: User,
    },
    {
        title: 'Bookings',
        href: booking.index(),
        icon: Book,
    },
];

const footerNavItems: NavItem[] = [
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
