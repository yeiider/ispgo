import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {__} from "@/translation.ts";
import {usePage} from "@inertiajs/react";
import AddressForm from "@/components/AddressForm.tsx";
import {Daum} from "@/interfaces/IAddressBook.ts";

type Props = {
  address: Daum,
  id: number,
  countries: {
    label: string,
    value: string,
  }[]
}
export default function Edit() {
  const props  = usePage<Props>().props;

  return (
    <CustomerLayout>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">{__('Edit Address')}</h1>
        <AddressForm address={props.address} id={props.id} countries={props.countries} />
      </div>
    </CustomerLayout>
)
}
