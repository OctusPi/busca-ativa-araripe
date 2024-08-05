<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import masks from '@/utils/masks';
import dates from '@/utils/dates';
import notifys from '@/utils/notifys';
import http from '@/services/http';
import forms from '@/services/forms';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';
import TableListSelect from '@/components/TableListSelect.vue';

const pgdata = ref({
    baseURL: '/registrations',
    uiview: { search: false, register: false },
    data: {},
    search: {
        sent: false,
        modality: 'student'
    },
    search_students: {
        sent: false,
        search: {},
        header: [
            { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'id_sige', title: 'ID. Sige: ' }] },
            { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
            { key: 'city', title: 'LOCALIZAÇÃO', sub: [{ key: 'street' }, { key: 'neighborhood' }] },
            { key: 'mother', title: 'RESPOSÁVEIS', sub: [{ key: 'father' }] },
            { key: 'status', cast: 'title', title: 'STATUS' }
        ],
        body: []
    },
    dataheader: [
        { obj: 'student', key: 'name', title: 'ALUNO', sub: [{ obj: 'student', key: 'id_sige', title: 'ID. Sige: ' }, { obj: 'student', key: 'birth', title: ' / Nascimento: ' }] },
        { obj: 'school', key: 'name', title: 'ESCOLA', sub: [{ obj: 'school', key: 'inep' }] },
        { key: 'year', title: 'MATRÍCULA', sub: [{ obj: 'serie', title: 'Série: ', key: 'name' }, { obj: 'classe', title: ' - Turma: ', key: 'name' }] },
        { key: 'status', cast: 'title', title: 'STATUS' }
    ],
    datalist: [],
    selects: {
        organs: [],
        schools: [],
        series: [],
        classes: [],
        status: [],
        students_status: []
    },
    selects_loc: {
        years: dates.listYears(2022)
    },
    rules: {
        fields: {
            organ: 'required',
            school: 'required',
            serie: 'required',
            classe: 'required',
            year: 'required',
            status: 'required',
            students: 'required'
        },
        valids: {}
    }
})

const emit = defineEmits(['callAlert', 'callRemove'])
const props = defineProps({ datalist: { type: Array, default: () => [] } })
const page = new Page(pgdata, emit)

function list(modality = null) {
    pgdata.value.search.modality = modality

    if (modality === 'student') {
        if (!pgdata.value.search.name && !pgdata.value.search.id_sige) {
            emit('callAlert', notifys.warning('Informe o nome ou ID. Sige do aluno pra localizar as matrículas...'))
            return
        }
    } else {
        const rules = {
            fields: {
                school: 'required',
                serie: 'required',
                classe: 'required',
                year: 'required'
            },
            valids: {}
        }

        const check = forms.checkform(pgdata.value.search, rules)

        if (!check.isvalid) {
            emit('callAlert', notifys.warning(check.message))
            return
        }
    }

    page.list()
}

function update(id) {
    const reg = pgdata.value.datalist.find(obj => obj.id === id)
    if (reg) {
        page.selects('classes', `${reg.school.organ},${reg.school.id},${reg.serie.id}`);
        http.get(`${pgdata.value.baseURL}/details/${id}`, emit, (resp) => {
            pgdata.value.data = resp.data
            pgdata.value.search_students.sent = true
            pgdata.value.search_students.body = [resp.data.student]
            pgdata.value.data.students = [resp.data.student]
            page.ui('update')
        })
    }
}

