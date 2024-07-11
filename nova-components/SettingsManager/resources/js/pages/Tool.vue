<template>
  <div class="relative" dusk="settings-index-component">
    <heading class="mb-6">Settings Manager</heading>
    <div class="flex justify-evenly">
      <Menu class="w-[20%]"
            :menu="settingMenu"
            :section="section"
      />
      <form @submit.prevent="saveSetting" class="w-[75%]">

        <card>

          <!--<template v-for="(field, fieldIndex) in fields" :key="field.uniqueKey">
            <component
              :is="getFieldComponent(field.component)"
              :field="field"
              :fieldname="section"
              @input="updateFieldValue(field.group, field.attribute, $event)"
            ></component>
          </template> -->

          <template v-for="(group, groupIndex) in groups" :key="groupIndex">
            <Collapsible :title="group.label">
              <DefaultField :fields="group.fields" />
            </Collapsible>
          </template>

        </card>
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
import CustomTextField from '../components/CustomTextField.vue';
import CustomBooleanField from '../components/CustomBooleanField.vue';
import CustomSelectField from '../components/CustomSelectField.vue';
import CustomTextareaField from '../components/CustomTextareaField.vue';
import Menu from "../components/Menu.vue";
import Collapsible from "../components/Collapsible.vue"
import DefaultField from "../components/fields/DefaultField.vue";


export default {
  props: {
    section: {
      type: String,
      required: false
    }
  },
  components: {
    Menu,
    CustomTextField,
    CustomTextareaField,
    CustomSelectField,
    CustomBooleanField,
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
    getFieldComponent(fieldType) {
      switch (fieldType) {
        case 'boolean-field':
          return 'CustomBooleanField';
        case 'text-field':
          return 'CustomTextField';
        case 'textarea-field':
          return 'CustomTextareaField';
        case 'select-field':
          return 'CustomSelectField';
        default:
          return 'CustomTextField';
      }
    },
    fetchSettings() {
      let url = '/settings-manager/settings';
      if (this.section) {
        url = `${url}?section=${this.section}`;
      }

      Nova.request().get(url).then(response => {
        this.fields = response.data.fields;
        this.settingMenu = response.data.settingMenu;
        if (response.data && "groups" in response.data && response.data.groups.length) {
          this.groups =  response.data.groups;
          console.log(this.groups)
        }
      });
    },
    updateFieldValue(group, attribute, value) {

      const field = this.fields.find(f => f.group === group && f.attribute === attribute);
      if (field) {
        field.value = value;
      }
      console.log(`Group: ${group}, Attribute: ${attribute}, Value: ${value}`);
    },
    saveSetting(continueEditing = false) {
      Nova.request().post('/settings-manager/settings/save', {
        fields: this.fields,
        section: this.section ?? 'general'
      }).then(response => {
        this.fetchSettings();
        if (response.success) {
          alert(response.message)
        } else {
          alert(response.message)
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
