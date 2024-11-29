import {SatelliteDish} from "lucide-react";
import {usePage} from "@inertiajs/react";

type Props = {
  companyName: string
}
export default function Footer() {
  const {companyName} = usePage<Props>().props;

  return (
    <footer className="sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full text-sm">
      <nav
        className="mb-4 relative max-w-2xl w-full bg-white border border-gray-200 rounded-lg mx-2 py-2.5 px-2 md:flex md:items-center md:justify-between  md:px-4 md:mx-auto dark:bg-neutral-900 dark:border-neutral-700">
        <div className="px-4 md:px-0 flex justify-between items-center">
          {/* Logo*/}
          <div>
            <a className="flex items-center gap-2 rounded-md text-xl font-semibold focus:outline-none focus:opacity-80"
               href="/" aria-label="Preline">
              <SatelliteDish size={32}/>
              <span className="leading-none">{companyName}</span>
            </a>
          </div>
          {/* End Logo*/}
          <div>
            <div className="border-s border-neutral-700 ps-5 ms-5">
              <p className="text-sm text-neutral-400">
                © {new Date().getFullYear()} isp Labs.
              </p>
            </div>
          </div>
        </div>
      </nav>
    </footer>
  )
}
