<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import masks from '@/utils/masks';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/organs',
    uiview: { search: false, register:false },
    data: {},
    report: {
        title: null,
        description: null,
        types: {
            'schools':{icon:'home-outline', title:'Escolas', description:'Dados sobre estrura da escola'},
            'students':{icon:'school-outline', title:'Alunado', description:'Quantitativo de alunos por série'},
            'teachers':{icon:'people-outline', title:'Professores', description:'Quantitativo por formação'},
            'grids':{icon:'grid-outline', title:'Grade', description:'Dados de vinculo de professores por escola/série/turma/disciplina'},
            'fouls':{icon:'close-circle-outline', title:'Faltas', description:'Registro de faltas dentro de determinado período'},
        }
    },
    search:{},
    dataheader:[
    { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'cnpj' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'postalcity', title: 'LOCALIZAÇÃO', sub: [{ key: 'address' }] },
        { key: 'status', cast: 'title', title: 'STATUS' }
    ],
    datalist:[],
    selects: {
        organs: [],
        schools: [],
        status: []
    },
    rules: {
        fields: {
            name: 'required',
            cnpj: 'required',
            phone: 'required',
            email: 'required|email',
            address: 'required',
            postalcity: 'required',
            postalcode: 'required',
            status: 'required'
        },
        valids: {}
    }
})

const emit = defineEmits(['callAlert', 'callRemove'])
const props = defineProps({ datalist: { type: Array, default: () => [] } })
const page = new Page(pgdata, emit)


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
                        <h1 class="m-0 p-0">Emissão de Relatórios</h1>
                        <p class="m-0 p-0 form-text">Tabulação de dados registrados no sistema</p>
                    </div>
                </div>
            </header>

            <!-- BOX SEARCH -->
            <div class="box p-5 mb-3">
                <HeaderBoxUiComp 
                icon="search-outline" title="Localizar Registros"
                desc="Aplique os filtros para localizar os registros desejados" />
                
                <form class="form-row" @submit.prevent="page.list">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <label for="s-type_report" class="form-label">Tipo de relatório desejado</label>
                            <select name="type_report" class="form-control" id="s-type_report" v-model="pgdata.search.type_report">
                                <option v-for="(t,i) in pgdata.report.types" :key="i" :value="i">{{ `${t.title}: ${t.description}` }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- REPORT SCHOOLS -->
                    <div v-if="pgdata.search.type_report === 'schools'" class="row g-3">
                        <div class="col-sm-12 col-md-6">
                            <label for="s-organ" class="form-label">Orgão</label>
                            <select name="organ" class="form-control" id="s-organ" v-model="pgdata.search.organ">
                                <option></option>
                                <option v-for="o in pgdata.selects.organs" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="s-school" class="form-label">Escola</label>
                            <select name="school" class="form-control" id="s-school" v-model="pgdata.search.school">
                                <option></option>
                                <option v-for="o in pgdata.selects.schools" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                    </div>

                    <!-- REPORT STUDENTS -->
                    <div v-if="pgdata.search.type_report === 'students'" class="row g-3">
                        <div class="col-sm-12 col-md-6">
                            <label for="s-organ" class="form-label">Orgão</label>
                            <select name="organ" class="form-control" id="s-organ" v-model="pgdata.search.organ">
                                <option></option>
                                <option v-for="o in pgdata.selects.organs" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="s-school" class="form-label">Escola</label>
                            <select name="school" class="form-control" id="s-school" v-model="pgdata.search.school">
                                <option></option>
                                <option v-for="o in pgdata.selects.schools" :key="o.id" :value="o.id">{{o.name}}</option>
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
            <div class="box p-5 mb-3">
                <HeaderBoxUiComp 
                :icon="pgdata.report.types[pgdata.search.type_report]?.icon ?? 'document-text-outline'" :title="pgdata.report.types[pgdata.search.type_report]?.title ?? 'Selecione o relatório'"
                :desc="pgdata.report.types[pgdata.search.type_report]?.description ?? 'Os dados serão exibidos para export em PDF'" />

                <div class="form-neg-box" v-if="pgdata.search.sent">
                    <TableList 
                    :header="pgdata.dataheader" 
                    :body="pgdata.datalist"
                    :actions="['update', 'delete']"
                    :casts="{
                        status:pgdata.selects.status, 
                        profile:pgdata.selects.profiles,
                        passchange: [{ id: 0, title: 'Ativa' }, { id: 1, title: 'Mudança de Senha' }]
                    }"
                    @action:update="page.update"
                    @action:delete="page.remove"
                     />
                </div>
                <div class="text-center" v-else>
                    <ion-icon name="chatbox-ellipses-outline" class="form-text fs-3"></ion-icon>
                    <p class="form-text">Aplique o filtro na opção localizar para visualizar os dados...</p>
                </div>
            </div>

        </main>

        <nav class="nav-view">
            <NavMainComp />
        </nav>

    </div>
</template>