import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion"

export default function FAQ() {
  const frequentlyAskedQuestions = [
    {
      id: 1,
      question: 'How can I get started?',
      answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at risus quis elit maximus venenatis. Sed auctor, elit vel condimentum congue, elit elit maximus risus, eget maximus ex risus sed leo.'
    },
    {
      id: 2,
      question: 'How can I get started?',
      answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at risus quis elit maximus venenatis. Sed auctor, elit vel condimentum congue, elit elit maximus risus, eget maximus ex risus sed leo.'
    },
    {
      id: 3,
      question: 'How can I get started?',
      answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at risus quis elit maximus venenatis. Sed auctor, elit vel condimentum congue, elit elit maximus risus, eget maximus ex risus sed leo.'
    },
    {
      id: 4,
      question: 'How can I get',
      answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at risus quis elit maximus venenatis. Sed auctor, elit vel condimentum congue, elit elit maximus risus, eget maximus ex risus sed leo.'
    },
  ]

  return (
    <div className="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
      {/* Title */}
      <div className="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h2 className="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Sus preguntas, respondidas</h2>
        <p className="mt-1 text-gray-600 dark:text-neutral-400">Respuestas a las preguntas más frecuentes.</p>
      </div>
      {/* End Title */}

      <div className="max-w-2xl mx-auto">
        <Accordion type="single" collapsible>
          {frequentlyAskedQuestions.map((item) => (
            <AccordionItem key={item.id} value={`item-${item.id}`}>
              <AccordionTrigger>{item.question}</AccordionTrigger>
              <AccordionContent>
                {item.answer}
              </AccordionContent>
            </AccordionItem>
          ))}
        </Accordion>
      </div>
    </div>
  )
}
