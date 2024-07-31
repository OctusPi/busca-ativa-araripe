<script setup>
import { onMounted, ref, watch } from 'vue';

import Page from '@/services/page';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/subjects',
    uiview: { search: false, register: false },
    data: {},
    search: {},
    dataheader: [
        { key: 'organ', cast: 'name', title: "ÓRGÃO" },
        { key: 'name', title: "NOME" },
        { key: 'area', cast: 'title', title: "ÁREA" },
        { key: 'description', title: "DESCRIÇÂO" },
    ],
    datalist: [],
    selects: {
        areas: [],
        organs: []
    },
    rules: {
        fields: {
            name: 'required',
            organ: 'required',
            area: 'required',
            description: 'required'
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
    page.list()
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
                        <h1 class="m-0 p-0">Gestão de Disciplinas</h1>
                        <p class="m-0 p-0 form-text">Visualize e gerencie as disciplinas.</p>
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
                <HeaderBoxUiComp icon="search-outline" title="Buscar Disciplinas"
                    desc="Use os filtros para localizar registros de disciplinas." />

                <form class="form-row" @submit.prevent="page.list">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-6">
                            <label for="s-name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" id="s-name"
                                placeholder="Buscar por parte do nome" v-model="pgdata.search.name">
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label for="s-description" class="form-label">Descrição</label>
                            <input type="text" name="name" class="form-control" id="s-description"
                                placeholder="Buscar por parte da descrição" v-model="pgdata.search.description">
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
                <HeaderBoxUiComp icon="people-outline" title="Disciplinas Cadastradas."
                    desc="Visualize e gerencie a lista de disciplinas cadastradas." />

                <div class="form-neg-box">
                    <TableList :header="pgdata.dataheader" :body="pgdata.datalist" :actions="['update', 'delete']"
                        :casts="{
                            area: pgdata.selects.areas,
                            organ: pgdata.selects.organs
                        }" @action:update="page.update" @action:delete="page.remove" />
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="body-outline" title="Cadastro de Disciplinas"
                    desc="Cadastre uma nova disciplina." />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name" placeholder="Nome"
                                v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="organ" class="form-label">Órgão</label>
                            <select name="organ" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.organ }" id="organ"
                                v-model="pgdata.data.organ">
                                <option></option>
                                <option v-for="s in pgdata.selects.organs" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="area" class="form-label">Área</label>
                            <select name="area" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.area }" id="area"
                                v-model="pgdata.data.area">
                                <option></option>
                                <option v-for="s in pgdata.selects.areas" :key="s.id" :value="s.id">{{ s.title }}
                                </option>
                            </select>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm col-md">
                            <label for="description" class="form-label">Descrição</label>
                            
                            <textarea class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.description }" id="description"
                                placeholder="Insira a descrição." v-model="pgdata.data.description" name="description" rows="3">
                            </textarea>
                                
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