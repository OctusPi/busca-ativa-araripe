<script setup>
import { onMounted, ref } from 'vue';
import auth from '@/stores/auth';
import http from '@/services/http';

const emit = defineEmits(['callAlert'])
const user = ref(auth.getUser())
const tmss = ref('60:00')

function logout() {
    http.get('/auth/logout', emit, () => {
        auth.clear()
        user.value = null
        window.location = '/'
    })
}

function is_auth() {
    if (!auth.token || !user.value) {
        logout()
    }
}

function time_session() {
    let minutes = 59
    let seconds = 59

    function regressivecount() {
        if (seconds > 0) {
            seconds--
        } else {
            seconds = 59
            minutes--
        }

        tmss.value = minutes >= 0 ? minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0') : '00:00'

        if (minutes < 0) {
            logout()
        }
    }

    setInterval(regressivecount, 1000);
}

onMounted(() => {
    is_auth()
    time_session()
})
</script>

<template>
    <div class="dropdown ms-3" v-if="user">
        <button class="btn-fast-action" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/assets/icons/user-id.svg" alt="icon-userid">
        </button>
        <ul class="dropdown-menu">
            <li class="my-1">
                <a class="dropdown-item d-flex align-items-center py-2" href="#">
                    <ion-icon name="person-outline" class="fs-5 me-3"></ion-icon>
                    <span>
                        <span class="d-block p-0 m-0 small">Usuário</span>
                        <span class="d-block p-0 m-0 small fw-bolder">{{ user.name }}</span>
                    </span>
                </a>
            </li>
            <li class="my-1">
                <a class="dropdown-item d-flex align-items-center py-2" href="#">
                    <ion-icon name="albums-outline" class="fs-5 me-3"></ion-icon>
                    <span>
                        <span class="d-block p-0 m-0 small">Perfil</span>
                        <span class="d-block p-0 m-0 small fw-bolder">{{ user.profile }}</span>
                    </span>
                </a>
            </li>
            <li class="my-1">
                <a class="dropdown-item d-flex align-items-center py-2" href="#">
                    <ion-icon name="calendar-outline" class="fs-5 me-3"></ion-icon>
                    <span>
                        <span class="d-block p-0 m-0 small">Ultimo Acesso</span>
                        <span class="d-block p-0 m-0 small fw-bolder">{{ user.lastlogin }}</span>
                    </span>
                </a>
            </li>
            <li class="my-1">
                <a class="dropdown-item d-flex align-items-center py-2" href="#">
                    <ion-icon name="alarm-outline" class="fs-5 me-3"></ion-icon>
                    <span>
                        <span class="d-block p-0 m-0 small">Tempo Restante</span>
                        <span class="d-block p-0 m-0 small fw-bolder">{{ tmss }}</span>
                    </span>
                </a>
            </li>
            <li class="my-1">
                <a @click.prevent="logout" class="dropdown-item d-flex align-items-center py-2" href="#">
                    <ion-icon name="log-out-outline" class="fs-5 me-3"></ion-icon>
                    <span>
                        <span class="d-block p-0 m-0 small">Fazer Logout</span>
                        <span class="d-block p-0 m-0 small fw-bolder">Sair com Segurança</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</template>