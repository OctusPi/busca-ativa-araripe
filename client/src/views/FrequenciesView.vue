<script setup>
import { onMounted, ref, watch } from 'vue';

import Page from '@/services/page';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';
import http from '@/services/http';
import notifys from '@/utils/notifys';
import InputDropMultSelect from '@/components/inputs/InputDropMultSelect.vue';

const pgdata = ref({
    baseURL: '/grids',
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
        teachers: [],
        days:[]
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

function update(id) {
    const grid = pgdata.value.datalist.find(obj => obj.id === id)

    if (grid) {
        http.get(`${pgdata.value.baseURL}/selects/classes/${grid.organ},${grid.school.id},${grid.serie.id}`, emit, (resp) => {
            pgdata.value.selects = resp.data
            pgdata.value.data = {
                id: id,
                organ: grid.organ,
                school: grid.school.id,
                serie: grid.serie.id,
                classe: grid.classe.id,
                subject: grid.subject.id,
                teacher: grid.teacher.id,
                days: grid.days
            }
            page.ui('update')
        });
        return
    }

    emit('callAlert', notifys.warning('Grade não localizada...'))

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
                        <h1 class="m-0 p-0">Gestão de Grade Curricular</h1>
                        <p class="m-0 p-0 form-text">Visualize e gerencie as a grade de curricular.</p>
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
                    desc="Use os filtros para localizar registros das grades." />

                <form class="form-row" @submit.prevent="page.list">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="s-organ" class="form-label">Órgão</label>
                            <select name="organ" class="form-control" @change="page.selects('organs', pgdata.search.organ)" id="s-organ"
                                v-model="pgdata.search.organ">
                                <option></option>
                                <option v-for="s in pgdata.selects.organs" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-school" class="form-label">Escola</label>
                            <select name="school" class="form-control" @change="page.selects('classes',`${pgdata.search.organ},${pgdata.search.school},${pgdata.search.serie}`)"
                                id="s-school" v-model="pgdata.search.school">
                                <option></option>
                                <option v-for="s in pgdata.selects.schools" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-serie" class="form-label">Série</label>
                            <select name="serie" class="form-control" @change="page.selects('classes', `${pgdata.search.organ},${pgdata.search.school},${pgdata.search.serie}`)" 
                            id="s-serie" v-model="pgdata.search.serie">
                                <option></option>
                                <option v-for="s in pgdata.selects.series" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control" id="s-classe" v-model="pgdata.search.classe">
                                <option></option>
                                <option v-for="s in pgdata.selects.classes" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-subject" class="form-label">Disciplina</label>
                            <select name="subject" class="form-control" id="s-subject" v-model="pgdata.search.subject">
                                <option></option>
                                <option v-for="s in pgdata.selects.subjects" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-teacher" class="form-label">Professor</label>
                            <select name="teacher" class="form-control" id="s-teacher" v-model="pgdata.search.teacher">
                                <option></option>
                                <option v-for="s in pgdata.selects.teachers" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
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

            <!-- BOX LIST -->
            <div v-if="!pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="keypad-outline" title="Grades Cadastradas."
                    desc="Visualize e gerencie a lista de grades cadastradas." />
                <div class="form-neg-box" v-if="pgdata.search.sent">
                    <TableList :header="pgdata.dataheader" :body="pgdata.datalist" :actions="['update', 'delete']"
                        :casts="{
                            organ: pgdata.selects.organs
                        }" @action:update="update" @action:delete="page.remove" />
                </div>
                <div class="text-center" v-else>
                    <ion-icon name="chatbox-ellipses-outline" class="form-text fs-3"></ion-icon>
                    <p class="form-text">Aplique o filtro na opção localizar para visualizar as grades...</p>
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="grid-outline" title="Cadastro de Grade"
                    desc="Registre os dados para as grades curriculares." />

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
                            <label for="teacher" class="form-label">Dias de Aula</label>
                            <InputDropMultSelect identify="days_grid" v-model="pgdata.data.days" :options="pgdata.selects.days" />
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
        </div>

        <div class="nav-view">
            <NavMainComp />
        </div>

    </div>

</template>