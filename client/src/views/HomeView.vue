<script setup>
import { onMounted, ref, watch } from 'vue';

import Page from '@/services/page';
import dates from '@/utils/dates';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'

const pgdata = ref({
    baseURL: '/frequencies',
    uiview: { search: false, register: false },
    data: {},
    search: {
        sent: false
    },
    dataheader: [
        { obj:'school', key: 'name', title: "ESCOLA", sub:[{ key: 'organ', cast: 'name' }] },
        { obj: 'serie', key: 'name', title: "SERIE" },
        { obj: 'classe', key: 'name', title:'TURMA'},
        { obj: 'subject', key: 'name', title:'DISCIPLINA'},
        { obj: 'teacher', key: 'name', title:'GRADE'}
    ],
    datalist: [],
    selects: {
        organs: [],
        schools: [],
        series: [],
        classes: [],
        subjects: [],
        teachers: []
    },
    selects_loc: {
        years:dates.listYears()
    },
    rules: {
        fields: {
            organ: 'required',
            school: 'required',
            serie: 'required',
            classe: 'required',
            subject: 'required',
            teacher: 'required',
            days: 'required'
        },
        valids: {}
    },

})

const emit = defineEmits(['callAlert', 'callRemove'])
const props = defineProps({ datalist: { type: Array, default: () => [] } })
const page = new Page(pgdata, emit)


watch(() => props.datalist, (newdata) => {
    pgdata.value.datalist = newdata
})

onMounted(() => {
    page.selects()
    page.ui('register')
})

</script>

<template>

    <div class="container-main position-relative">

        <div class="header-view">
            <HeaderMainComp />
        </div>

        <div class="container main-view px-3">

            <!-- HEADER ACTIONS -->
            <header class="d-flex align-items-center justify-content-between my-4">
                <div class="page-title d-flex">
                    <div class="page-title-bar me-1"></div>
                    <div>
                        <h1 class="m-0 p-0">Dashboard</h1>
                        <p class="m-0 p-0 form-text">Panorama de resultados.</p>
                    </div>
                </div>
                <div class="page-actions d-flex align-items-center">
                    
                </div>
            </header>

            <!-- BOX REGISTER -->
            <div class="box p-5 mb-3">
                
                Insigths de Dados Rápidos
                
            </div>
        </div>

        <div class="nav-view">
            <NavMainComp />
        </div>
    </div>

</template>