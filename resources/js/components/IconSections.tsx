export default function IconSections() {
  return (

    <div className="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
      <div data-aos="flip-up" className="max-w-xl mx-auto text-center mt-24">
        <h1 className="font-bold text-3xl lg:text-4xl text-gray-800 dark:text-neutral-200">Servicio Integral <span
          className="text-[#0ea5e9]">de Conexión a Internet</span>
        </h1>
        <p className="leading-relaxed text-gray-600 dark:text-neutral-400">
          Nuestro servicio de Internet es la solución completa que ofrece todo lo necesario para mantener tu hogar o
          negocio conectado de manera rápida y confiable.
        </p>
      </div>

      <div className="grid sm:grid-cols-2 lg:grid-cols-3 items-center gap-2 mt-5">

        {/* Icon Block */}
        <a
          className="group flex flex-col justify-center hover:bg-gray-50 focus:outline-none focus:bg-gray-50 rounded-xl p-4 md:p-7 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
          href="#">
          <div className="flex justify-center items-center size-12 bg-[#0ea5e9] rounded-xl">
            <svg className="shrink-0 size-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                 strokeLinejoin="round">
              <path d="M20 7h-9"/>
              <path d="M14 17H5"/>
              <circle cx="17" cy="17" r="3"/>
              <circle cx="7" cy="7" r="3"/>
            </svg>
          </div>
          <div className="mt-5">
            <h3
              className="group-hover:text-gray-600 text-lg font-semibold text-gray-800 dark:text-white dark:group-hover:text-gray-400">Instalación
              rápida</h3>
            <p className="mt-1 text-gray-600 dark:text-neutral-400">Disfruta de una instalación rápida y eficiente de tu
              servicio de Internet, sin complicaciones.</p>
          </div>
        </a>
        {/* End Icon Block */}

        {/* Icon Block */}
        <a
          className="group flex flex-col justify-center hover:bg-gray-50 focus:outline-none focus:bg-gray-50 rounded-xl p-4 md:p-7 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
          href="#">
          <div className="flex justify-center items-center size-12 bg-[#0ea5e9] rounded-xl">
            <svg className="shrink-0 size-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                 strokeLinejoin="round">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
          </div>
          <div className="mt-5">
            <h3
              className="group-hover:text-gray-600 text-lg font-semibold text-gray-800 dark:text-white dark:group-hover:text-gray-400">Facturación
              en línea y gestión de contratos</h3>
            <p className="mt-1 text-gray-600 dark:text-neutral-400">Gestiona de manera fácil y segura todas las
              transacciones financieras y contractuales de tu servicio de Internet.</p>
          </div>
        </a>
        {/* End Icon Block */}

        {/* Icon Block */}
        <a
          className="group flex flex-col justify-center hover:bg-gray-50 focus:outline-none focus:bg-gray-50 rounded-xl p-4 md:p-7 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
          href="#">
          <div className="flex justify-center items-center size-12 bg-[#0ea5e9] rounded-xl">
            <svg className="shrink-0 size-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                 strokeLinejoin="round">
              <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"/>
              <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>
            </svg>
          </div>
          <div className="mt-5">
            <h3
              className="group-hover:text-gray-600 text-lg font-semibold text-gray-800 dark:text-white dark:group-hover:text-gray-400">Soporte
              24/7</h3>
            <p className="mt-1 text-gray-600 dark:text-neutral-400">Ofrecemos atención personalizada y soluciones
              adaptadas a las necesidades específicas de cada cliente.</p>
          </div>
        </a>
        {/* End Icon Block */}
      </div>
    </div>

  )
}
