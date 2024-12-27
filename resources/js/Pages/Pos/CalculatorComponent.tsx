import {useState} from "react";
import {Parser} from 'expr-eval';

export default function CalculatorComponent() {
  const [currentInput, setCurrentInput] = useState<string>('0')

  const clear = () => {
    setCurrentInput('0')
  }

  const append = (value: string) => {
    setCurrentInput((prevState) => {
      if (prevState == '0' || prevState == 'Error') {
        return value;
      }
      return (
        prevState + value
      )
    });
  }

  const calculate = () => {
    try {
      const parser = new Parser();
      const result = parser.evaluate(currentInput);
      setCurrentInput(result.toString());
    } catch (e) {
      console.error(e)
      setCurrentInput('Error');
    }
  }

  return (
    <div className="flex">
      <div className="bg-white w-full">
        <div className="text-right text-3xl p-2 mb-3 border border-gray-300 rounded-lg">
          {currentInput || '0'}
        </div>
        <div className="grid grid-cols-4 mb-3 gap-2">
          <button onClick={clear} className="col-span-2 p-4 bg-red-500 text-white rounded-lg">AC</button>
          <button onClick={() => append('%')} className="p-4 bg-gray-200 rounded-lg">%</button>
          <button onClick={() => append('/')} className="p-4 bg-[#0ea5e9] text-white rounded">/</button>

          <button onClick={() => append('7')} className="p-4 bg-gray-200 rounded-lg">7</button>
          <button onClick={() => append('8')} className="p-4 bg-gray-200 rounded-lg">8</button>
          <button onClick={() => append('9')} className="p-4 bg-gray-200 rounded-lg">9</button>
          <button onClick={() => append('*')} className="p-4 bg-[#0ea5e9] text-white rounded-lg">*</button>

          <button onClick={() => append('4')} className="p-4 bg-gray-200 rounded-lg">4</button>
          <button onClick={() => append('5')} className="p-4 bg-gray-200 rounded-lg">5</button>
          <button onClick={() => append('6')} className="p-4 bg-gray-200 rounded-lg">6</button>
          <button onClick={() => append('-')} className="p-4 bg-[#0ea5e9] text-white rounded-lg">-</button>

          <button onClick={() => append('1')} className="p-4 bg-gray-200 rounded-lg">1</button>
          <button onClick={() => append('2')} className="p-4 bg-gray-200 rounded-lg">2</button>
          <button onClick={() => append('3')} className="p-4 bg-gray-200 rounded-lg">3</button>
          <button onClick={() => append('+')} className="p-4 bg-[#0ea5e9] text-white rounded-lg">+</button>

          <button onClick={() => append('0')} className="col-span-2 p-4 bg-gray-200 rounded-lg">0</button>
          <button onClick={() => append('.')} className="p-4 bg-gray-200 rounded-lg">.</button>
          <button onClick={calculate} className="p-4 bg-green-500 text-white rounded-lg">=</button>
        </div>
      </div>
    </div>
  )
}
