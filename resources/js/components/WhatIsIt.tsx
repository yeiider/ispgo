// @ts-ignore
import rectangle21 from '@/assets/rectangle21.png';
// @ts-ignore
import rectangle19 from '@/assets/rectangle19.png';
import {usePage} from "@inertiajs/react";

type Props = {
  companyName: string
}
export default function WhatIsIt() {
  const {companyName} = usePage<Props>().props;

  return (
    <div className="mt-28 container mx-auto px-12">
      <div className="text-center max-w-screen-md mx-auto aos-init aos-animate">
        <h1 className="text-3xl text-gray-800 font-bold mb-4">¿Qué es <span
          className="text-[#0ea5e9]">{companyName}?</span>
        </h1>
        <p className="text-gray-500">{companyName} es una plataforma que permite a los usuarios gestionar su
          conexión a
          Internet de manera sencilla y eficiente. Desde un solo lugar, puedes controlar el uso de tu red, revisar
          detalles de tu plan, gestionar facturas, realizar pagos y recibir asistencia técnica personalizada para
          garantizar una experiencia de navegación óptima.</p>
      </div>
      <div data-aos="fade-up"
           className="flex flex-col md:flex-row justify-center space-y-5 md:space-y-0 md:space-x-6 lg:space-x-10 mt-7">
        <div className="relative md:w-5/12">
          <img className="rounded-2xl" src={rectangle19} alt="rectangle19"/>
          <div className="absolute bg-black bg-opacity-20 bottom-0 left-0 right-0 w-full h-full rounded-2xl">
            <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
              <h1 className="uppercase text-white font-bold text-center text-sm lg:text-xl mb-3">Para Hogares | Para
                Empresas</h1>
            </div>
          </div>
        </div>
        <div className="relative md:w-5/12">
          <img className="rounded-2xl" src={rectangle21} alt="rectangle21"/>
          <div className="absolute bg-black bg-opacity-20 bottom-0 left-0 right-0 w-full h-full rounded-2xl">
            <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
              <h1 className="uppercase text-white font-bold text-center text-sm lg:text-xl mb-3">Para tu familia</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
