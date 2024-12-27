<template>
  <card>
    <div class="mb-6 ap-dropdown-menu">
      <div v-for="(item, index) in menu" :key="index" class="mb-2">
        <a :href="computedHref(item.code)"
           :class="[
             'w-full flex items-start p-[15px] rounded text-left text-gray-500 dark:text-gray-500 focus:outline-none focus:ring focus:ring-primary-200 dark:focus:ring-gray-600 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 font-bold dark:text-primary-500',
             section === item.code ? 'bg-gray-200 dark:bg-gray-800 ' : ''
           ]">
          <span>
            {{ item.label }}
          </span>
        </a>
      </div>
    </div>
  </card>
</template>

<script>
import {usePage} from "@inertiajs/vue3";

export default {
  props: {
    menu: {
      type: Array,
      default: () => []
    },
    section: {
      type: String,
      default: ''
    },
    scope: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      baseUrl: ''
    }
  },
  mounted() {
    const path = window.location.pathname;

    this.baseUrl = path
      .replace(/\/scope\/[^\/]+/, '')
      .replace(/\/section\/[^\/]+/, '');
  },
  methods: {
    computedHref(sectionCode) {
      let url = this.baseUrl;
      let scope = this.scope;

      if (typeof this.scope === 'undefined' || this.scope === '') {
        scope = 1;
      }

      url += `/scope/${encodeURIComponent(scope)}`;

      if (sectionCode) {
        // Agrega section al final del enlace
        url += `/section/${encodeURIComponent(sectionCode)}`;
      }

      return url; // Devolvemos la URL construida
    }
  }
}
</script>
