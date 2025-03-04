import {Link, usePage} from "@inertiajs/react";
import {Errors, Flash} from "@/interfaces/IRoot.ts";
import {__} from "@/translation.ts";
import {useEffect, useState} from "react";
import {twMerge} from "tailwind-merge";
import {BadgeDollarSign, SatelliteDish} from "lucide-react";
import ModeToggle from "@/components/ModeToggle.tsx";

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

const INITIAL_PADDING = 'md:py-1';

export default function Navigation() {
  const props = usePage<IRoot>().props;
  const [active, setActive] = useState(false);
  const [padding, setPadding] = useState<string>(INITIAL_PADDING);

  const handleScroll = () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    setPadding(scrollTop > 0 ? 'md:py-0' : INITIAL_PADDING);
  };

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);
    return () => {
      window.removeEventListener('scroll', handleScroll); // Limpieza del evento
    };
  }, []);

  const toggleMenu = () => {
    setActive((prevState) => !prevState);
  }

  const classActive = "border-gray-800 font-medium text-gray-800";
  const isActive = (path: string) => {
    return window.location.pathname === path;
  }

  const initialPadding = 'md:py-2'

  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > 0) {
      setPadding('md:py-0')
    } else {
      setPadding(initialPadding);
    }
  })

  return (
    <header
      className="max-w-[85rem] mx-auto sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full text-sm sm:px-6 lg:px-8">
      <nav
        className={`transition-all duration-300 mt-4 relative w-full bg-white/60 backdrop-blur-md dark:bg-neutral-900/60 border border-gray-200 rounded-lg mx-2  md:flex md:items-center md:justify-between py-2.5 ${padding} md:px-4 md:mx-auto dark:bg-neutral-900 dark:border-neutral-700`}>
        <div className="px-4 md:px-0 flex justify-between items-center">
          {/* Logo*/}
          <div>
            <a className="flex items-center gap-2 rounded-md text-xl font-semibold focus:outline-none focus:opacity-80"
               href="/" aria-label="Preline">
              <SatelliteDish size={32}/>
              <span className="leading-none">{props.companyName}</span>
            </a>
          </div>
          {/* End Logo*/}

          <div className="md:hidden">
            {/* Toggle Button*/}
            <button
              type="button"
              onClick={toggleMenu}
              className="hs-collapse-toggle flex justify-center items-center size-6 border border-gray-200 text-gray-500 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
            >
              <svg className="hs-collapse-open:hidden shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                   height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                   strokeLinecap="round" strokeLinejoin="round">
                <line x1="3" x2="21" y1="6" y2="6"/>
                <line x1="3" x2="21" y1="12" y2="12"/>
                <line x1="3" x2="21" y1="18" y2="18"/>
              </svg>
              <svg className="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                   width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                   strokeLinecap="round" strokeLinejoin="round">
                <path d="M18 6 6 18"/>
                <path d="m6 6 12 12"/>
              </svg>
            </button>
            {/* End Toggle Button*/}
          </div>
        </div>

        <div
          className={twMerge("hs-collapse overflow-hidden transition-all duration-300 basis-full grow md:block", !active && "hidden")}>
          <div
            className="flex flex-col md:flex-row md:items-center md:justify-end gap-2 md:gap-3 mt-3 md:mt-0 py-2 md:py-0 md:ps-7">
            <Link
              className={twMerge("link", isActive("/") && classActive)}
              href="/">{__('Home')}</Link>

            {props.isAuthenticated ? (
              <>
                <Link
                  className={twMerge("link", isActive("/customer-account") && classActive)}
                  href={"/customer-account/"}>{__('Dashboard')}</Link>
                <Link
                  className={twMerge("link text-red-500", isActive("/customer/logout") && classActive)}
                  href={"/customer-account/logout"}>{__('Sign Out')}</Link>
              </>
            ) : (
              <>
                <Link
                  className={twMerge("link", isActive("/customer/login") && classActive)}
                  href={"/customer/login"}>{__('Sign In')}</Link>
                <Link
                  className={twMerge("link", isActive("/customer/register") && classActive)}
                  href={"/customer/register"}>{__('Sign Up')}</Link>
              </>
            )}
            <Link
              className={twMerge("link flex items-center gap-1", isActive("/checkout") && classActive)}
              href={"/checkout"}>
              {__('Payments portal')}
              <BadgeDollarSign size={16}/>
            </Link>
            <div className="mr-2">
              <ModeToggle/>
            </div>
          </div>
        </div>
      </nav>
    </header>

  )
}
