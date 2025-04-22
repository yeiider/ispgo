import {usePage} from "@inertiajs/react";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel"
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card'
import {Button} from '@/components/ui/button'
import {Check} from "lucide-react";
import Autoplay from "embla-carousel-autoplay"


interface Plan {
  id: number
  name: string
  description: string
  download_speed: number
  upload_speed: number
  monthly_price: string
  overage_fee: any
  data_limit?: number
  unlimited_data: number
  contract_period?: string
  promotions?: string
  extras_included?: string
  geographic_availability?: string
  promotion_start_date: any
  promotion_end_date: any
  plan_image?: string
  customer_rating?: string
  customer_reviews?: string
  service_compatibility?: string
  network_priority?: string
  technical_support?: string
  additional_benefits?: string
  connection_type: string
  plan_type: string
  modality_type: string
  status: string
  created_by: any
  updated_by: any
  created_at: string
  updated_at: string
  profile_smart_olt: any
  is_synchronized: number
}

type Pros = {
  plans: Plan[]
}


export default function Plans() {
  const {plans} = usePage<Pros>().props;
  console.log(plans)

  return (
    <section className="mx-auto max-w-6xl px-6 mt-10 md-mt-24 lg:mt-32">
      <div className="">
        <div className="mx-auto max-w-2xl space-y-6 text-center">
          <h1 className="text-center text-4xl font-semibold lg:text-5xl">Pricing that Scales with You</h1>
          <p>Gemini is evolving to be more than just the models. It supports an entire to the APIs and platforms helping
            developers and businesses innovate.</p>
        </div>
        <Carousel className="mt-10" plugins={[
          Autoplay({
            delay: 3000,
          })
        ]}>
          <CarouselContent className="-ml-1">
            {plans.map((plan, index) => (
              <CarouselItem key={index} className="pl-1 md:basis-1/2 lg:basis-1/3">
                <Card>
                  <CardHeader>
                    {plan.plan_image && (
                      <img src={plan.plan_image} height="50" alt={plan.name}/>
                    )}
                    <CardTitle className="font-medium">{plan.name}</CardTitle>

                    <span className="my-3 block text-2xl font-semibold">{plan.monthly_price} / mo</span>

                    <CardDescription className="text-sm">{plan.plan_type}</CardDescription>
                    <Button asChild variant="outline" className="mt-4 w-full">
                      <a href="">Get Started</a>
                    </Button>
                  </CardHeader>

                  <CardContent className="space-y-4">
                    <hr className="border-dashed"/>
                    <p>{plan.description}</p>

                    <ul className="list-outside space-y-3 text-sm">
                      {plan.extras_included && plan.extras_included.split('.').map((item, index) => (
                        <li key={index} className="flex items-center gap-2">
                          {item.length > 1 && (
                            <>
                              <Check className="size-3"/>
                              {item}
                            </>
                          )}
                        </li>
                      ))}
                    </ul>
                  </CardContent>
                </Card>
              </CarouselItem>
            ))}

          </CarouselContent>
          <CarouselPrevious/>
          <CarouselNext/>
        </Carousel>

      </div>
    </section>
  )
}
