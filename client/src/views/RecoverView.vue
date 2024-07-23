<script setup>
import { inject, ref } from "vue";
import forms from "@/services/forms";
import notifys from "@/utils/notifys";
import http from "@/services/http";


const pgdata = ref({
    data: {},
    rules: {
        fields: {
            username: 'required|email'
        },
        valids: {}
    }
})

const emit = defineEmits(['callAlert'])
const sysapp = inject('sysapp')

function recover() {
    const validform = forms.checkform(pgdata.value.data, pgdata.value.rules)
    if (!validform.isvalid) {
        emit('callAlert', notifys.warning("Campos obrigatórios não informados"))
        return
    }

    http.post('/auth/recover', pgdata.value.data, emit)
}


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

            <form class="row g-3" @submit.prevent="recover">

                <h1 class="text-center mb-3">Recuperação de Senha</h1>
                
                <div class="mb-3">
                    <label for="password" class="form-label d-flex justify-content-between">
                        E-mail
                        <RouterLink to="/" class="box-link">Fazer login</RouterLink>
                    </label>
                    <input type="email" name="username" class="form-control" id="username"
                        :class="{ 'form-control-alert': pgdata.rules.valids.username }" 
                        placeholder="Digite seu endereço de E-mail" v-model="pgdata.data.username">
                </div>

                <div class="mb-4">
                    <button type="submit"
                        class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center">
                        <span class="me-2">Solicitar</span>
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