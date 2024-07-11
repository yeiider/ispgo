import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

export default class Variable extends Plugin {
    init() {
        const editor = this.editor;

        editor.ui.componentFactory.add('variable', locale => {
            const view = new ButtonView(locale);

            view.set({
                label: 'Insert Variable',
                withText: true
            });

            view.on('execute', () => {
                const variable = prompt('Variable name');
                if (variable) {
                    editor.model.change(writer => {
                        editor.model.insertContent(writer.createText(`{{ ${variable} }}`));
                    });
                }
            });

            return view;
        });
    }
}
