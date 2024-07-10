<script setup>

import { inject, onMounted, ref } from 'vue';
import auth from '@/stores/auth';
import http from '@/services/http';
import forms from '@/services/forms';
import notifys from '@/utils/notifys';

const app  = inject('app')

// const user = ref(auth.getUser())

const emit = defineEmits(['callAlert'])
const page = ref({
    data: {
        username:''
    },
    rules:{
        fields: {
            username:'required|email'
        },
        valids:{}
    }
})

function recoverPassword(){
    
}

</script>

<template>
    <main class="container-back-box d-flex justify-content-center align-items-center">
        <div class="box p-5 rounded-4 shadow-sm container-box">
            <header class="d-lg-flex align-items-center text-center text-lg-start mb-4">
                <img src="../assets/imgs/logo.svg" class="mb-3 mb-lg-0" width="55">
                <div>
                    <h1 class="m-0 p-0 ms-0 ms-lg-2 sistem-title-box">{{ app.name }}</h1>
                    <p class="m-0 p-0 text-color-sec small ms-0 ms-lg-2">{{ app.desc }}</p>
                </div>
            </header>

            <form class="row g-3" @submit.prevent="recoverPassword">
                <div class="mb-2">
                    <label for="username" class="form-label">Usuário<span class="text-danger">*</span></label>
                    <input type="email" name="username" class="form-control" :class="{'form-control-alert' : page.rules.valids.username}"
                    id="username" placeholder="nome@example.com" v-model="page.data.username">
                </div>
                
                <div class="mb-4">
                    <button type="submit" class="btn btn-outline-primary w-100">Recuperar Senha<i class="bi bi-check2-circle"></i></button>
                </div>
                
                <div class="box-copyr">
                    <p class="txt-color-sec small p-0 m-0 text-center">Todos os direitos reservados.</p>
                    <p class="txt-color-sec small p-0 m-0 text-center">{{ app.copy }}&copy;</p>
                </div>
                
            </form>
        </div>
    </main>
</template>

<style>
    .icon-user{
        font-size: 3rem;
    }
</style>