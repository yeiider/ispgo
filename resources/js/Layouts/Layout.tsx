import React from "react";
import {Toaster} from "@/components/ui/sonner.tsx";
import Navigation from "@/components/Navigation.tsx";
import Footer from "@/components/Footer.tsx";
import {Head} from "@inertiajs/react";
import favicon from "@/assets/favicon.svg";

export default function Layout({children}: { children: React.ReactNode }) {

  return (
    <>
      <Head>
        <link rel="icon" type="image/svg+xml" href={favicon}/>
      </Head>
      <Navigation/>
      <main>
        {children}
        <Toaster/>
      </main>
      <Footer/>
    </>
  )
}


