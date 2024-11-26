import Hero from "@/components/Hero.tsx";
import IconSections from "@/components/IconSections.tsx";
import FAQ from "@/components/FAQ.tsx";
import Features from "@/components/Features.tsx";
import Clients from "@/components/Clients.tsx";
import Subscribe from "@/components/Subscribe.tsx";

export default function Welcome() {
  return (
    <div className="mt-5">
      <Hero/>
      <Clients/>
      <IconSections/>
      <Features/>
      <FAQ/>
      <Subscribe/>
    </div>
  )
}
