<template>
  <div class="space-y-2 md:flex @md/modal:flex md:flex-row @md/modal:flex-row md:space-y-0 @md/modal:space-y-0 py-5">
    <div class="w-full px-6 md:mt-2 @md/modal:mt-2 md:px-8 @md/modal:px-8 md:w-1/5 @md/modal:w-1/5">
      <label :for="field.uniqueKey" class="inline-block leading-tight space-x-1">
        <span>{{ field.name }}</span>
        <span v-if="field.required" class="text-red-500 text-sm">*</span>
      </label>
    </div>
    <div class="w-full space-y-2 px-6 md:px-8 @md/modal:px-8 md:w-3/5 @md/modal:w-3/5 flex">
      <ckeditor :editor="editor" :config="editorConfig" :name="field.attribute" @input="updateValue($event.target.checked)"></ckeditor>

    </div>
  </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import {
  ClassicEditor,
  AccessibilityHelp,
  Autoformat,
  AutoLink,
  Autosave,
  Bold,
  Code,
  CodeBlock,
  Essentials,
  GeneralHtmlSupport,
  Heading,
  HtmlEmbed,
  Italic,
  Link,
  Paragraph,
  SelectAll,
  ShowBlocks,
  Table,
  TableCaption,
  TableCellProperties,
  TableColumnResize,
  TableProperties,
  TableToolbar,
  TextPartLanguage,
  TextTransformation,
  Undo,
  FileRepository,
  Image, ImageCaption, ImageStyle, ImageToolbar, ImageUpload,
  CKFinderUploadAdapter,
  MediaEmbed
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

export default {
  name: "Ckeditor",
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
        plugins: [
          ClassicEditor,
          AccessibilityHelp,
          Autoformat,
          AutoLink,
          Autosave,
          Bold,
          Code,
          CodeBlock,
          Essentials,
          GeneralHtmlSupport,
          Heading,
          HtmlEmbed,
          Italic,
          Link,
          Paragraph,
          SelectAll,
          ShowBlocks,
          Table,
          TableCaption,
          TableCellProperties,
          TableColumnResize,
          TableProperties,
          TableToolbar,
          TextPartLanguage,
          TextTransformation,
          Undo,
          FileRepository,
          Image,
          ImageCaption,
          ImageStyle,
          ImageToolbar,
          ImageUpload,
          CKFinderUploadAdapter,
          MediaEmbed
        ],
        toolbar: {
          items: [
            'undo',
            'redo',
            '|',
            'showBlocks',
            'selectAll',
            '|',
            'heading',
            '|',
            'bold',
            'italic',
            'code',
            '|',
            'link',
            'insertImage',
            'insertTable',
            'codeBlock',
            'htmlEmbed',
            '|',
            'accessibilityHelp'
          ],
          shouldNotGroupWhenFull: true
        },
      }
    };
  },
  methods: {
    updateValue(value) {
      this.$emit('input', {key: this.field.uniqueKey, value});
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
