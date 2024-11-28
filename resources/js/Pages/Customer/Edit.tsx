import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {__} from "@/translation.ts"
import ProfileForm from "@/components/ProfileForm.tsx";
import ChangePasswordForm from "@/components/ChangePasswordForm.tsx";


export default function Edit() {
  return (
    <CustomerLayout>
      <h1 className="text-3xl font-semibold text-slate-950">{__('Edit Account')}</h1>
      <div className="md:flex flex-wrap gap-6 md:flex-nowrap">
        <div className="md:border-r border-slate-200 dark:border-slate-600 md:w-1/2 md:pr-5">
          <ProfileForm/>
        </div>
        <div className="md:border-l border-slate-200 dark:border-slate-600 md:w-1/2 md:pl-5 mt-10 border-t md:mt-0 md:border-t-0">
          <ChangePasswordForm />
        </div>
      </div>
    </CustomerLayout>
  )
}
