import Nova from 'laravel-nova';

class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
        this.url = '/ckeditor/upload'; // Asegúrate de que esta URL sea correcta
    }

    // Comienza el proceso de carga
    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
            this._initListeners(resolve, reject, file);
            this._sendRequest(file);
        }));
    }

    // Aborta el proceso de carga
    abort() {
        if (this.controller) {
            this.controller.abort();
        }
    }

    // Inicializa los listeners del request
    _initListeners(resolve, reject, file) {
        const genericErrorText = `No se pudo cargar el archivo: ${file.name}.`;
        this.controller = new AbortController();

        this.controller.signal.addEventListener('abort', () => reject(genericErrorText));

        this.controller.signal.addEventListener('error', () => reject(genericErrorText));
    }

    // Envía el request usando Nova.request()
    _sendRequest(file) {
        const data = new FormData();
        data.append('upload', file);

        Nova.request().post(this.url, data, {
            signal: this.controller.signal,
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: (evt) => {
                if (evt.lengthComputable) {
                    this.loader.uploadTotal = evt.total;
                    this.loader.uploaded = evt.loaded;
                }
            }
        })
            .then(response => {
                if (!response.data || response.data.error) {
                    throw new Error(response.data.error ? response.data.error.message : 'Error al subir la imagen');
                }

                resolve({
                    default: response.data.url
                });
            })
            .catch(error => {
                reject(error.message);
            });
    }
}

export default MyUploadAdapter;
