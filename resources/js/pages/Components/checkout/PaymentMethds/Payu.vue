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
import { inject } from 'vue';

export default {
  name: 'PaymentMethod',
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
  setup() {
    const config = inject('config');
    return { config };
  },
  methods: {
    async handleClick() {
      this.$swal({
        title: `¿Quieres proceder con el pago usando ${this.method.payment_code}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            const response = await this.getSignature();
            this.createFormAndSubmit(response.signature);
          } catch (error) {
            console.error('Error al obtener la firma:', error);
            this.$swal('Error', 'No se pudo obtener la firma.', 'error');
          }
        }
      });
    },
    async getSignature() {
      const {data} = await axios.post('/payment/payu/signature', {
        reference: this.invoice.invoice.increment_id,
        amount: this.invoice.invoice.amount
      });
      return data;
    },
    createFormAndSubmit(signature) {
      const form = document.createElement('form');
      form.action = this.method.action_url;
      form.method = 'POST';
      const invoice = this.invoice.invoice;
      const fields = {
        merchantId: this.method.merchant_id,
        referenceCode: invoice.increment_id,
        accountId: this.method.account_id,
        description: "Pago de factura",
        currency: this.config.currency,
        amount: invoice.total,
        tax: invoice.tax,
        taxReturnBase: invoice.subtotal,
        signature: signature,
        buyerEmail: invoice.customer.email_address,
        telephone:invoice.customer.phone_number,
        buyerFullName:invoice.customer_name,
        payerEmail: invoice.customer.email_address,
        payerPhone:invoice.customer.phone_number,
        payerFullName:invoice.customer_name,
        payerDocument:invoice.customer.identity_document,
        payerDocumentType:invoice.customer.document_type,
        test:'1',
        responseUrl: this.method.url_response,
        confirmationUrl: this.method.url_confirmation,
      };

      for (const key in fields) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = fields[key];
        form.appendChild(input);
      }

      document.body.appendChild(form);
      form.submit();
    }
  }
};
</script>
