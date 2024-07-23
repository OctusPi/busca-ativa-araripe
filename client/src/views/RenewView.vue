<script setup>
import { inject, onMounted, ref } from "vue";
import { useRoute } from 'vue-router';
import forms from "@/services/forms";
import notifys from "@/utils/notifys";
import http from "@/services/http";

const emit = defineEmits(['callAlert'])
const route = useRoute()
const token = route.params.token
const sysapp = inject('sysapp')
const user = ref(undefined)

const pgdata = ref({
    data: {
        token:token
    },
    rules: {
        fields: {
            newpass: 'required|password',
            confpass: 'required|password'
        },
        valids: {}
    }
})

function renew() {

    if(pgdata.value.data.newpass !== pgdata.value.data.confpass){
        emit('callAlert', notifys.warning("As senhas não são iguais..."))
        return
    }

    const validform = forms.checkform(pgdata.value.data, pgdata.value.rules)
    if (!validform.isvalid) {
        emit('callAlert', notifys.warning(validform.message))
        return
    }

    http.post('/auth/renew', pgdata.value.data, emit, (resp) => {
        if (http.success(resp)) {
            window.location = '/'
        }
    })
}

function validate_renew() {
    http.get(`/auth/validate_renew/${token}`, emit, (resp) => {
        user.value = resp.data
    })
}


onMounted(() => {
    validate_renew()
})

</script>
<template>
    <main class="container-back-box d-flex justify-content-center align-items-center">
        <div class="box p-5 rounded-4 shadow-sm container-box">
            <header class="d-lg-flex align-items-center text-center text-lg-start mb-4">
                <img src="../assets/imgs/logo.svg" class="logomarca-box mb-3 mb-lg-0">
                <div>
                    <h1 class="m-0 p-0 ms-0 ms-lg-2 sistem-title-box">{{ sysapp.name }}</h1>
                    <p class="p-0 m-0 text-color-sec small ms-0 ms-lg-2">{{ sysapp.desc }}</p>
                </div>
            </header>

            <div v-if="!user" class="text-center">
                <ion-icon name="extension-puzzle-outline" class="fs-2 mb-3"></ion-icon>
                <p class="form-text">Seu link solicitado expirou, para recuperar sua senha será necessário solicitar outro link.</p>

                <RouterLink to="/recover"
                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                    <span class="me-2">Solicitar</span>
                    <ion-icon name="finger-print-outline" class="fs-5"></ion-icon>
                </RouterLink>
            </div>

            <div v-else>
                <div class="text-center mb-3">
                    <h1 class="mb-3">Cadastrar nova senha</h1>
                    <ion-icon name="finger-print-outline" class="fs-2"></ion-icon>
                    <h2 class="mt-2">{{ user.name }}</h2>
                    <p class="small txt-color-sec p-0 m-0">Perfil: {{ user.profile }}</p>
                    <p class="small txt-color-sec p-0 m-0">Ultimo Acesso: {{ user.lastlogin }}</p>
                </div>

                <form class="row g-3" @submit.prevent="renew">
                    <div class="mb-2">
                        <label for="newpass" class="form-label">Nova Senha</label>
                        <input type="password" name="newpass" class="form-control"
                            :class="{ 'form-control-alert': pgdata.rules.valids.newpass }" id="newpass"
                            placeholder="***********" v-model="pgdata.data.newpass">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confpass" class="form-label d-flex justify-content-between">
                            Confirmar Senha
                        </label>
                        <input type="password" name="confpass" class="form-control" id="confpass"
                            :class="{ 'form-control-alert': pgdata.rules.valids.confpass }" placeholder="***********"
                            v-model="pgdata.data.confpass">
                    </div>

                    <div class="mb-4">
                        <button type="submit"
                            class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <span class="me-2">Cadastrar</span>
                            <ion-icon name="enter-outline" class="fs-5"></ion-icon>
                        </button>
                    </div>
                </form>
            </div>

            <div class="box-copyr mt-4">
                <p class="txt-color-sec small p-0 m-0 text-center">Todos os direitos reservados.</p>
                <p class="txt-color-sec small p-0 m-0 text-center">{{ sysapp.copy }}&copy;</p>
            </div>
        </div>
    </main>
</template>