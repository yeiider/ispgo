import {clsx, type ClassValue} from "clsx"
import {twMerge} from "tailwind-merge"
import React from "react";

type setDataByObject<TForm> = (data: TForm) => void;
type setDataByMethod<TForm> = (data: (previousData: TForm) => TForm) => void;
type setDataByKeyValuePair<TForm> = <K extends keyof TForm>(key: K, value: TForm[K]) => void;

type setDataType<TForm> = setDataByObject<TForm> & setDataByMethod<TForm> & setDataByKeyValuePair<TForm>;

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

/**
 * Function to get the first two letters from a string.
 * If the string contains multiple words, it returns initials.
 *
 * @param {string} str - The input string.
 * @returns {string} The first two letters or initials.
 */
export function getInitials(str: string): string {
  const words = str.trim().split(' ');

  if (words.length === 1) {
    return str.slice(0, 2);
  } else {
    // Extract initials from each word
    return words.map(word => word[0]).join('').slice(0, 2);
  }
}


export function handleInput(e: React.FormEvent<HTMLInputElement>, data: any, setData: setDataType<any>): void {
  const target = e.target as HTMLInputElement;
  const value = target.value;
  setData({
    ...data,
    [target.name]: value
  })
}


export function priceFormat(price: string, {locale = 'en-US', currency = 'USD'} = {}) {

  /*return price.toLocaleString(locale, {
    style: 'currency',
    currency
  })*/
  const formatter = new Intl.NumberFormat(locale, {style: 'currency', currency});
  return formatter.format(parseFloat(price));
}
