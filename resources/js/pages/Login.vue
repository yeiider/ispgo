<template>
  <LayoutAuth title="Sign in">
    <form class="space-y-5 w-full">
      <div class="w-full h-auto">
        <div class="flex relative flex-col justify-center h-11">
          <div class="flex relative">
            <label for="email" @click=""
                   :class="{ 'top-0 -translate-y-1 ml-2 text-xs auth-component-input-label-focused' : focusedOrFilled, 'top-[16px] ml-2.5 text-[15px] text-gray-500' : !focusedOrFilled }"
                   class="block absolute px-1.5 py-0 font-normal leading-normal bg-white duration-300 ease-out cursor-text auth-component-input dark:text-gray-300 top-[16px] ml-2.5 text-[15px] text-gray-500">
              Email Address
            </label>


            <div data-model="email" class="mt-1.5 w-full rounded-md shadow-sm auth-component-input-container">
              <input data-auth="email-input" required="required"
                     id="email" name="" type="email"
                     @focus="focused()"
                     @blur="blurred()"
                     class="auth-component-input appearance-none flex w-full h-11 px-3.5 text-sm bg-white border rounded-md border-gray-300 ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-1 focus:ring-zinc-800 disabled:cursor-not-allowed disabled:opacity-50 ">
            </div>
          </div>
        </div>
      </div>
      <button type="submit"
              class="auth-component-button px-4 py-2.5 text-sm font-medium rounded-md opacity-[95%] hover:opacity-100 focus:ring-2 focus:ring-offset-2 cursor-pointer inline-flex items-center w-full justify-center disabled:opacity-50 font-semibold focus:outline-none"
              style=" color:#ffffff; background-color:#0ea5e9;"
      >
        Continue
      </button>
    </form>
  </LayoutAuth>
</template>

<script>
import {onMounted, ref} from "vue";
import LayoutAuth from "./Components/Customer/Auth/Layout.vue";

export default {
  components: {
    LayoutAuth
  },

  setup() {
    const focusedOrFilled = ref(false);

    onMounted(async () => {
      console.log("Hola  mundo")
    })

    const focused = () => {

    }

    const blurred = () => {

    }

    return {
      focusedOrFilled,
      focused,
      blurred,
    }
  }
}
</script>

<style scoped>

</style>
