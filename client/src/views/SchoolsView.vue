<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import masks from '@/utils/masks';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/schools',
    uiview: { search: false, register:false },
    data: {},
    search:{},
    dataheader:[
        { key: 'inep', title: 'INEP' },
        { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'organ', cast:'name' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'postalcity', title: 'LOCALIZAÇÃO', sub: [{ key: 'address' }] },
        { key: 'status', cast:'title', title: 'STATUS' }
    ],
    datalist:[],
    selects:{
        organs: [],
        status: []
    },
    rules: {
        fields: {
            organ: 'required',
            name: 'required',
            inep: 'required',
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
                        <h1 class="m-0 p-0">Gestão de Unidades de Ensino</h1>
                        <p class="m-0 p-0 form-text">Credencimento de unidades escolares vinculadas ao Orgão</p>
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
                            <label for="s-inep" class="form-label">Inep</label>
                            <input type="text" name="inep" class="form-control" id="s-inep"
                                placeholder="00000000" v-model="pgdata.search.inep">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-status" class="form-label">Status</label>
                            <select name="s-status" class="form-control" id="s-status"
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
                icon="keypad-outline" title="Unidades Escolares Credênciados"
                desc="Listagem de unidades escolares registrados junto ao sistema" />

                <div class="form-neg-box">
                    <TableList 
                    :header="pgdata.dataheader" 
                    :body="pgdata.datalist"
                    :actions="['update', 'delete']"
                    :casts="{
                        organ:pgdata.selects.organs,
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
                icon="home-outline" title="Registro de Unidades Escoladas"
                desc="Gerenciamento de Unidades Escolares Municipais" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">

                        <div class="col-sm-12 col-md-4">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Definição Unidade" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="inep" class="form-label">Inep</label>
                            <input type="text" name="inep" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.inep }" id="inep"
                                placeholder="0000-0000" v-model="pgdata.data.inep" v-maska:[masks.maskinep]>
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
                        
                        <div class="col-sm-12 col-md-8">
                            <label for="address" class="form-label">Endereço</label>
                            <input type="text" name="address" class="form-control" id="address"
                                placeholder="Logradouro, Num - Bairro" v-model="pgdata.data.address">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="phone" name="phone" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.phone }" id="phone"
                                placeholder="(00)9.0000-0000" v-model="pgdata.data.phone" v-maska:[masks.maskphone]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.email }" id="email"
                                placeholder="escola@example.com" v-model="pgdata.data.email">
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
