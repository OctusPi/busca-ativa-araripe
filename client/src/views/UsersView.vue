<script setup>
import { onMounted, ref, watch } from 'vue'

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import Page from '@/services/page';
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import InputDropMultSelect from '@/components/inputs/InputDropMultSelect.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/users',
    uiview: { search: false, register:false },
    data: {},
    search:{},
    dataheader:[
        { key: 'name', title: 'NOME' },
        { key: 'email', title: 'E-MAIL' },
        { key: 'profile', cast: 'title', title: 'PERFIL', sub: [{ key: 'passchange', cast: 'title', title: 'Sitiação Senha:' }] },
        { key: 'status', cast: 'title', title: 'STATUS', sub: [{ key: 'lastlogin', title: 'Ultimo Acesso:' }] }
    ],
    datalist:[],
    selects:{
        profiles:[],
        modules:[],
        organs:[],
        schools:[],
        status:[]
    },
    rules: {
        fields: {
            name: 'required',
            email: 'required|email',
            status: 'required',
            profile: 'required',
            modules: 'required'
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
                        <h1 class="m-0 p-0">Gestão de Usuários</h1>
                        <p class="m-0 p-0 form-text">Credencimento de usuários para acesso ao sistema</p>
                    </div>
                </div>
                <div class="page-actions d-flex align-items-center">
                    <button @click="page.ui('register')" type="button" class="btn btn-action d-flex align-items-center ms-2">
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
                            <label for="s-name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" id="s-name"
                                placeholder="Buscar por parte do nome" v-model="pgdata.search.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" id="s-email"
                                placeholder="user@example.com" v-model="pgdata.search.email">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-control" id="status"
                                v-model="pgdata.search.status">
                                <option></option>
                                <option v-for="s in pgdata.selects.status" :key="s.id" :value="s.id">{{ s.title }}</option>
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
                icon="people-outline" title="Usuários Credênciados"
                desc="Listagem de usuários registrados para acesso ao sistema" />

                <div class="form-neg-box">
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
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp 
                icon="body-outline" title="Registro de Usuários"
                desc="Gerenciamento de dados de Usuário" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Seu Tromba" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.email }" id="email"
                                placeholder="user@example.com" v-model="pgdata.data.email">
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
                        <div class="col-sm-12 col-md-4">
                            <label for="profile" class="form-label">Perfil</label>
                            <select name="profile" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.profile }" id="profile"
                                v-model="pgdata.data.profile">
                                <option></option>
                                <option v-for="p in pgdata.selects.profiles" :key="p.id" :value="p.id">{{ p.title }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="email" class="form-label">Módulos</label>
                            <InputDropMultSelect
                            v-model="pgdata.data.modules"
                            identify="modules" 
                            :options="pgdata.selects.modules" 
                            :valid="pgdata.rules.valids.modules"/>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="email" class="form-label">Orgãos</label>
                            <InputDropMultSelect 
                            v-model="pgdata.data.organs"
                            identify="organs" 
                            label="name"
                            :options="pgdata.selects.organs" 
                            :valid="pgdata.rules.valids.organs"/>
                        </div>
                        <div class="col-sm-12">
                            <label for="email" class="form-label">Escolas</label>
                            <InputDropMultSelect 
                            v-model="pgdata.data.schools"
                            identify="schools"
                            label="name"
                            :options="pgdata.selects.schools" 
                            :valid="pgdata.rules.valids.schools"/>
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
