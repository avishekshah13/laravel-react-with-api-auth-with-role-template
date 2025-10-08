import { MainNavItem } from "@/types";
import { LayoutGrid, Package } from "lucide-react";

const mainNavItems:MainNavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Global Values',
        href: '/',
        icon: Package,
        subItems: [
            {
                title: 'Config',
                href: '/config',
            },
            {
                title: 'System',
                href: '/system',
            }
        ],
    }
]
 
export default mainNavItems;