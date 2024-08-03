import http from './http';
import forms from './forms'
import notifys from '@/utils/notifys';
class Page {

    constructor(data, emit) {
        this.data = data
        this.emit = emit
    }

    ui = (mode = null) => {
        switch (mode) {
            case 'register':
                this.data.value.uiview.search = false
                this.data.value.uiview.register = !this.data.value.uiview.register
                this.data.value.data = {}
                break;
            case 'update':
                this.data.value.uiview.search = false
                this.data.value.uiview.register = true
                break;
            case 'search':
                this.data.value.uiview.search = !this.data.value.uiview.search
                this.data.value.uiview.register = false
                break;
            case 'list':
                this.data.value.uiview.register = false
                break;
            default:
                this.data.value.uiview.search = false
                this.data.value.uiview.register = false
                break;
        }
    }
    

    save = () => {
        const validation = forms.checkform(this.data.value.data, this.data.value.rules);
        if (!validation.isvalid) {
            this.emit('callAlert', notifys.warning(validation.message))
            return
        }


        http.post(`${this.data.value.baseURL}/save`, this.data.value.data, this.emit, () => {
            this.list();
        })
    }

    update = (id) => {

        http.get(`${this.data.value.baseURL}/details/${id}`, this.emit, (response) => {
            this.data.value.data = response.data
            this.ui('update')
        })
    }

    remove = (id) => {
        this.emit('callRemove', {
            id: id,
            url: this.data.value.baseURL,
            search: this.data.value.search,
        })
    }

    fastremove = (id) => {
        http.destroy(`${this.data.value.baseURL}/fastdestroy`, { id }, this.emit, (resp) => {
            if (http.success(resp)) {
                this.list()
            }
        })
    }

    list = () => {
        this.data.value.search.sent = true
        http.post(`${this.data.value.baseURL}/list`, this.data.value.search, this.emit, (response) => {
            this.data.value.datalist = response.data ?? []
            this.ui('list')
        })
    }

    selects = (key = null, search = null) => {

        const urlselect = (key && search) ? `${this.data.value.baseURL}/selects/${key}/${search}` : `${this.data.value.baseURL}/selects`

        http.get(urlselect, this.emit, (response) => {
            this.data.value.selects = response.data
        })
    }

    download = (id, name = 'Documento') => {
        http.download(`${this.data.value.baseURL}/download/${id}`, this.emit, (response) => {
            if (response.headers['content-type'] !== 'application/pdf') {
                this.emit('callAlert', notifys.warning('Arquivo Indisponível'))
                return
            }
            const url = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
            const link = document.createElement('a')
            link.href = url;
            link.download = `${name}-${id}.pdf`
            document.body.appendChild(link)
            link.click()
            window.URL.revokeObjectURL(url);
        })
    }
}

export default Page