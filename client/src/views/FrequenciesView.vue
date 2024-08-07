<script setup>
import { onMounted, ref, watch } from 'vue';

import Page from '@/services/page';
import dates from '@/utils/dates';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import forms from '@/services/forms';
import notifys from '@/utils/notifys';
import http from '@/services/http';
import TableListSelectPresence from '@/components/TableListSelectPresence.vue';

const pgdata = ref({
    baseURL: '/frequencies',
    uiview: { search: false, register: false },
    data: {
        year: dates.getYearNow(),
        date_miss: dates.nowUtc().date,
        students: []
    },
    search: {
        sent: false
    },
    dataheader: [
        { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'id_sige', title: 'ID. Sige: ' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'city', title: 'LOCALIZAÇÃO', sub: [{ key: 'street' }, { key: 'neighborhood' }] },
        { key: 'mother', title: 'RESPOSÁVEIS', sub: [{ key: 'father' }] },
        { key: 'status', cast: 'title', title: 'STATUS' }
    ],
    datalist: [],
    selects: {
        organs: [],
        schools: [],
        series: [],
        classes: [],
        subjects: [],
        teachers: [],
        status:[]
    },
    selects_loc: {
        years: dates.listYears()
    },
    rules: {
        fields: {
            school: 'required',
            serie: 'required',
            classe: 'required',
            teacher: 'required',
            subject: 'required',
            year: 'required',
            date_miss: 'required'
        },
        valids: {}
    }

})

const emit = defineEmits(['callAlert', 'callRemove'])
const props = defineProps({ datalist: { type: Array, default: () => [] } })
const page = new Page(pgdata, emit)

function list() {
    const chkf = forms.checkform(pgdata.value.data, pgdata.value.rules)
    if (!chkf.isvalid) {
        emit('callAlert', notifys.warning(chkf.message))
        return;
    }

    http.post(`${pgdata.value.baseURL}/list`, pgdata.value.data, emit, (resp) => {
        pgdata.value.search.sent = true
        pgdata.value.data.students = resp.data?.fouls
        pgdata.value.datalist = resp.data?.students
    })
}

watch(() => props.datalist, (newdata) => {
    pgdata.value.datalist = newdata
})

onMounted(() => {
    page.selects()
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
                            <label for="school" class="form-label">Escola</label>
                            <select name="school" class="form-control"
                                @change="page.selects('schools', pgdata.data.school)"
                                :class="{ 'form-control-alert': pgdata.rules.valids.school }" id="school"
                                v-model="pgdata.data.school">
                                <option></option>
                                <option v-for="s in pgdata.selects.schools" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="serie" class="form-label">Série</label>
                            <select name="serie" class="form-control"
                                @change="page.selects('classes', `${pgdata.data.school},${pgdata.data.serie}`)"
                                :class="{ 'form-control-alert': pgdata.rules.valids.serie }" id="serie"
                                v-model="pgdata.data.serie">
                                <option></option>
                                <option v-for="s in pgdata.selects.series" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.classe }" id="classe"
                                v-model="pgdata.data.classe"
                                @change="page.selects('teachers', `${pgdata.data.school},${pgdata.data.serie},${pgdata.data.classe},${pgdata.data.subject}`)">
                                <option></option>
                                <option v-for="s in pgdata.selects.classes" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="subject" class="form-label">Disciplina</label>
                            <select name="subject" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.subject }" id="subject"
                                v-model="pgdata.data.subject"
                                @change="page.selects('teachers', `${pgdata.data.school},${pgdata.data.serie},${pgdata.data.classe},${pgdata.data.subject}`)">
                                <option></option>
                                <option v-for="s in pgdata.selects.subjects" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="teacher" class="form-label">Professor</label>
                            <select name="teacher" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.teacher }" id="teacher"
                                v-model="pgdata.data.teacher">
                                <option></option>
                                <option v-for="s in pgdata.selects.teachers" :key="s.teacher.id" :value="s.teacher.id">
                                    {{ s.teacher.name }}
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
                                :enable-time-picker="false" format="dd/MM/yyyy" model-type="yyyy-MM-dd" locale="pt-br"
                                calendar-class-name="dp-custom-calendar" calendar-cell-class-name="dp-custom-cell"
                                menu-class-name="dp-custom-menu" />
                        </div>
                    </div>

                    <div class="accordion my-4" id="accordion-search">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="accordion-search-header">
                                <button class="w-100 text-center px-2 py-3" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accordion-search-collapse" aria-expanded="false"
                                    aria-controls="accordion-search-collapse">
                                    <template v-if="pgdata.datalist.length">
                                        <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                            <ion-icon name="people-outline" class="fs-6 me-1"></ion-icon>
                                            Alunos Matriculados
                                        </h2>
                                        <p class="form-text text-center m-0 p-0 mt-1">
                                            Aplique as faltas para os alunos na data selecionada
                                        </p>
                                    </template>
                                    <template v-else>
                                        <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                            <ion-icon name="people-outline" class="fs-6 me-1"></ion-icon>
                                            {{ pgdata.search.sent ? 'Não foram localizados alunos para o filtro' :
                                            'Aplique o filtro para listar os alunos' }}
                                        </h2>
                                        <p class="form-text text-center m-0 p-0 mt-1">
                                            Selecione um ou mais alunos para registro de faltas.
                                        </p>
                                    </template>
                                </button>
                            </h2>
                            <div id="accordion-search-collapse" class="accordion-collapse collapse"
                                :class="{ 'show': pgdata.datalist.length }" aria-labelledby="accordion-search-header"
                                data-bs-parent="#accordion-search">
                                <div class="accordion-body p-4">
                                    <div class="mt-5 form-neg-box-min">
                                        <TableListSelectPresence identify="students_reg" :sent="pgdata.search.sent"
                                            :header="pgdata.dataheader" :body="pgdata.datalist"
                                            :casts="{ status: pgdata.selects.status }"
                                            v-model="pgdata.data.students" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse mt-4">
                        <button @click="list" type="button" class="btn btn-form btn-accept d-flex align-items-center">
                            <ion-icon name="search-outline" class="fs-6 me-1"></ion-icon>
                            Listar Alunos
                        </button>
                        <button v-if="pgdata.datalist.length" type="button"
                            class="btn btn-form btn-danger d-flex align-items-center mx-2">
                            <ion-icon name="alert-circle-outline" class="fs-6 me-1"></ion-icon>
                            Enviar Frequencia
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