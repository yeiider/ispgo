import {Input} from "@/components/ui/input.tsx";
import {Search} from "lucide-react";
import {FormEvent, useState} from "react";
import axios from "axios";
import {ICustomer} from "@/interfaces/ICustomer.ts";
import {toast} from "sonner";

interface Props {
  onSearchCustomers: (customer: ICustomer[]) => void
}

export default function SearchBar({onSearchCustomers}: Props) {
  const [searchQuery, setSearchQuery] = useState<string | null>(null);

  const search = async () => {
    try {
      const response = await axios.get('/customer/search', {
        params: {input: searchQuery}
      });
      if (response) {
        onSearchCustomers(response.data)
      }
    }catch (e) {
      console.log(e)
      toast.error("An error has occurred, try latter", {
        classNames: {
          icon: 'text-red-500',
          title: 'text-red-500'
        }
      })
    }
  }

  const handlerInput = async (e: FormEvent<HTMLInputElement>) => {
    if ('value' in e.target && typeof e.target.value == 'string' && e.target.value.length > 2) {
      setSearchQuery(e.target.value)
      await search()
    }
  }

  return (
    <div className="relative flex items-center">
      <Search className="absolute left-[10px] text-gray-400 stroke-1" size={20}/>
      <Input type="search" className="pl-8" onInput={handlerInput}/>
    </div>
  )
}
