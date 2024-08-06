<script setup>
import { onMounted, ref, watch } from 'vue';

import Page from '@/services/page';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';
import masks from '@/utils/masks';

const pgdata = ref({
    baseURL: '/teachers',
    uiview: { search: false, register: false },
    data: {},
    search: {},
    dataheader: [
        { key: 'name', title: 'NOME', sub: [{ key: 'cpf' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'qualification', cast: 'title', title: 'QUALIFICAÇÃO', sub: [{ key: 'degree' }] },
        { key: 'organ', cast: 'name', title: 'ORGANIZAÇÃO' }
    ],
    datalist: [],
    selects: {
        qualifications: [],
        organs: [],
    },
    rules: {
        fields: {
            name: 'required',
            cpf: 'required',
            organ: 'required'
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
                        <p class="m-0 p-0 form-text">Professores credenciados ao Órgão</p>
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
                <HeaderBoxUiComp icon="search-outline" title="Encontrar Professores"
                    desc="Aplique os filtros para localizar registros de professores." />

                <form class="form-row" @submit.prevent="page.list">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <label for="s-name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" id="s-name"
                                placeholder="Buscar por parte do nome" v-model="pgdata.search.name">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label for="s-cpf" class="form-label">CPF</label>
                            <input type="text" name="s-cpf" class="form-control" id="s-cpf" placeholder="000.000.000-00"
                                v-model="pgdata.search.cpf" v-maska:[masks.maskcpf]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-qualification" class="form-label">Qualificação</label>
                            <select name="s-qualification" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.qualification }"
                                id="s-qualification" v-model="pgdata.search.qualification">
                                <option></option>
                                <option v-for="s in pgdata.selects.qualifications" :key="s.id" :value="s.id">{{ s.title
                                    }}
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
                <HeaderBoxUiComp icon="people-outline" title="Professores Credenciados"
                    desc="Visualize e gerencie a lista de professores credenciados ao órgão." />

                <div class="form-neg-box">
                    <TableList :header="pgdata.dataheader" :body="pgdata.datalist" :actions="['update', 'delete']"
                        :casts="{
                            qualification: pgdata.selects.qualifications,
                            organ: pgdata.selects.organs
                        }" @action:update="page.update" @action:delete="page.remove" />
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="body-outline" title="Cadastro de Professores"
                    desc="Gerenciamento de cadastro dos Professores" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
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
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Nome Completo do Professor (a)" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.cpf }" id="cpf"
                                placeholder="123.456.789-00" v-model="pgdata.data.cpf" v-maska:[masks.maskcpf]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.email }" id="email"
                                placeholder="prof@example.com" v-model="pgdata.data.email">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="phone" class="form-label">Fone</label>
                            <input type="text" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.phone }" id="phone"
                                placeholder="(00) 91234-5678" v-model="pgdata.data.phone" v-maska:[masks.maskphone]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="qualification" class="form-label">Qualificação</label>
                            <select name="qualification" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.qualification }" id="qualification"
                                v-model="pgdata.data.qualification">
                                <option></option>
                                <option v-for="s in pgdata.selects.qualifications" :key="s.id" :value="s.id">{{ s.title
                                    }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="degree" class="form-label">Formação</label>
                            <input type="text" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.degree }" id="degree"
                                placeholder="Licenciatura em Letras" v-model="pgdata.data.degree">
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


<style></style>