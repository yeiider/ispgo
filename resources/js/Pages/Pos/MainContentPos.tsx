import {useEffect, useState} from "react";
import {ICustomer} from "@/interfaces/ICustomer.ts";
import {Invoice} from "@/interfaces/Invoice.ts";
import axios from "axios";
import InvoiceDetails from "@/Pages/Pos/InvoiceDetails.tsx";
import InvoiceListComponent from "@/Pages/Pos/InvoiceListComponent.tsx";
import CashierInfo from "@/Pages/Pos/CashierInfo.tsx";
import RealTimeClock from "@/Pages/Pos/RealTimeClock.tsx";
import SearchBar from "@/Pages/Pos/SearchBar.tsx";
import CustomerList from "@/Pages/Pos/CustomerList.tsx";
import CreateDailyBoxForm from "@/Pages/Pos/CreateDailyBoxForm.tsx";
import CalculatorComponent from "@/Pages/Pos/CalculatorComponent.tsx";
import {usePage} from "@inertiajs/react";
import {BadgeCheck, Box} from "lucide-react"
import {priceFormat} from "@/lib/utils.ts";
import {Button} from "@/components/ui/button.tsx";
import {__} from "@/translation.ts";

type Props = {
  config: {
    currency: string
    currencySymbol: string
    box: Box
    todayBox: TodayBox
  }
  cashier: string
}

export interface Box {
  id: number
  name: string
  users: number[]
  created_at: string
  updated_at: string
}

export interface TodayBox {
  id: number
  box_id: number
  date: string
  start_amount: string
  end_amount: string
  transactions: string
  created_at: string
  updated_at: string
}

export default function MainContentPos() {
  const [customers, setCustomers] = useState<ICustomer[]>([]);
  const [currentComponent, setCurrentComponent] = useState<string>('invoices');
  const [selectInvoice, setSelectInvoice] = useState<Invoice | null>(null);
  const [customerSelected, setCustomerSelected] = useState<ICustomer | null>(null);


  const {config, cashier} = usePage<Props>().props;

  useEffect(() => {

    // Fetch invoices
    const fetchInvoices = async () => {
      try {
        const response = await axios.get('/invoice/find-by-box');

      } catch (error) {
        console.error('Error fetching invoices', error);
      }
    };

    fetchInvoices();
  }, []);

  // Handle setting customers
  const handleSetCustomers = (customerData: ICustomer[]) => {
    setCustomers(customerData);
    setCustomerSelected(null);
  };

  const handlerSetTodayBox = () => {

  }

  return (

    <div>
      {config.todayBox ? (
        <div className="flex flex-col md:flex-row w-[90%] m-auto">
          {/* Left Column */}
          <div className="w-full md:w-1/3 mt-4">
            <div className="p-4 border rounded-lg h-full flex flex-col">
              <div className="flex items-center gap-2 md:gap-4">
                <Box size={30}/>
                <div className="flex flex-col">
                  <h3 className="text-[1rem]">{config.box?.name}</h3>
                  <span className="text-[1.5rem] font-semibold">{priceFormat(config.todayBox.end_amount, {
                    currency: 'COP',
                    locale: 'es-CO'
                  })}</span>
                </div>
              </div>
              <hr className="my-4"/>
              <CalculatorComponent/>
            </div>
          </div>

          {/* Middle Column */}
          <div className="w-full md:w-2/3 p-4">
            <div className="flex gap-3">
              <Button variant="outline" onClick={() => setCurrentComponent('invoices')}>
                <span>{__('Paid bills')}</span>
                <BadgeCheck className="text-green-500"/>
              </Button>
            </div>

            <main className="border rounded-lg flex justify-center max-h-dvh min-h-[65vh] py-5 mt-4">
              {currentComponent === 'invoices' && !selectInvoice && (
                <InvoiceListComponent/>
              )}

              {selectInvoice && (
                <InvoiceDetails
                  onSelectedInvoice={setSelectInvoice}
                  invoice={selectInvoice}
                  customer={customerSelected}
                />
              )}
            </main>
          </div>

          {/* Right Column */}
          <div className="w-full md:w-1/3 p-4 flex flex-col gap-4">
            <CashierInfo cashierName={cashier}/>
            <RealTimeClock/>
            <SearchBar onSearchCustomers={handleSetCustomers}/>
            <CustomerList
              customers={customers}
              onInvoiceSelected={setSelectInvoice}
              onCustomerSelected={setCustomerSelected}
            />
          </div>
        </div>
      ) : (
        <CreateDailyBoxForm onDailyBoxCreated={handlerSetTodayBox}/>
      )}
    </div>
  )
}
