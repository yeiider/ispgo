import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {useForm} from "react-hook-form";
import {z} from "zod";
import {zodResolver} from "@hookform/resolvers/zod";
import {Daum} from "@/interfaces/IAddressBook.ts";
import React from "react";
import {useForm as inertiaUseForm} from "@inertiajs/react";
import {__} from "@/translation.ts";
import {Input} from "@/components/ui/input.tsx";
import {Button} from "@/components/ui/button.tsx";
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select.tsx";


interface Props {
  address?: Daum,
  id?: number,
  countries: {
    label: string,
    value: string
  }[]
}

export default function AddressForm({address, id, countries}: Props) {
  console.log(countries)

  const formSchema = z.object({
    address: z.string().min(3),
    city: z.string().min(3),
    country: z.string().min(3),
    state_province: z.string().min(3),
    latitude: z.string().min(1),
    longitude: z.string().min(1),
    postal_code: z.string().min(3),
  })


  const {data, put, post, setData} = inertiaUseForm({
    address: address?.address || "",
    city: address?.city || "",
    country: address?.country || "",
    state_province: address?.state_province || "",
    latitude: address?.latitude || "",
    longitude: address?.longitude || "",
    postal_code: address?.postal_code || "",
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  const onSubmit = () => {
    if (typeof address !== "undefined") {
      put(`/customer-account/address-book/update/${id}`)
    } else {
      post("/customer-account/address-book")
    }
  }
  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }
  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className=" max-w-5xl">
        <div className="grid grid-cols-4 gap-4">
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="address"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="address">{__('Address')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="cl #2" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="city"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="city">{__('City')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="Miami" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />

          </div>
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="state_province"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="state_province">{__('State Province')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="country"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="country">{__('Country')}</FormLabel>
                  <Select name="country" value={field.value} onValueChange={(value) => {
                    field.onChange(value);
                    setData({
                      ...data,
                      country: value
                    })
                  }} defaultValue={field.value}>
                    <FormControl>
                      <SelectTrigger>
                        <SelectValue placeholder="Select a document type"/>
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent>
                      {countries.map((item) => (
                        <SelectItem key={item.value} value={item.value}>{__(item.label)}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage/>
                </FormItem>
              )}
            />

          </div>
          <div className="col-span-1">
            <FormField
              control={form.control}
              name="latitude"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="latitude">{__('Latitude')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="41" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-1">
            <FormField
              control={form.control}
              name="longitude"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="longitude">{__('Longitude')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="-32" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="postal_code"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="postal_code">{__('Postal Code')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
        </div>

        <Button type="submit" className="mt-4">
          <span>{__(address ? 'Save Changes' : 'Save Address')}</span>
        </Button>
      </form>
    </Form>
  )
}
