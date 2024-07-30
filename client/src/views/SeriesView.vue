<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import utils from '@/utils/utils';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/series',
    uiview: { search: false, register:false },
    data: {},
    search:{},
    dataheader:[
        { key: 'code', title: 'CÓDIGO' },
        { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'organ', cast:'name' }] },
        { key: 'course', cast:'title', title: 'ORGANIZAÇÃO', sub: [{ key: 'level', cast:'title' }] },
        { key: 'status', cast:'title', title: 'STATUS' }
    ],
    datalist:[],
    selects:{
        organs: [],
        courses:[],
        levels:[],
        status: []
    },
    rules: {
        fields: {
            organ: 'required',
            name: 'required',
            code: 'required',
            course: 'required',
            level: 'required',
            status: 'required'
        },
        valids: {}
    }
})

const emit = defineEmits(['callAlert', 'callRemove'])
const props = defineProps({ datalist: { type: Array, default: () => [] } })
const page = new Page(pgdata, emit)

function actionRegister(){
    page.ui('register')
    pgdata.value.data.code = utils.randCode(8)
}

watch(() => props.datalist, (newdata) => {
    pgdata.value.datalist = newdata
})

onMounted(() => {
    page.selects()
    page.list()
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
                        <h1 class="m-0 p-0">Gestão de Séries/Anos</h1>
                        <p class="m-0 p-0 form-text">Definição dos niveis de aprendizados</p>
                    </div>
                </div>
                <div class="page-actions d-flex align-items-center">
                    <button @click="actionRegister" type="button" class="btn btn-action d-flex align-items-center ms-2">
                        <ion-icon name="add-circle-outline" class="fs-6 me-1 btn-icon"></ion-icon>
                        <span>Adicionar</span>
                    </button>
                    <button @click="page.ui('search')" type="button" class="btn btn-action d-flex align-items-center ms-2">
                        <ion-icon name="search-outline" class="fs-6 me-1 btn-icon"></ion-icon>
                        <span>Localizar</span>
                    </button>
                </div>
            </header>

            <!-- BOX SEARCH -->
            <div v-if="pgdata.uiview.search" class="box p-5 mb-3">
                <HeaderBoxUiComp 
                icon="search-outline" title="Localizar Registros"
                desc="Aplique os filtros para localizar os registros desejados" />
                
                <form class="form-row" @submit.prevent="page.list">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="s-name" class="form-label">Série/Ano</label>
                            <input type="text" name="name" class="form-control" id="s-name"
                                placeholder="Nome definido para o nivel" v-model="pgdata.search.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-course" class="form-label">Curso</label>
                            <select name="s-course" class="form-control" id="s-course"
                                v-model="pgdata.search.course">
                                <option></option>
                                <option v-for="s in pgdata.selects.courses" :key="s.id" :value="s.id">{{ s.title }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-level" class="form-label">Nivel</label>
                            <select name="s-level" class="form-control" id="s-level"
                                v-model="pgdata.search.level">
                                <option></option>
                                <option v-for="s in pgdata.selects.levels" :key="s.id" :value="s.id">{{ s.title }}</option>
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
                <HeaderBoxUiComp 
                icon="keypad-outline" title="Estrutura Organica dos Niveis de Ensino"
                desc="Listagem das séries/anos registrados junto ao sistema" />

                <div class="form-neg-box">
                    <TableList 
                    :header="pgdata.dataheader" 
                    :body="pgdata.datalist"
                    :actions="['update', 'delete']"
                    :casts="{
                        organ:pgdata.selects.organs,
                        course:pgdata.selects.courses,
                        level:pgdata.selects.levels,
                        status:pgdata.selects.status
                    }"
                    @action:update="page.update"
                    @action:delete="page.remove"
                     />
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp 
                icon="layers-outline" title="Registro de Série/Ano"
                desc="Gerenciamento Organizacional de niveis de aprendizado." />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="code" class="form-label">Código</label>
                            <input type="text" name="code" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.code }" id="code"
                                placeholder="Definição Unidade" v-model="pgdata.data.code">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="name" class="form-label">Série/Ano</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Definição do Nivel" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="organ" class="form-label">Orgão</label>
                            <select name="organ" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.organ }" id="organ"
                                v-model="pgdata.data.organ">
                                <option></option>
                                <option v-for="s in pgdata.selects.organs" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="course" class="form-label">Curso</label>
                            <select name="course" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.course }" id="course"
                                v-model="pgdata.data.course">
                                <option></option>
                                <option v-for="s in pgdata.selects.courses" :key="s.id" :value="s.id">{{ s.title }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="level" class="form-label">Nivel</label>
                            <select name="level" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.level }" id="level"
                                v-model="pgdata.data.level">
                                <option></option>
                                <option v-for="s in pgdata.selects.levels" :key="s.id" :value="s.id">{{ s.title }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.status }" id="status"
                                v-model="pgdata.data.status">
                                <option></option>
                                <option v-for="s in pgdata.selects.status" :key="s.id" :value="s.id">{{ s.title }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse mt-4">
                        <button @click="page.ui('list')" type="button" class="btn btn-form btn-warning d-flex align-items-center">
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