function search_students() {
    if (!Object.keys(pgdata.value.search_students.search).length) {
        emit('callAlert', notifys.warning('Informe pelo menos um filtro para localizar os alunos...'))
        return
    }
    pgdata.value.search_students.sent = true
    http.post('/students/list', pgdata.value.search_students.search, emit, (resp) => {
        pgdata.value.search_students.body = resp.data
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

        <main class="container main-view px-3">
            <!-- HEADER ACTIONS -->
            <header class="d-flex align-items-center justify-content-between my-4">
                <div class="page-title d-flex">
                    <div class="page-title-bar me-1"></div>
                    <div>
                        <h1 class="m-0 p-0">Gestão de Matrículas</h1>
                        <p class="m-0 p-0 form-text">Controle de matrículas por ano letivo</p>
                    </div>
                </div>
                <div class="page-actions d-flex align-items-center">
                    <button @click="page.ui('register')" type="button"
                        class="btn btn-action d-flex align-items-center ms-2">
                        <ion-icon name="add-circle-outline" class="fs-6 me-1 btn-icon"></ion-icon>
                        <span>Adicionar</span>
                    </button>
                    <button @click="page.ui('search')" type="button"
                        class="btn btn-action d-flex align-items-center ms-2">
                        <ion-icon name="search-outline" class="fs-6 me-1 btn-icon"></ion-icon>
                        <span>Localizar</span>
                    </button>
                </div>
            </header>

            <!-- BOX SEARCH -->
            <div v-if="pgdata.uiview.search" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="search-outline" title="Localizar Registros"
                    desc="Aplique os filtros para localizar os registros desejados" />

                <div class="accordion my-4" id="accordion-search">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="accordion-search-header">
                            <button class="w-100 text-center px-2 py-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-search-collapse" aria-expanded="false"
                                aria-controls="accordion-search-collapse">
                                <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                    <ion-icon name="copy-outline" class="fs-6 me-1"></ion-icon>
                                    Localizar Matriculas por Vínculo
                                </h2>
                                <p class="form-text text-center m-0 p-0 mt-1">
                                    Localizar por Escola/Serie/Turma/Ano Letivo
                                </p>
                            </button>
                        </h2>
                        <div id="accordion-search-collapse" class="accordion-collapse collapse show"
                            aria-labelledby="accordion-search-header" data-bs-parent="#accordion-search">
                            <div class="accordion-body p-4">
                                <form class="form-row" @submit.prevent="list">
                                    <div class="row g-3">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="s-school" class="form-label">Orgão</label>
                                            <select @change="page.selects('schools', pgdata.search.organ)" name="organ"
                                                class="form-control" id="s-organ" v-model="pgdata.search.organ">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.organs" :key="s.id" :value="s.id">{{
                                                    s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="s-school" class="form-label">Escola</label>
                                            <select
                                                @change="page.selects('classes', `${pgdata.search.organ},${pgdata.search.school},${pgdata.search.serie}`)"
                                                name="school" class="form-control" id="s-school"
                                                v-model="pgdata.search.school">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.schools" :key="s.id" :value="s.id">{{
                                                    s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="s-serie" class="form-label">Série</label>
                                            <select
                                                @change="page.selects('classes', `${pgdata.search.organ},${pgdata.search.school},${pgdata.search.serie}`)"
                                                name="serie" class="form-control" id="s-serie"
                                                v-model="pgdata.search.serie">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.series" :key="s.id" :value="s.id">{{
                                                    s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <label for="s-classe" class="form-label">Turma</label>
                                            <select name="classe" class="form-control" id="s-classe"
                                                v-model="pgdata.search.classe">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.classes" :key="s.id" :value="s.id">{{
                                                    s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <label for="s-year" class="form-label">Ano Letivo</label>
                                            <select name="year" class="form-control" id="s-year"
                                                v-model="pgdata.search.year">
                                                <option></option>
                                                <option v-for="s in pgdata.selects_loc.years" :key="s" :value="s">{{ s
                                                    }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row-reverse mt-4">
                                        <button type="submit" class="btn btn-form btn-accept d-flex align-items-center">
                                            <ion-icon name="search-outline" class="fs-6 me-1"></ion-icon>
                                            Aplicar Filtro
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="accordion-search-header">
                            <button class="w-100 text-center px-2 py-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-search-collapse" aria-expanded="false"
                                aria-controls="accordion-search-collapse">
                                <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                    <ion-icon name="person-outline" class="fs-6 me-1"></ion-icon>
                                    Localizar Matrículas por Aluno
                                </h2>
                                <p class="form-text text-center m-0 p-0 mt-1">
                                    Visualizar todas as matriculas para o Estudante
                                </p>
                            </button>
                        </h2>
                        <div id="accordion-search-collapse" class="accordion-collapse collapse"
                            aria-labelledby="accordion-search-header" data-bs-parent="#accordion-search">
                            <div class="accordion-body p-4">
                                <form class="form-row" @submit.prevent="list('student')">
                                    <div class="row g-3">
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-name" class="form-label">Nome</label>
                                            <input type="text" name="name" class="form-control" id="s-name"
                                                placeholder="Buscar por parte do nome" v-model="pgdata.search.name">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-sige" class="form-label">ID. Sige</label>
                                            <input type="text" name="sige" class="form-control" id="s-sige"
                                                placeholder="00000000" v-model="pgdata.search.id_sige">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-cpf" class="form-label">CPF</label>
                                            <input type="text" name="cpf" class="form-control" id="s-cpf"
                                                placeholder="000.000.000-00" v-model="pgdata.search.cpf"
                                                v-maska:[masks.maskcpf]>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row-reverse mt-4">
                                        <button type="submit" class="btn btn-form btn-accept d-flex align-items-center">
                                            <ion-icon name="search-outline" class="fs-6 me-1"></ion-icon>
                                            Aplicar Filtro
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOX LIST -->
            <div v-if="!pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="keypad-outline" title="Lista de Matrículas"
                    desc="Listagem de matrículas por Escola/Série/Turma/Ano Letivo" />

                <div v-if="pgdata.search.sent" class="form-neg-box">
                    <TableList :header="pgdata.dataheader" :body="pgdata.datalist" :actions="['update', 'delete']"
                        :casts="{
                            organ: pgdata.selects.organs,
                            race: pgdata.selects.races,
                            sex: pgdata.selects.sexs,
                            status: pgdata.selects.status
                        }" @action:update="update" @action:delete="page.remove" />
                </div>

                <div class="text-center" v-else>
                    <ion-icon name="chatbox-ellipses-outline" class="form-text fs-3"></ion-icon>
                    <p class="form-text">Aplique o filtro na opção localizar para visualizar as matrículas...</p>
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="document-text-outline" title="Registro de Matrículas"
                    desc="Registro de matrículas por aluno" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6">
                            <label for="s-organ" class="form-label">Orgão</label>
                            <select name="organ" class="form-control" id="s-organ"
                                :class="{ 'form-control-alert': pgdata.rules.valids.organ }" v-model="pgdata.data.organ"
                                @change="page.selects('schools', pgdata.data.organ)">
                                <option></option>
                                <option v-for="o in pgdata.selects.organs" :key="o.id" :value="o.id">{{ o.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="s-school" class="form-label">Escola</label>
                            <select name="school" class="form-control" id="s-school"
                                :class="{ 'form-control-alert': pgdata.rules.valids.school }"
                                v-model="pgdata.data.school"
                                @change="page.selects('classes', `${pgdata.data.organ},${pgdata.data.school},${pgdata.data.serie}`)">
                                <option></option>
                                <option v-for="o in pgdata.selects.schools" :key="o.id" :value="o.id">{{ o.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-serie" class="form-label">Série/Ano</label>
                            <select name="serie" class="form-control" id="s-serie"
                                :class="{ 'form-control-alert': pgdata.rules.valids.serie }" v-model="pgdata.data.serie"
                                @change="page.selects('classes', `${pgdata.data.organ},${pgdata.data.school},${pgdata.data.serie}`)">
                                <option></option>
                                <option v-for="o in pgdata.selects.series" :key="o.id" :value="o.id">{{ o.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control" id="s-classe"
                                :class="{ 'form-control-alert': pgdata.rules.valids.classe }"
                                v-model="pgdata.data.classe">
                                <option></option>
                                <option v-for="o in pgdata.selects.classes" :key="o.id" :value="o.id">{{ o.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-year" class="form-label">Ano Letivo</label>
                            <select name="year" class="form-control" id="s-year"
                                :class="{ 'form-control-alert': pgdata.rules.valids.year }" v-model="pgdata.data.year">
                                <option></option>
                                <option v-for="y in pgdata.selects_loc.years" :key="y" :value="y">{{ y }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-status" class="form-label">Situação</label>
                            <select name="status" class="form-control" id="s-status"
                                :class="{ 'form-control-alert': pgdata.rules.valids.status }"
                                v-model="pgdata.data.status">
                                <option></option>
                                <option v-for="y in pgdata.selects.status" :key="y.id" :value="y.id">{{ y.title }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="accordion my-4" id="accordion-search">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="accordion-search-header">
                                <button class="w-100 text-center px-2 py-3" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accordion-search-collapse" aria-expanded="false"
                                    aria-controls="accordion-search-collapse">
                                    <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                        <ion-icon name="people-outline" class="fs-6 me-1"></ion-icon>
                                        Localizar Alunos
                                    </h2>
                                    <p class="form-text text-center m-0 p-0 mt-1">
                                        Selecione um ou mais alunos para efetuar sua matrícula.
                                    </p>
                                </button>
                            </h2>
                            <div id="accordion-search-collapse" class="accordion-collapse collapse"
                                aria-labelledby="accordion-search-header" data-bs-parent="#accordion-search">
                                <div class="accordion-body p-4">
                                    <div class="row g-3">
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-name" class="form-label">Nome</label>
                                            <input type="text" name="name" class="form-control" id="s-name"
                                                placeholder="Buscar por parte do nome"
                                                @keydown.enter.prevent="search_students"
                                                v-model="pgdata.search_students.search.name">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-sige" class="form-label">ID. Sige</label>
                                            <input type="text" name="sige" class="form-control" id="s-sige"
                                                @keydown.enter.prevent="search_students" placeholder="00000000"
                                                v-model="pgdata.search_students.search.id_sige">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-cpf" class="form-label">CPF</label>
                                            <input type="text" name="cpf" class="form-control" id="s-cpf"
                                                @keydown.enter.prevent="search_students" placeholder="000.000.000-00"
                                                v-model="pgdata.search_students.search.cpf" v-maska:[masks.maskcpf]>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-4">
                                        <button type="button" @click="search_students"
                                            class="btn btn-form btn-accept d-flex align-items-center">
                                            <ion-icon name="search-outline" class="fs-6 me-1"></ion-icon>
                                            Localizar Alunos
                                        </button>
                                    </div>
                                    <div class="mt-5 form-neg-box-min">
                                        <TableListSelect identify="students_reg" :sent="pgdata.search_students.sent"
                                            :header="pgdata.search_students.header" :body="pgdata.search_students.body"
                                            :casts="{ status: pgdata.selects.students_status }"
                                            v-model="pgdata.data.students" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse mt-4">
                        <button @click="page.ui('list')" type="button"
                            class="btn btn-form btn-warning d-flex align-items-center">
                            <ion-icon name="close-circle-outline" class="fs-6 me-1"></ion-icon>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-form btn-accept d-flex align-items-center mx-2">
                            <ion-icon name="checkmark-circle-outline" class="fs-6 me-1"></ion-icon>
                            Salvar
                        </button>
                    </div>
                </form>
            </div>

        </main>

        <nav class="nav-view">
            <NavMainComp />
        </nav>
    </div>
</template>
