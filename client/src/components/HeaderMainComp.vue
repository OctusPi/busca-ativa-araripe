<script setup>

import { inject } from 'vue';

const sysapp = inject('sysapp')

import NotifysUiComp from '@/components/NotifysUiComp.vue'
import ThemeUiComp from '@/components/ThemeUiComp.vue'
import UserIdUiComp from '@/components/UserIdUiComp.vue'
</script>

<template>
    <header class="container px-3">
        <div class="title-app">
            <RouterLink to="/home">
                <img src="@/assets/imgs/logo.svg" alt="logomarca">
            </RouterLink>
            <div>
                <h1>{{ sysapp.name }}</h1>
                <p>{{ sysapp.desc }}</p>
            </div>
        </div>
        <div class="fast-actions">
            <NotifysUiComp />
            <ThemeUiComp />
            <UserIdUiComp />
        </div>
    </header>
</template>

<style scoped>
header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    background-color: var(--bg-fundo);
}

.title-app{
    display: flex;
    align-items: center;
}

.title-app img{
    width: 42px;
    margin-right: 8px;
}

.title-app h1{
    font-size: 1rem;
    margin: 0;
    padding: 0;
}

.title-app p{
    font-size: 0.8rem;
    margin: 0;
    padding: 0;
    color: var(--cl-txt-sec);
}

.fast-actions{
    display: flex;
}
</style>