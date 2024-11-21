import React from "react";
import {Toaster} from "@/components/ui/sonner.tsx";
import Navigation from "@/components/Navigation.tsx";
import Footer from "@/components/Footer.tsx";

export default function Layout({children}: { children: React.ReactNode }) {

  return (
    <>
      <Navigation/>
      <main>
        {children}
        <Toaster/>
      </main>
      <Footer/>
    </>
  )
}


