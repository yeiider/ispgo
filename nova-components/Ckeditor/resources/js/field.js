Nova.booting((Vue, router, store) => {
    Vue.component('index-ckeditor', require('./components/IndexField').default);
    Vue.component('detail-ckeditor', require('./components/DetailField').default);
    Vue.component('form-ckeditor', require('./components/FormField').default);
});
