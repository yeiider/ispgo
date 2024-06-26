<template>
    <div>
        <heading class="mb-6">Settings Manager</heading>

        <card class="mb-6">
            <form @submit.prevent="saveSetting">
                <div class="mb-4">
                    <custom-text-field
                        v-model="newSetting.section"
                        :field="{
              attribute: 'section',
              name: 'Section',
              placeholder: 'Enter section',
              required: true
            }"
                    ></custom-text-field>
                </div>
                <div class="mb-4">
                    <custom-text-field
                        v-model="newSetting.group"
                        :field="{
              attribute: 'group',
              name: 'Group',
              placeholder: 'Enter group',
              required: true
            }"
                    ></custom-text-field>
                </div>
                <div class="mb-4">
                    <custom-text-field
                        v-model="newSetting.key"
                        :field="{
              attribute: 'key',
              name: 'Key',
              placeholder: 'Enter key',
              required: true
            }"
                    ></custom-text-field>
                </div>
                <div class="mb-4">
                    <custom-text-field
                        v-model="newSetting.scope"
                        :field="{
              attribute: 'scope',
              name: 'Scope',
              placeholder: 'Enter scope',
              required: true
            }"
                    ></custom-text-field>
                </div>
                <div class="mb-4">
                    <custom-textarea-field
                        v-model="newSetting.value"
                        :field="{
              attribute: 'value',
              name: 'Value',
              placeholder: 'Enter value',
              required: true
            }"
                    ></custom-textarea-field>
                </div>
                <div class="flex flex-col md:flex-row md:items-center justify-center md:justify-end space-y-2 md:space-y-0 md:space-x-3">
                    <button type="button" class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center bg-transparent border-transparent h-9 px-3 text-gray-600 dark:text-gray-400 hover:bg-gray-700/5 dark:hover:bg-gray-950" @click="cancelUpdate" dusk="cancel-update-button">
                        <span class="flex items-center gap-1">Cancel</span>
                    </button>
                    <button type="button" class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:bg-primary-400 hover:border-primary-400 text-white dark:text-gray-900" @click="saveSetting(true)" dusk="update-and-continue-editing-button">
                        <span class="flex items-center gap-1">Update &amp; Continue Editing</span>
                    </button>
                    <button type="submit" class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:bg-primary-400 hover:border-primary-400 text-white dark:text-gray-900" dusk="update-button">
                        <span class="flex items-center gap-1">Update Setting</span>
                    </button>
                </div>
            </form>
        </card>

        <card class="mb-6">
            <div v-for="setting in settings" :key="setting.id" class="mb-4">
                <p><strong>{{ setting.section }} / {{ setting.group }} / {{ setting.key }}:</strong> {{ setting.value }}</p>
            </div>
        </card>
    </div>
</template>


<script>
import CustomTextField from './components/CustomTextField.vue';
import CustomTextareaField from './components/CustomTextareaField.vue';

export default {
    components: {
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
            settings: []
        };
    },
    mounted() {
        this.fetchSettings();
    },
    methods: {
        fetchSettings() {
            Nova.request().get('/nova-vendor/nova-settings-manager/settings').then(response => {
                this.settings = response.data;
            });
        },
        saveSetting(continueEditing = false) {
            Nova.request().post('/nova-vendor/nova-settings-manager/settings', this.newSetting).then(response => {
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
