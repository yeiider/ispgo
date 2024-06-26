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
                <button type="submit" class="btn btn-default btn-primary">Save</button>
            </form>
        </card>

        <card class="mb-6">
            <div v-for="setting in settings" :key="setting.id" class="mb-4">
                <p><strong>{{ setting.section }} / {{ setting.group }} / {{ setting.key }}:</strong> {{ setting.value }}
                </p>
            </div>
        </card>
    </div>
</template>


<script>
import CustomTextField from './components/CustomTextField.vue';
import CustomTextareaField from './components/CustomTextareaField.vue';

export default {
    components: {CustomTextField, CustomTextareaField},

    data() {
        return {
            newSetting: {
                section: '',
                group: '',
                key: '',
                value: '',
            },
            settings: [],
        };
    },
    mounted() {
        this.fetchSettings();
    },
    methods: {
        fetchSettings() {
            Nova.request().get('/settings-manager/settings').then(response => {
                this.settings = response.data;
            });
        },
        saveSetting() {
            Nova.request().post('/settings-manager/settings', this.newSetting).then(response => {
                this.fetchSettings();
                this.newSetting = {
                    section: '',
                    group: '',
                    key: '',
                    value: '',
                };
            });
        },
    },
};
</script>
