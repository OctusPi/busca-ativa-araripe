<script setup>

import { ref } from 'vue'

const emit = defineEmits(['action'])
const props = defineProps({
    id: [String, Number],
    calls: { type: Array, default: () => [] }
})

const calls = ref(props.calls)
const actions = {
    'update': {
        action: (id) => { emit('action', { e: 'action:update', i: id }) },
        icon: 'create-outline',
        title: 'Editar'
    },
    'delete': {
        action: (id) => { emit('action', { e: 'action:delete', i: id }) },
        icon: 'trash-bin-outline',
        title: 'Excluir'
    },
    'fastdelete': {
        action: (id) => { emit('action', { e: 'action:fastdelete', i: id }) },
        icon: 'trash-bin-outline',
        title: 'Remover'
    },
    'download': {
        action: (id) => { emit('action', { e: 'action:download', i: id }) },
        icon: 'cloud-download-outline',
        title: 'Docs'
    },
    'export_pdf': {
        action: (id) => { emit('action', { e: 'action:pdf', i: id }) },
        icon: 'document-text-outline',
        title: 'Gerar PDF'
    },
    'export_doc': {
        action: (id) => { emit('action', { e: 'action:doc', i: id }) },
        icon: 'document-outline',
        title: 'Gerar Doc'
    },
    'export_xls': {
        action: (id) => { emit('action', { e: 'action:xls', i: id }) },
        icon: 'document-attach-outline',
        title: 'Gerar Excel'
    },
    'members': {
        action: (id) => { emit('action', { e: 'action:members', i: id }) },
        icon: 'people-outline',
        title: 'Membros'
    },
    'extinction': {
        action: (id) => { emit('action', { e: 'action:extinction', i: id }) },
        icon: 'calendar-outline',
        title: 'Extinguir'
    },
    'items': {
        action: (id) => { emit('action', { e: 'action:items', i: id }) },
        icon: 'cube-outline',
        title: 'Itens'
    },
    'clone': {
        action: (id) => { emit('action', { e: 'action:clone', i: id }) },
        icon: 'documents-outline',
        title: 'Clonar'
    },
    'modaldetails': {
        action: (id) => { emit('action', { e: 'action:modaldetails', i: id }) },
        icon: 'list-outline',
        title: 'Detalhar'
    },

}

function enableModal(call){
    switch(call){
        case 'delete':
            return '#modalDelete'
        case 'modaldetails':
            return '#modalDetails'
        default:
            return null
    }
}

</script>

<template>
    <div class="dropdown">
        <button class="btn btn-sm d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <ion-icon name="ellipsis-vertical-outline" class="fs-6"></ion-icon>
        </button>
        <ul class="dropdown-menu px-2 py-3">
            <li>
                <a class="dropdown-item item-action-menu d-flex align-items-center p-2" href="#" v-for="c in calls" :key="actions[c].title"
                    :data-bs-toggle="enableModal(c) ? 'modal' : null"
                    :data-bs-target="enableModal(c)"
                    @click.prevent="actions[c].action(props.id)">
                    <ion-icon :name="actions[c].icon" class="fs-6 me-1"></ion-icon>
                    {{ actions[c].title }}
                </a>
            </li>
        </ul>
    </div>
</template>

<style scoped>
    .btn-sm{
        color: var(--cl-txt)
    }
</style>