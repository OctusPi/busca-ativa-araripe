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
            </header>

            <div class="row g-3 mb-3">
                <div class="col-sm-12 col-md-4">
                    <div class="box box-dash p-4">
                        <div class="title-dash d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Alunos Matriculados 2024</h2>
                                <p>Quantitativo de Alunos Matriculados no ano corrente</p>
                            </div>
                            <ion-icon name="people-outline" class="fs-2 ms-2"></ion-icon>
                        </div>
                        <div class="content-dash mt-2">
                            ...
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="box box-dash p-4">
                        <div class="title-dash d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Alunos por Escola 2024</h2>
                                <p>Quantitativo de Alunos por Escola no ano corrente</p>
                            </div>
                            <ion-icon name="home-outline" class="fs-2 ms-2"></ion-icon>
                        </div>
                        <div class="content-dash mt-2">
                            ...
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="box box-dash p-4">
                        <div class="title-dash d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Professores</h2>
                                <p>Quantitativo de Professores por qualificação</p>
                            </div>
                            <ion-icon name="school-outline" class="fs-2 ms-2"></ion-icon>
                        </div>
                        <div class="content-dash mt-2">
                            ...
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="box box-dash p-4">
                        <div class="title-dash d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Panorama de Faltas 2024</h2>
                                <p>Quantitativo de faltas mês a mês</p>
                            </div>
                            <ion-icon name="close-circle-outline" class="fs-2 ms-2"></ion-icon>
                        </div>
                        <div class="content-dash mt-2">
                            ...
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="box box-dash p-4">
                        <div class="title-dash d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Faltas por Escola 2024</h2>
                                <p>Quantitativo de faltas por escola no ano corrente</p>
                            </div>
                            <ion-icon name="apps-outline" class="fs-2 ms-2"></ion-icon>
                        </div>
                        <div class="content-dash mt-2">
                            ...
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="box box-dash p-4">
                        <div class="title-dash d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Faltas por Série 2024</h2>
                                <p>Quantitativo de faltas por série no ano corrente</p>
                            </div>
                            <ion-icon name="grid-outline" class="fs-2 ms-2"></ion-icon>
                        </div>
                        <div class="content-dash mt-2">
                            ...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-view">
            <NavMainComp />
        </div>
    </div>

</template>

<style scoped>

.box-dash{
    height: 310px;
    overflow: auto;
}

.title-dash *{
    margin: 0;
    padding: 0;
}

.title-dash h2{
    color: var(--cl-base);
}

.title-dash p{
    font-size: 0.8rem;
}
</style>