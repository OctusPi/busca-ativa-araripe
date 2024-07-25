<script setup>
import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
</script>

<template>
    <div class="container-main position-relative">

        <div class="header-view">
            <HeaderMainComp />
        </div>

        <main class="container main-view px-3">
            <div class="box p-4">
                <h1>Dashboard</h1>
            </div>
        </main>

        <nav class="nav-view">
            <NavMainComp />
        </nav>

    </div>
</template>