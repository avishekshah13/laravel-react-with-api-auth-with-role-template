import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type MainNavItem } from '@/types';
import { usePage } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from './ui/collapsible';
import { ChevronRight } from 'lucide-react';
import mainNavItems from '@/config/navigation';

export function NavMain() {
    const { url: currentUrl } = usePage();
    const navItems: MainNavItem[] = mainNavItems.map((item) => {
        if (item.subItems) {
            const subItems = item.subItems.map((sub) => ({
                ...sub,
                isActive: currentUrl.startsWith(sub.href),
            }));
            return {
                ...item,
                subItems,
                isActive: subItems.some((sub) => sub.isActive),
            };
        }
        return {
            ...item,
            isActive: currentUrl.startsWith(item.href),
        };
    });
    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>
                Platform
            </SidebarGroupLabel>
            <SidebarMenu>
                {navItems.map((item) => (
                    item.subItems ? (
                        <Collapsible
                            key={item.title}
                            asChild
                            defaultOpen={item.isActive}
                            className="group/collapsible"
                        >
                            <SidebarMenuItem>
                                <CollapsibleTrigger
                                    asChild
                                >
                                    <SidebarMenuButton
                                        tooltip={item.title}
                                        className={`${item.isActive ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium' : ''}`}
                                    >
                                    {item.icon && <item.icon />}
                                    <span>
                                        {item.title}
                                    </span>
                                    <ChevronRight
                                        className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                    />
                                    </SidebarMenuButton>
                                </CollapsibleTrigger>
                                <CollapsibleContent>
                                    <SidebarMenuSub>
                                        {item.subItems?.map((subItem) => (
                                            <SidebarMenuSubItem
                                                key={subItem.title}
                                            >
                                                <SidebarMenuSubButton
                                                    asChild
                                                >
                                                    <a
                                                        href={subItem.href}
                                                        className='flex items-center'
                                                    >
                                                        {subItem.isActive ? (
                                                            <div className='h-[0.6px] w-2 bg-black dark:bg-white'></div>
                                                        ) : ``}
                                                        <span className={`${subItem.isActive ? 'dark:font-light' : ''}`}>
                                                            {subItem.title}
                                                        </span>
                                                    </a>
                                                </SidebarMenuSubButton>
                                            </SidebarMenuSubItem>
                                        ))}
                                    </SidebarMenuSub>
                                </CollapsibleContent>
                            </SidebarMenuItem>
                        </Collapsible>
                    ) : (
                        <SidebarMenuItem
                            key={item.title}
                        >
                            <SidebarMenuButton
                                asChild
                                isActive={item.isActive}
                            >
                                <a
                                    href={item.href}
                                >
                                    {item.icon && <item.icon />}

                                    <span>
                                        {item.title}
                                    </span>
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    )
                    
                ))}
            </SidebarMenu>
        </SidebarGroup>
    );
}
