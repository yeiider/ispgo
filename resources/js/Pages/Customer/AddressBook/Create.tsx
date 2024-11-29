import {__} from "@/translation.ts";
import AddressForm from "@/components/AddressForm.tsx";
import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {usePage} from "@inertiajs/react";

type Props = {
  countries: {
    label: string,
    value: string,
  }[]
}
export default function Create() {
  const props = usePage<Props>().props;
  return (
    <CustomerLayout>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">{__('Create Address')}</h1>
        <AddressForm countries={props.countries}/>
      </div>
    </CustomerLayout>
  )
}
