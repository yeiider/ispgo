import {Head, usePage} from '@inertiajs/react'
import Hero from "@/components/Hero.tsx";
import IconSections from "@/components/IconSections.tsx";
import FAQ from "@/components/FAQ.tsx";
import Features from "@/components/Features.tsx";
import Clients from "@/components/Clients.tsx";
import WhatIsIt from "@/components/WhatIsIt.tsx";
import ContactUs from "@/components/ContactUs.tsx";

type Props = {
  companyName: string;
}
export default function Welcome() {
  const {companyName} = usePage<Props>().props;
  const title = `Welcome - ${companyName}`;
  return (
    <>
      <Head>
        <title>{title}</title>
        <meta head-key="description" name="description" content="This is a page specific description"/>
      </Head>
      <div className="mt-5">
        <Hero/>
        <Clients/>
        <IconSections/>
        <Features/>
        <WhatIsIt/>
        <FAQ/>
        <ContactUs />
      </div>
    </>
  )
}
