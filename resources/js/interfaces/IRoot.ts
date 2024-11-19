export interface IRoot {
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

export interface Errors {}

export interface Flash {
  status: any
}
