<template>
  <div class="flex justify-center">
    <a @click.prevent="handleClick"
       class="block border rounded-lg p-4 hover:bg-secondary hover:text-secondary-foreground transition">
      <img :src="method.image" :alt="method.payment_code + ' logo'"/>
    </a>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Wompi',
  props: {
    method: {
      type: Object,
      required: true
    },
    invoice: {
      type: Object,
      required: true
    }
  },
  methods: {
    async handleClick() {
      const confirmation = await this.$swal({
        title: `¿Quieres proceder con el pago usando ${this.method.payment_code}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
      });

      if (confirmation.isConfirmed) {
        try {
          const response = await this.getSignature();
          this.createFormAndSubmit(response.signature);
        } catch (error) {
          console.error('Error al obtener la firma:', error);
          this.$swal('Error', 'No se pudo obtener la firma.', 'error');
        }
      }
    },
    async getSignature() {
      try {
        const { data } = await axios.post('/payment/wompi/signature', {
          reference: this.invoice.invoice.increment_id,
          amount: this.calculateAmountInCents(this.invoice.invoice.total)
        });
        return data;
      } catch (error) {
        console.error('Error al obtener la firma:', error);
        throw new Error('No se pudo obtener la firma.');
      }
    },
    calculateAmountInCents(amount) {
      return parseInt(parseInt(amount).toString() + '0000');
    },
    createFormAndSubmit(signature) {
      const invoice = this.invoice.invoice;
      const checkout = new WidgetCheckout({
        currency: 'COP',
        amountInCents: this.calculateAmountInCents(invoice.total),
        reference: invoice.increment_id,
        publicKey: this.method.public_key,
        signature: { integrity: signature },
        redirectUrl: this.method.confirmation_url, // Opcional

        customerData: { // Opcional
          email: invoice.customer.email_address,
          fullName: invoice.customer_name,
          phoneNumber: invoice.customer.phone_number,
          phoneNumberPrefix: '+57',
          legalId: invoice.customer.identity_document,
          legalIdType: invoice.customer.document_type
        },
        shippingAddress: { // Opcional
          addressLine1: invoice.address.address,
          city: invoice.address.city,
          phoneNumber: invoice.customer.phone_number,
          region: invoice.address.state_province,
          country: invoice.address.country
        }
      });

      checkout.open((result) => {
        const transaction = result.transaction;
        console.log("Transaction ID: ", transaction.id);
        console.log("Transaction object: ", transaction);
      });
    }
  }
};
</script>
