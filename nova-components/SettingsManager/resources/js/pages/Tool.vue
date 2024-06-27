<template>
    <div class="relative" dusk="settings-index-component">


        <heading class="mb-6">Settings Manager</heading>

        <div class="flex justify-evenly">
            <Menu class="w-[20%]"
                  :menu="menu"
                  :section="section"
            />

            <card class="mb-6 w-[75%]">
                <div class="mb-4 " v-for="(section, index) in settings" :key="index">
                    <custom-text-field
                        :field="{
                            attribute: index,
                            name: section.label,
                            placeholder: section.placeholder,
                            required: true
                        }"
                    ></custom-text-field>
                </div>
            </card>
        </div>


        <div
            class="flex flex-col md:flex-row md:items-center justify-center md:justify-end space-y-2 md:space-y-0 md:space-x-3">
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
    </div>

</template>


<script>
import CustomTextField from './components/CustomTextField.vue';
import CustomTextareaField from './components/CustomTextareaField.vue';
import Menu from "./components/Menu.vue";

export default {
    props: {
        section: ''
    },
    components: {
        Menu,
        CustomTextField,
        CustomTextareaField
    },
    data() {
        return {
            newSetting: {
                section: '',
                group: '',
                key: '',
                scope: '',
                value: ''
            },
            settings: [],
            menu: [],
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
                url = '/settings-manager/settings?section=' + this.section;
            }
            Nova.request().get(url).then(response => {
                this.settings = response.data.sectionsConfig;
                delete this.settings.setting
                this.menu = response.data.menu;
                this.isOpen = Array(this.menu.length).fill(false)
            });
        },
        toggleSection(index) {
            this.$set(this.isOpen, index, !this.isOpen[index]);
        },
        saveSetting(continueEditing = false) {
            let url = '/settings-manager/settings';
            if (this.section) {
                url = '/settings-manager/settings?section=' + this.section;
            }
            Nova.request().post(url, this.newSetting).then(response => {
                this.fetchSettings();
                if (!continueEditing) {
                    this.newSetting = {
                        section: '',
                        group: '',
                        key: '',
                        scope: '',
                        value: ''
                    };
                }
            });
        },
        cancelUpdate() {
            this.newSetting = {
                section: '',
                group: '',
                key: '',
                scope: '',
                value: ''
            };
        }
    }
};
</script>
