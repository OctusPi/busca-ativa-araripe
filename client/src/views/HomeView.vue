<script setup>
import { inject, onMounted, ref } from 'vue';
import auth from '@/stores/auth';
import http from '@/services/http';
import forms from '@/services/forms';
import notifys from '@/utils/notifys';

const app  = inject('app')

import MainNav from '@/components/MainNav.vue'

const emit = defineEmits(['callAlert', 'callUpdate'])

</script>

<template>
   <MainNav/>
</template>
