import {IAddress} from "@/interfaces/IAddress.ts";
import {ICustomer} from "@/interfaces/ICustomer.ts";

export interface Invoice {
  subtotal: string
  increment_id: string
  tax: string
  total: string
  amount: string
  discount: string
  customer_name: string
  product: string
  status: string
  issue_date: string
  due_date: string
  customer: ICustomer
  address: IAddress
}
