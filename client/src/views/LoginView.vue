<script setup>
import { inject, onMounted, ref } from "vue";
import auth from '@/stores/auth';
import forms from "@/services/forms";
import notifys from "@/utils/notifys";
import http from "@/services/http";


const pgdata = ref({
    data: {
        username: 'octus@mail.com',
        password: 'senha123'
    },
    rules: {
        fields: {
            username: 'required|email',
            password: 'required'
        },
        valids: {}
    }
})

const emit = defineEmits(['callAlert'])
const sysapp = inject('sysapp')
const user = ref(auth.getUser())

function login() {
    const validform = forms.checkform(pgdata.value.data, pgdata.value.rules)
    if (!validform.isvalid) {
        emit('callAlert', notifys.warning("Campos obrigatórios não informados"))
        return
    }

    http.post('/auth/login', pgdata.value.data, emit, (resp) => {
        if (http.success(resp)) {
            auth.setToken(resp.data.token)
            auth.setUser(resp.data.user)

            window.location = '/home'
        }
    })
}

function logout() {
    http.get('/auth/logout', emit)
    auth.clear()
    user.value = false
}

onMounted(() => {
    if(auth.token){
        http.get('/auth/check', emit, null, () => {
        logout()
        })
    }
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

            <div v-if="user" class="text-center">
                <ion-icon name="body-outline" class="fs-2"></ion-icon>
                <h2 class="mt-2">{{ user.name }}</h2>
                <p class="small txt-color-sec p-0 m-0">Perfil: {{ user.profile }}</p>
                <p class="small txt-color-sec p-0 m-0">Ultimo Acesso: {{ user.lastlogin }}</p>

                <div class="d-flex justify-content-center mt-4">
                    <RouterLink to="/home" class="btn btn-sm btn-outline-primary d-flex align-items-center"> 
                        <ion-icon name="enter-outline" class="fs-6 me-1"></ion-icon>Partiu!
                    </RouterLink>
                    <button type="button" class="btn btn-sm btn-outline-warning d-flex align-items-center ms-2" @click="logout">
                        <ion-icon name="close-circle-outline" class="fs-6 me-1"></ion-icon> Até Logo
                    </button>
                    
                </div>
            </div>

            <form v-else class="row g-3" @submit.prevent="login">
                <div class="mb-2">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="email" name="username" class="form-control"
                        :class="{ 'form-control-alert': pgdata.rules.valids.username }" id="username"
                        placeholder="nome@example.com" v-model="pgdata.data.username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label d-flex justify-content-between">
                        Senha
                        <RouterLink to="/recover" class="box-link">Esqueceu sua senha?</RouterLink>
                    </label>
                    <input type="password" name="password" class="form-control" id="password"
                        :class="{ 'form-control-alert': pgdata.rules.valids.password }" placeholder="***********"
                        v-model="pgdata.data.password">
                </div>

                <div class="mb-4">
                    <button type="submit"
                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                        <span class="me-2">Entrar</span>
                        <ion-icon name="enter-outline" class="fs-5"></ion-icon>
                    </button>
                </div>


                <div class="box-copyr">
                    <p class="txt-color-sec small p-0 m-0 text-center">Todos os direitos reservados.</p>
                    <p class="txt-color-sec small p-0 m-0 text-center">{{ sysapp.copy }}&copy;</p>
                </div>

            </form>
        </div>
    </main>
</template>