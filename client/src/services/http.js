import axsi from './axsi'
import forms from './forms'
import utils from '@/utils/utils'

async function request(options, emit, custom_resp = null, callback_error = null){
    utils.load()

    await axsi.axiosInstanceAuth.request(options).then(response => {
        if(response){
            if(custom_resp && success(response)){ custom_resp(response, emit) }
            default_resp(response, emit)
            return
        }

        emit('callAlert', {show: true, data:{type:'danger', msg: 'Falha ao receber dados...'}})
        
    }).catch((error) => {
        console.log(error.message)
        const resperror = callback_error ?? default_resp
        emit('callAlert', {show: true, data:error?.response?.data?.notify ?? {type:'danger', msg:'Que feio servidor, não faz assim!'}})
        resperror(error?.response, emit)
        
    }).finally(() => {
        utils.load(false)
    })
}

function default_resp(resp, emit){

    const data = resp?.data ?? {}

    //call redirect
    if(data.redirect){
        window.location = data.redirect
    }

     //call notifys
    if(data?.notify){
        emit('callAlert', {show:true, data:data.notify})
    }
}

function post (url, data, emit, resp = null, back = null){
    const options = {
        url: url,
        method: 'POST',
        data : forms.builddata(data)
    }
    
    request(options, emit, resp, back)
}

function put (url, data, emit, resp= null, back = null){
    const options = {
        url: url,
        method: 'PUT',
        data : forms.builddata(data),
        headers:{
            'Content-Type':'application/json',
        }
    }
    
    request(options, emit, resp, back)
}

function get (url, emit, resp = null, back = null){
    const options = {
        method: 'GET',
        url: url,
        headers:{
            'Content-Type':'application/json',
        }
    }

    request(options, emit, resp, back)
}

function download (url, emit, resp = null, back = null){
    const options = {
        method: 'GET',
        url: url,
        responseType: 'blob',
        headers:{
            'Content-Type':'application/json',
        }
    }

    request(options, emit, resp, back)
}

function patch (url, data, emit, resp= null, back = null){
    const options = {
        url: url,
        method: 'PATCH',
        data : forms.builddata(data)
    }
    
    request(options, emit, resp, back)
}

function destroy (url, data, emit, resp= null, back = null){
    const options = {
        url: url,
        method: 'POST',
        data : forms.builddata(data)
    }
    
    request(options, emit, resp, back)
}

function success(response){
    return response?.status >= 200 && response?.status <= 299
}

export default {
    post,
    put,
    patch,
    get,
    download,
    destroy,
    success
}