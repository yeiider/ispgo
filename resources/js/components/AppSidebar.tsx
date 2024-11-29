import {
  icons,
  ChartNoAxesCombined, SatelliteDish
} from "lucide-react"

import {
  Sidebar,
  SidebarContent,
  SidebarFooter, SidebarGroup, SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar"
import {usePage, Link} from "@inertiajs/react";
import {Avatar, AvatarFallback} from "@/components/ui/avatar.tsx";
import {getInitials} from "@/lib/utils.ts";

type Props = {
  sidebar: {
    app_name: string,
    links: ILink[]
    url_logout: string
  },
  customer: {
    first_name: string,
    last_name: string,
    email_address: string,
    id: number
  }
  companyName: string
}

interface ILink {
  code: string,
  title: string,
  url: string,
  icon: string,
}


interface IIcon {
  name: string,
  color?: string,
  size?: string
}
const Icon = ({name, color, size}: IIcon) => {
  // @ts-ignore
  const LucideIcon = icons[name];
  return <LucideIcon color={color} size={size}/>;
}

export function AppSidebar({...props}: React.ComponentProps<typeof Sidebar>) {

  const {sidebar, customer, companyName} = usePage<Props>().props;

  return (
    <Sidebar variant="inset" {...props}>
      <SidebarHeader className="">
        <SidebarMenu className="">
          <SidebarMenuItem>
            <SidebarMenuButton size="lg" asChild>
              <a href="#">
                <div
                  className="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                  <SatelliteDish className="size-4" />
                </div>
                <div className="grid flex-1 text-left text-sm leading-tight">
                  <span className="truncate font-semibold">{companyName}</span>
                  <span className="truncate text-xs">Enterprise</span>
                </div>
              </a>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>
      <SidebarContent>
        <SidebarGroup className="group-data-[collapsible=icon]:hidden">
          <SidebarGroupLabel>--</SidebarGroupLabel>
          <SidebarMenu>
            {sidebar.links.map((item) => (
              <SidebarMenuButton key={item.code}>
                <Icon name={item.icon}/>
                <Link href={item.url}>
                  <span>{item.title}</span>
                </Link>
              </SidebarMenuButton>
            ))}
          </SidebarMenu>
        </SidebarGroup>
      </SidebarContent>
      <SidebarFooter>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton
              size="lg"
              className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
              <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                <Avatar className="h-8 w-8 lg:h-10 lg:w-10 rounded-lg">
                  <AvatarFallback className="rounded-lg uppercase">{getInitials(customer.first_name)}</AvatarFallback>
                </Avatar>
                <div className="grid flex-1 text-left text-sm leading-tight">
                  <span className="truncate font-semibold">{customer.first_name}</span>
                  <span className="truncate text-xs">{customer.email_address}</span>
                </div>
              </div>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarFooter>
    </Sidebar>
  )
}
