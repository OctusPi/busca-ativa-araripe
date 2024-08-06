<script setup>
import { onMounted, ref, watch } from 'vue';

import Page from '@/services/page';
import dates from '@/utils/dates';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';

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
                        <h1 class="m-0 p-0">Controle de Frequencias</h1>
                        <p class="m-0 p-0 form-text">Visualize e gerencie a frequencia diária.</p>
                    </div>
                </div>
                <div class="page-actions d-flex align-items-center">
                    
                </div>
            </header>

            <!-- BOX REGISTER -->
            <div class="box p-5 mb-3">
                <HeaderBoxUiComp icon="checkmark-circle-outline" title="Frequência Diária"
                    desc="Registre as faltas para qualificar a presença dos alunos." />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="organ" class="form-label">Órgão</label>
                            <select name="organ" class="form-control" @change="page.selects('organs', pgdata.data.organ)"
                                :class="{ 'form-control-alert': pgdata.rules.valids.organ }" id="organ"
                                v-model="pgdata.data.organ">
                                <option></option>
                                <option v-for="s in pgdata.selects.organs" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="school" class="form-label">Escola</label>
                            <select name="school" class="form-control" @change="page.selects('classes',`${pgdata.data.organ},${pgdata.data.school},${pgdata.data.serie}`)"
                                :class="{ 'form-control-alert': pgdata.rules.valids.school }" id="school"
                                v-model="pgdata.data.school">
                                <option></option>
                                <option v-for="s in pgdata.selects.schools" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="teacher" class="form-label">Professor</label>
                            <select name="teacher" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.teacher }" id="teacher"
                                v-model="pgdata.data.teacher">
                                <option></option>
                                <option v-for="s in pgdata.selects.teachers" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="subject" class="form-label">Disciplina</label>
                            <select name="subject" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.subject }" id="subject"
                                v-model="pgdata.data.subject">
                                <option></option>
                                <option v-for="s in pgdata.selects.subjects" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <label for="serie" class="form-label">Série</label>
                            <select name="serie" class="form-control" @change="page.selects('classes', `${pgdata.data.organ},${pgdata.data.school},${pgdata.data.serie}`)"
                                :class="{ 'form-control-alert': pgdata.rules.valids.serie }" id="serie"
                                v-model="pgdata.data.serie">
                                <option></option>
                                <option v-for="s in pgdata.selects.series" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <label for="classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.classe }" id="classe"
                                v-model="pgdata.data.classe">
                                <option></option>
                                <option v-for="s in pgdata.selects.classes" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <label for="year" class="form-label">Ano Letivo</label>
                            <select name="year" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.year }" id="year"
                                v-model="pgdata.data.year">
                                <option></option>
                                <option v-for="s in pgdata.selects_loc.years" :key="s" :value="s">{{ s }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <label for="classe" class="form-label">Data</label>
                            <VueDatePicker auto-apply v-model="pgdata.data.date_miss"
                                            :input-class-name="pgdata.rules.valids.date_miss ? 'dp-custom-input-dtpk-alert' : 'dp-custom-input-dtpk'"
                                            :enable-time-picker="false" format="dd/MM/yyyy" model-type="dd/MM/yyyy"
                                            locale="pt-br" calendar-class-name="dp-custom-calendar"
                                            calendar-cell-class-name="dp-custom-cell"
                                            menu-class-name="dp-custom-menu" />
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <button type="submit" class="btn btn-form btn-accept d-flex align-items-center mx-2">
                            <ion-icon name="checkmark-circle-outline" class="fs-6 me-1"></ion-icon>
                            Listar Alunos
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="nav-view">
            <NavMainComp />
        </div>
    </div>

</template>