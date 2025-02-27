import React from "react";
import {SidebarInset, SidebarProvider, SidebarTrigger} from "@/components/ui/sidebar.tsx";
import {AppSidebar} from "@/components/AppSidebar.tsx";

export default function CustomerLayout({children}: { children: React.ReactNode }) {
  return (
    <SidebarProvider className="items-start">
      <AppSidebar className="mt-20 h-[85svh]"/>
      <SidebarInset className="!min-h-[70svh]">
        <header className="flex h-16 shrink-0 items-center gap-2">
          <div className="flex items-center gap-2 px-4">
            <SidebarTrigger className="-ml-1"/>
          </div>
        </header>
        <div className="flex flex-1 flex-col gap-4 p-4 pt-0">
          {children}
        </div>
      </SidebarInset>
    </SidebarProvider>
  )
}
