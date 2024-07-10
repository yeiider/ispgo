<template>
    <DefaultField :field="currentField" :errors="errors">
        <template #field>
            <div>
                <label v-if="field.label" :for="field.name" class="field-label">{{ field.label }}</label>
                <ckeditor :editor="editor" v-model="value" :config="editorConfig" @input="handleChange"></ckeditor>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {DependentFormField, HandlesValidationErrors} from 'laravel-nova';

export default {
    mixins: [DependentFormField, HandlesValidationErrors],
    components: {
        'ckeditor': CKEditor.component
    },
    props: {
        resourceName: String,
        resourceId: [String, Number],
        field: Object,
    },
    data() {
        return {
            value: this.field.value || '',
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.MyCustomUploadAdapterPlugin],
                // Otros ajustes de configuración
            }
        };
    },
    methods: {
        handleChange(event) {
            this.value = event;
            this.$emit('input', this.value);
        },
        fill(formData) {
            this.fillIfVisible(formData, this.field.attribute, this.value || '');
        },
        MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return {
                    upload: () => {
                        return loader.file.then(file => {
                            return new Promise((resolve, reject) => {
                                const data = new FormData();
                                data.append('upload', file);

                                Nova.request().post('/ckeditor/upload', data, {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }).then(response => {
                                    if (!response.data || response.data.error) {
                                        return reject(response.data && response.data.error ? response.data.error.message : 'Error al subir la imagen');
                                    }
                                    resolve({
                                        default: response.data.url
                                    });
                                }).catch(error => {
                                    reject(error);
                                });
                            });
                        });
                    },
                    abort: () => {
                        // Implement abort if needed
                    }
                };
            };
        }
    },
    watch: {
        value(newValue) {
            this.$emit('change', newValue);
        }
    }
}
</script>

<style scoped>
.ck-editor__editable_inline {
    min-height: 200px;
}
</style>
