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
import { usePermissions } from '@/lib/permissions';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { LayoutGrid, Rss, PackageSearch, Kanban, User, Book, MailSearchIcon, Palette, FolderTree, GraduationCap, ShoppingCart, ShieldCheck } from 'lucide-react';
import AppLogo from './app-logo';
import {index} from '@/actions/App/Http/Controllers/BlogController';
import productIndex from '@/actions/App/Http/Controllers/ProductController';
import serviceIndex from '@/actions/App/Http/Controllers/ServiceController';
import userIndex from '@/actions/App/Http/Controllers/UserController';
import customer from '@/actions/App/Http/Controllers/CustomerController';
import booking from '@/actions/App/Http/Controllers/BookingController';
import themeIndex from '@/actions/App/Http/Controllers/ThemeController';
import productCategoryIndex from '@/actions/App/Http/Controllers/ProductCategoryController';
import serviceCategoryIndex from '@/actions/App/Http/Controllers/ServiceCategoryController';
import roleIndex from '@/actions/App/Http/Controllers/RoleController';

/** Nav item plus the permission it needs. Groups are dropped when no child survives. */
type GuardedNavItem = NavItem & { permission: string; items?: GuardedNavItem[] };

const mainNavItems: GuardedNavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        permission: 'dashboard.view',
    },
    {
        title: 'Blog',
        href: index(),
        icon: Rss,
        permission: 'blog.view',
    },
    {
        title: 'Catalog',
        href: '#',
        icon: PackageSearch,
        permission: 'products.view',
        items: [
            { title: 'Products', href: productIndex.index(), icon: PackageSearch, permission: 'products.view' },
            { title: 'Categories', href: productCategoryIndex.index(), icon: FolderTree, permission: 'products.view' },
        ],
    },
    {
        title: 'Service',
        href: '#',
        icon: Kanban,
        permission: 'services.view',
        items: [
            { title: 'Services', href: serviceIndex.index(), icon: Kanban, permission: 'services.view' },
            { title: 'Categories', href: serviceCategoryIndex.index(), icon: FolderTree, permission: 'services.view' },
        ],
    },
    {
        title: 'Customers',
        href: customer.index(),
        icon: User,
        permission: 'customers.view',
    },
    {
        title: 'Bookings',
        href: booking.index(),
        icon: Book,
        permission: 'bookings.view',
    },
    {
        title: 'Orders',
        href: '/admin/orders',
        icon: ShoppingCart,
        permission: 'orders.view',
    },
    {
        title: 'Instructors',
        href: '/admin/instructors',
        icon: GraduationCap,
        permission: 'instructors.view',
    },
    {
        title: 'Users',
        href: userIndex.index(),
        icon: MailSearchIcon,
        permission: 'users.view',
    },
    {
        title: 'Roles',
        href: roleIndex.index(),
        icon: ShieldCheck,
        permission: 'roles.view',
    },
    {
        title: 'Themes',
        href: themeIndex.index(),
        icon: Palette,
        permission: 'themes.view',
    },
];

const footerNavItems: NavItem[] = [
];

/** Keeps items the user may see; a group with no visible child disappears. */
function visibleNavItems(items: GuardedNavItem[], held: string[]): NavItem[] {
    return items.flatMap(({ permission, items: children, ...item }) => {
        if (!children) {
            return held.includes(permission) ? [item] : [];
        }

        const visibleChildren = visibleNavItems(children, held);

        return visibleChildren.length > 0 ? [{ ...item, items: visibleChildren }] : [];
    });
}

export function AppSidebar() {
    const permissions = usePermissions();
    const navItems = visibleNavItems(mainNavItems, permissions);

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
                <NavMain items={navItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
