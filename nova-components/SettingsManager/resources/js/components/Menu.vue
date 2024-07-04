<template>
    <card>
        <div class="mb-6 ap-dropdown-menu">
            <div v-for="(item, index) in menu" :key="index" class="mb-2">
                <a :href="`${baseUrl}/section/${item.code}`"
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
export default {
    props: {
        menu: Array,  // Cambiar a Array ya que menu es una lista
        section: String,
    },
    data() {
        return {
            baseUrl: ''
        }
    },
    mounted() {
        let path = window.location.pathname;
        if (this.section) {
            this.baseUrl = path.replace(/\/section\/[^\/]+$/, '');
        } else {
            this.baseUrl = path;
        }
    }
}
</script>



