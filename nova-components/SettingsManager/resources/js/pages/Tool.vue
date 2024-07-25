<template>
  <div class="relative" dusk="settings-index-component">
    <heading class="mb-6">Settings Manager</heading>
    <div class="flex justify-evenly items-start">
      <Menu class="w-[20%]"
            :menu="settingMenu"
            :section="section"
      />
      <form @submit.prevent="saveSetting" class="w-[75%]">

        <template v-for="(group, groupIndex) in groups" :key="groupIndex">
          <card class="mb-1">
            <Collapsible :title="group.label" :isDefaultOpen="groupIndex === 0">
              <DefaultField
                :fields="group.fields"
                :section="section"
                @update-field="updateFieldValue"
              />
            </Collapsible>
          </card>
        </template>

        <div
          class="flex flex-col md:flex-row md:items-center justify-center md:justify-end space-y-2 md:space-y-0 md:space-x-3 mt-2 pt-2">
          <button type="button"
                  class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center bg-transparent border-transparent h-9 px-3 text-gray-600 dark:text-gray-400 hover:bg-gray-700/5 dark:hover:bg-gray-950"
                  @click="cancelUpdate" dusk="cancel-update-button">
            <span class="flex items-center gap-1">Cancel</span>
          </button>
          <button type="button"
                  class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:bg-primary-400 hover:border-primary-400 text-white dark:text-gray-900"
                  @click="saveSetting(true)" dusk="update-and-continue-editing-button">
            <span class="flex items-center gap-1">Update &amp; Continue Editing</span>
          </button>
          <button type="submit"
                  class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:bg-primary-400 hover:border-primary-400 text-white dark:text-gray-900"
                  dusk="update-button">
            <span class="flex items-center gap-1">Update Setting</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>

import Menu from "../components/Menu.vue";
import Collapsible from "../components/Collapsible.vue"
import DefaultField from "../components/fields/DefaultField.vue";
import {data} from "autoprefixer";

export default {
  props: {
    section: {
      type: String,
      required: false
    }
  },
  components: {
    Menu,
    Collapsible,
    DefaultField
  },
  data() {
    return {
      fields: [],
      groups: [],
      settingMenu: [],
      isOpen: []
    };
  },
  mounted() {
    this.fetchSettings();
  },
  methods: {
    fetchSettings() {
      let url = '/settings-manager/settings';
      if (this.section) {
        url = `${url}?section=${this.section}`;
      }

      Nova.request().get(url).then(response => {
        this.settingMenu = response.data.settingMenu;
        if (response.data && "groups" in response.data && response.data.groups.length) {
          this.groups = response.data.groups;
        }
      });
    },
    updateFieldValue({key, value}) {
      this.groups.forEach(group => {
        const fieldToUpdate = group.fields.find(field => field.uniqueKey === key);
        if (fieldToUpdate) {
          fieldToUpdate.value = value;
        }
      });

      let _fields = [];
      this.groups.forEach(group => {
        group.fields.forEach(field => {
          _fields.push(field);
        })
      });
      this.fields = _fields;
    },


    saveSetting(continueEditing = false) {
      Nova.request().post('/settings-manager/settings/save', {
        fields: this.fields,
        section: this.section ?? 'general'
      }).then(response => {
        this.fetchSettings();
        if (response.data.success) {
          Nova.success(response.data.message);
        } else {
          Nova.error('It failed!')
        }
        if (!continueEditing) {
          // Lógica para salir del modo de edición si no se desea continuar editando.
        }
      });
    },
    cancelUpdate() {
      // Lógica para cancelar la actualización y restaurar los valores originales si es necesario.
    }
  }
};
</script>
