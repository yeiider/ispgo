import {ILink} from "@/interfaces/ILink.ts";

export interface IAddressBook {
  current_page: number
  data: Daum[]
  first_page_url: string
  from: number
  last_page: number
  last_page_url: string
  links: ILink[]
  next_page_url: any
  path: string
  per_page: number
  prev_page_url: any
  to: number
  total: number
}

export interface Daum {
  id: number
  address: string
  city: string
  state_province: string
  postal_code: string
  country: string
  created_at: string
  updated_at: string
}
