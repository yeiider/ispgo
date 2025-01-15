import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {Link, usePage} from "@inertiajs/react";
import {ChevronLeft} from "lucide-react";
import {__} from "@/translation.ts";
import TicketsForm from "@/components/TicketsForm.tsx";
import {Service} from "@/interfaces/Service.ts";
import {Option} from "@/interfaces/Option.ts";

type Props = {
  services: Service[]
  issueTypes: Option[]
}
export default function Create() {
  const props = usePage<Props>().props;

  return (
    <CustomerLayout>
      <div>
        <Link href="/customer-account/tickets" className="flex items-center gap-2">
          <ChevronLeft size={16}/>
          <span>{__('Back')}</span>
        </Link>
      </div>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">{__('Create Ticket')}</h1>
        <TicketsForm services={props.services} issueTypes={props.issueTypes}/>
      </div>
    </CustomerLayout>
  )
}
