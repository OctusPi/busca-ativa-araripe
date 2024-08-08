<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import dates from '@/utils/dates';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/organs',
    uiview: { search: false, register:false },
    data: {},
    report: {
        type_report: null,
        types: {
            'schools':{icon:'home-outline', title:'Escolas', description:'Dados sobre estrura da escola'},
            'students':{icon:'school-outline', title:'Alunado', description:'Quantitativo de alunos por matrículas'},
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
        series: [],
        classes: [],
        qualifications: [],
        subjects:[],
        teachers:[],
        status: []
    },
    selects_loc: {
      years: dates.listYears()  
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

function choose_report() {
    page.selects()
    pgdata.value.search = {}
    pgdata.value.search.sent = false
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
                            <select @change="choose_report" name="type_report" class="form-control" id="s-type_report" v-model="pgdata.report.type_report">
                                <option v-for="(t,i) in pgdata.report.types" :key="i" :value="i">{{ `${t.title}: ${t.description}` }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- REPORT SCHOOLS -->
                    <div v-if="pgdata.report.type_report === 'schools'" class="row g-3">
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
                    <div v-if="pgdata.report.type_report === 'students'" class="row g-3">
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
                        <div class="col-sm-12 col-md-3">
                            <label for="s-serie" class="form-label">Série</label>
                            <select name="serie" class="form-control" id="s-serie" v-model="pgdata.search.serie">
                                <option></option>
                                <option v-for="o in pgdata.selects.series" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control" id="s-classe" v-model="pgdata.search.classe">
                                <option></option>
                                <option v-for="o in pgdata.selects.classes" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-year" class="form-label">Ano Letivo</label>
                            <select name="year" class="form-control" id="s-year" v-model="pgdata.search.year">
                                <option></option>
                                <option v-for="o in pgdata.selects_loc.years" :key="o" :value="o">{{o}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-status" class="form-label">Situação</label>
                            <select name="status" class="form-control" id="s-status" v-model="pgdata.search.status">
                                <option></option>
                                <option v-for="o in pgdata.selects.status" :key="o.id" :value="o.id">{{o.title}}</option>
                            </select>
                        </div>
                    </div>

                    <!-- REPORT TEACHERS -->
                    <div v-if="pgdata.report.type_report === 'teachers'" class="row g-3">
                        <div class="col-sm-12 col-md-6">
                            <label for="s-organ" class="form-label">Orgão</label>
                            <select name="organ" class="form-control" id="s-organ" v-model="pgdata.search.organ">
                                <option></option>
                                <option v-for="o in pgdata.selects.organs" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="s-qualification" class="form-label">Qualificação</label>
                            <select name="qualification" class="form-control" id="s-qualification" v-model="pgdata.search.qualification">
                                <option></option>
                                <option v-for="o in pgdata.selects.qualifications" :key="o.id" :value="o.id">{{o.title}}</option>
                            </select>
                        </div>
                    </div>

                    <!-- REPORT GRIDS -->
                    <div v-if="pgdata.report.type_report === 'grids'" class="row g-3">
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
                        <div class="col-sm-12 col-md-3">
                            <label for="s-serie" class="form-label">Série</label>
                            <select name="serie" class="form-control" id="s-serie" v-model="pgdata.search.serie">
                                <option></option>
                                <option v-for="o in pgdata.selects.series" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control" id="s-classe" v-model="pgdata.search.classe">
                                <option></option>
                                <option v-for="o in pgdata.selects.classes" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-subject" class="form-label">Disciplina</label>
                            <select name="subject" class="form-control" id="s-subject" v-model="pgdata.search.subject">
                                <option></option>
                                <option v-for="o in pgdata.selects.subjects" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-teacher" class="form-label">Professor</label>
                            <select name="teacher" class="form-control" id="s-teacher" v-model="pgdata.search.teacher">
                                <option></option>
                                <option v-for="o in pgdata.selects.teachers" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                    </div>

                    <!-- REPORT FOULS -->
                    <div v-if="pgdata.report.type_report === 'fouls'" class="row g-3">
                        <div class="col-sm-12 col-md-3">
                            <label for="classe" class="form-label">Data Inicial</label>
                            <VueDatePicker auto-apply v-model="pgdata.search.date_ini"
                                :input-class-name="pgdata.rules.valids.date_ini ? 'dp-custom-input-dtpk-alert' : 'dp-custom-input-dtpk'"
                                :enable-time-picker="false" format="dd/MM/yyyy" model-type="yyyy-MM-dd" locale="pt-br"
                                calendar-class-name="dp-custom-calendar" calendar-cell-class-name="dp-custom-cell"
                                menu-class-name="dp-custom-menu" />
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="classe" class="form-label">Data Final</label>
                            <VueDatePicker auto-apply v-model="pgdata.search.date_fin"
                                :input-class-name="pgdata.rules.valids.date_fin ? 'dp-custom-input-dtpk-alert' : 'dp-custom-input-dtpk'"
                                :enable-time-picker="false" format="dd/MM/yyyy" model-type="yyyy-MM-dd" locale="pt-br"
                                calendar-class-name="dp-custom-calendar" calendar-cell-class-name="dp-custom-cell"
                                menu-class-name="dp-custom-menu" />
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-student" class="form-label">Nome Aluno</label>
                            <input type="text" name="student" class="form-control" id="s-student"
                            placeholder="Pesquise por parte do nome" v-model="pgdata.search.student">
                                
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-id_sige" class="form-label">ID. Sige</label>
                            <input type="text" name="id_sige" class="form-control" id="s-id_sige"
                            placeholder="Pesquise por ID. SIGE" v-model="pgdata.search.id_sige">
                                
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="s-school" class="form-label">Escola</label>
                            <select name="school" class="form-control" id="s-school" v-model="pgdata.search.school">
                                <option></option>
                                <option v-for="o in pgdata.selects.schools" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-serie" class="form-label">Série</label>
                            <select name="serie" class="form-control" id="s-serie" v-model="pgdata.search.serie">
                                <option></option>
                                <option v-for="o in pgdata.selects.series" :key="o.id" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label for="s-classe" class="form-label">Turma</label>
                            <select name="classe" class="form-control" id="s-classe" v-model="pgdata.search.classe">
                                <option></option>
                                <option v-for="o in pgdata.selects.classes" :key="o.id" :value="o.id">{{o.name}}</option>
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
                :icon="pgdata.report.types[pgdata.report.type_report]?.icon ?? 'document-text-outline'" :title="pgdata.report.types[pgdata.report.type_report]?.title ?? 'Selecione o relatório'"
                :desc="pgdata.report.types[pgdata.report.type_report]?.description ?? 'Os dados serão exibidos para export em PDF'" />

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