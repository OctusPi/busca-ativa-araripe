<script setup>

import style from '@/stores/theme';

function changeTheme(mode) {
    const screen = document.getElementById('screen')
    if (screen) {

        //remove theme
        screen.classList.remove('ligth')
        screen.classList.remove('dark')

        //set theme
        screen.classList.add(mode)
        style.setTheme(mode)

    }
}
</script>

<template>
    <div class="dropdown ms-3">
        <button class="btn-fast-action" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/assets/icons/theme.svg" alt="icon-theme">
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" @click="changeTheme('ligth')">
                    <ion-icon name="sunny-outline"></ion-icon>
                    <span class="ms-2">Modo Claro</span>
                </a></li>
            <li><a class="dropdown-item" href="#" @click="changeTheme('dark')">
                    <ion-icon name="moon-outline"></ion-icon>
                    <span class="ms-2">Modo Escuro</span>
                </a></li>
        </ul>
    </div>
</template>