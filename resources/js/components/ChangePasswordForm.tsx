import React, {useState} from "react";
import {__} from "@/translation.ts";
import {useForm as inertiaUseForm, usePage} from "@inertiajs/react";
import {useForm} from "react-hook-form";
import {z} from "zod";
import {zodResolver} from "@hookform/resolvers/zod";
import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {Input} from "@/components/ui/input.tsx";
import {Eye, EyeOff} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";

type Props = {
  routeChangePassword: string,
}
export default function ChangePasswordForm() {
  const {routeChangePassword} = usePage<Props>().props;
  const formSchema = z.object({
    current_password: z.string().min(3),
    password: z.string().min(3),
    password_confirmation: z.string().min(3),
  }).refine(data => data.password === data.password_confirmation, {
    message: __("The password confirmation does not match."),
    path: ['password_confirmation']
  })

  const {data, put, setData} = inertiaUseForm({
    current_password: "",
    password: "",
    password_confirmation: "",
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  const [isVisible, setIsVisible] = useState<boolean>(false);
  const toggleVisibility = () => setIsVisible((prevState) => !prevState);

  const onSubmit = async () => {
    console.log(data)
    put(routeChangePassword);
  }
  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  return (
    <>
      <h2 className="text-2xl mt-3 md:mt-10 font-light">{__('Change Password')}</h2>
      <Form {...form}>
        <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-5">
          <FormField
            control={form.control}
            name="current_password"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="current_password">{__('Current Password')}</FormLabel>
                <FormControl>
                  <Input type="password" placeholder="" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />
          <FormField
            control={form.control}
            name="password"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="password">{__('Password')}</FormLabel>
                <FormControl>
                  <div className="relative">
                    <Input type={isVisible ? "text" : "password"} placeholder="" {...field} onInput={handleInput}/>
                    <button
                      className="absolute inset-y-0 end-0 flex h-full w-9 items-center justify-center rounded-e-lg text-muted-foreground/80 ring-offset-background transition-shadow hover:text-foreground focus-visible:border focus-visible:border-ring focus-visible:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                      type="button"
                      onClick={toggleVisibility}
                      aria-label={isVisible ? "Hide password" : "Show password"}
                      aria-pressed={isVisible}
                      aria-controls="password"
                    >
                      {isVisible ? (
                        <EyeOff size={16} strokeWidth={2} aria-hidden="true"/>
                      ) : (
                        <Eye size={16} strokeWidth={2} aria-hidden="true"/>
                      )}
                    </button>
                  </div>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />

          <FormField
            control={form.control}
            name="password_confirmation"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="password_confirmation">{__('Confirm Password')}</FormLabel>
                <FormControl>
                  <Input type={isVisible ? "text" : "password"} placeholder="" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />
          <Button type="submit" className="w-full md:w-auto">
            <span>{__('Change password')}</span>
          </Button>
        </form>
      </Form>
    </>
  )
}
