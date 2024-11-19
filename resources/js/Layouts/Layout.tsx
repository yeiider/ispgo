import React from "react";
import {usePage, Link} from "@inertiajs/react";
import {__} from "@/translation.ts"
import {Errors, Flash} from "@/interfaces/IRoot.ts";
import {Toaster} from "@/components/ui/sonner.tsx";

type IRoot = {
  errors: Errors
  customer: any
  sidebar: any
  flash: Flash
  loginUrl: string
  registerUrl: string
  isAuthenticated: boolean
  customerDashboardUrl: string
  companyName: string
}

export default function Layout({children}: { children: React.ReactNode }) {

  const props = usePage<IRoot>().props;
  //console.log(props)

  return (
    <>
      <nav className="fixed inset-x-0 top-0 z-50 bg-[#081029] shadow-sm dark:bg-gray-950/90">
        <div className="w-full max-w-7xl mx-auto px-4 py-4">
          <div className="flex justify-between items-center">
            <a href="#" className="flex items-center">
              <span className="text-white">{props.companyName}</span>
            </a>

            <div className="flex items-center gap-4">
              {props.isAuthenticated ? (
                <>
                  <Link href={props.customerDashboardUrl} className="text-white">
                    {__('Dashboard')}
                  </Link>
                  <Link href="/customer-account/logout" method="post" as="button" className="text-white">
                    {__('Sign Out')}
                  </Link></>
              ) : (
                <>
                  <Link href="/customer/login" className="text-white">
                    {__('Sign In')}
                  </Link>
                  <Link href="/customer/register" className="text-white">{__('Sign Up')}</Link>
                </>
              )}

            </div>
          </div>
        </div>
      </nav>

      <main>
        {children}
        <Toaster/>
      </main>
    </>
  )
}


