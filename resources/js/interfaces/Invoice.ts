import {IAddress} from "@/interfaces/IAddress.ts";
import {ICustomer} from "@/interfaces/ICustomer.ts";

export interface Invoice {
  id: number
  increment_id: string
  service_id: number
  customer_id: number
  user_id: number
  subtotal: string
  tax: string
  total: string
  amount: string
  discount: string
  outstanding_balance: string
  issue_date: string
  due_date: string
  status: string
  payment_method: string
  notes: string
  payment_support: any
  daily_box_id: number
  created_by: number
  updated_by: number
  created_at: string
  updated_at: string
  additional_information: any[]
  full_name: string
  email_address: string
  customer: ICustomer
  address: IAddress
}
