<script setup>
import { onMounted, ref, watch } from 'vue';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import Page from '@/services/page';
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import InputDropMultSelect from '@/components/inputs/InputDropMultSelect.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/teachers',
    uiview: { search: false, register: false},
    data: {},
    search: {},
    dataheader: [
        { key: 'name', title: 'NOME' },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'qualification', title: 'QUALIFICAÇÃO'}
    ],
    datalist: [],
    selects: {
        qualifications:[]
    },
    rules: {
        fields: {
            name: 'required',
            cpf: 'required',
            phone: 'required',
            email: 'required|email',
            qualification: 'required'
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
                        <h1 class="m-0 p-0">Gestão de Professores</h1>
                        <p class="m-0 p-0 form-text">Credencimento de professores para acesso ao sistema</p>
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
                icon="people-outline" title="Professores Credênciados"
                desc="Listagem de professores registrados para acesso ao sistema" />

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
                icon="body-outline" title="Registro de Professores"
                desc="Gerenciamento de dados do Professor" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Nome" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.email }" id="email"
                                placeholder="user@example.com" v-model="pgdata.data.email">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control"
                                :class="{'form-control-alert' : pgdata.rules.valids.cpf }" id="cpf"
                                placeholder="123.456.789-00" v-model="pgdata.data.cpf">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="phone" class="form-label">Fone</label>
                            <input type="text" class="form-control"
                                :class="{'form-control-alert' : pgdata.rules.valids.phone }" id="phone"
                                placeholder="(00) 91234-5678" v-model="pgdata.data.phone">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="qualification" class="form-label">Qualificação</label>
                            <select name="qualification" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.qualification }" id="qualification"
                                v-model="pgdata.data.qualification">
                                <option></option>
                                <option v-for="s in pgdata.selects.qualifications" :key="s.id" :value="s.id">{{ s.title }}
                                </option>
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

        </div>


        <div class="nav-view">
            <NavMainComp />
        </div>

    </div>

</template>


<style></style>