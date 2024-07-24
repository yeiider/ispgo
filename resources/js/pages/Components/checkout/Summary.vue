<template>
  <footer class="fixed  left-0 w-full bottom-0 bg-white shadow-lg border-t-4 border-accent  z-50">
    <div v-if="showInvoice"
         class="max-w-4xl mx-auto bg-card text-card-foreground p-6 rounded-lg shadow-md flex justify-between flex-col sm:flex-row sm:items-center">
      <div>
        <div class=" flex-row sm:hidden text-center mb-4">
          <p class="text-lg font-medium">{{ invoice.invoice.product }}</p>
        </div>
        <div class="flex space-x-4">
          <div class="flex flex-col hidden sm:block">
            <p class="text-muted-foreground">Producto:</p>
            <p class="text-lg font-medium">{{ invoice.invoice.product }}</p>
          </div>
          <div class="flex flex-col sm:pl-5">
            <p class="text-muted-foreground">Impuesto:</p>
            <p class="text-lg font-medium">${{ invoice.invoice.tax }}</p>
          </div>
          <div class="flex flex-col sm:pl-5">
            <p class="text-muted-foreground">SubTotal:</p>
            <p class="text-lg font-medium">${{ invoice.invoice.subtotal }}</p>
          </div>
          <div class="flex flex-col  sm:pl-5">
            <p class="text-muted-foreground">Descuento:</p>
            <p class="text-lg font-medium text-red-500">-${{ invoice.invoice.discount }}</p>
          </div>
        </div>
      </div>
      <div class="sm:border-l mt-5 border-border pl-4">
        <p class="text-muted-foreground">Total a Pagar:</p>
        <p class="text-xl font-bold text-green-600">${{ invoice.invoice.total }}</p>
      </div>
    </div>
  </footer>
</template>


<script>
export default {
  name: "Summary",
  props: {
    invoice: {
      type: Object,
      required: true,
      default: () => ({
        productName: '',
        tax: 0,
        discount: 0,
        total: 0
      })
    },
    showInvoice: {
      type: Boolean,
      required: true,
      default: false,
    }
  }
}
</script>
