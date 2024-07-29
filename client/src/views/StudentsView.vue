<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import masks from '@/utils/masks';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/students',
    uiview: { search: false, register: false },
    data: {},
    search: {},
    dataheader: [
        { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'sige', title: 'ID. Sige: ' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'city', title: 'LOCALIZAÇÃO', sub: [{ key: 'street' }, { key: 'neighborhood' }] },
        { key: 'mother', title: 'RESPOSÁVEIS', sub: [{ key: 'father' }] },
        { key: 'status', cast: 'title', title: 'STATUS' }
    ],
    datalist: [],
    selects: {
        organs: [],
        status: []
    },
    rules: {
        fields: {
            organ: 'required',
            name: 'required',
            birth: 'required',
            sige: 'required',
            mother: 'required',
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
    <div class="modal fade" id="modalImportStudents" tabindex="-1" aria-labelledby="modalImportStudentsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content p-4">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5" id="modalImportStudentsLabel">Importar Lote de Alunos</h1>
                    <button type="button" class="ms-auto" data-bs-dismiss="modal" aria-label="Close">
                        <ion-icon name="close-outline" class="fs-5"></ion-icon>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-12">
                            <label for="datafile" class="form-label">Selecionar Arquivo (*.json)</label>
                            <div class="ct-file-import">
                                <p class="file-description">Data temp view file</p>
                                <input type="file" name="datafile" class="input-file-import" accept="application/json">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-form btn-danger d-flex align-items-center"
                        data-bs-dismiss="modal">
                        <ion-icon name="close-circle-outline" class="fs-6 me-1"></ion-icon>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-form btn-accept d-flex align-items-center mx-2">
                        <ion-icon name="checkmark-circle-outline" class="fs-6 me-1"></ion-icon>
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                        <h1 class="m-0 p-0">Gestão de Dados dos Alunos</h1>
                        <p class="m-0 p-0 form-text">Credencimento de alunos da rede municipal</p>
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
                    <button type="button" class="btn btn-action d-flex align-items-center ms-2" data-bs-toggle="modal"
                        data-bs-target="#modalImportStudents">
                        <ion-icon name="download-outline" class="fs-6 me-1 btn-icon"></ion-icon>
                        <span>Importar</span>
                    </button>
                </div>
            </header>

            <!-- BOX SEARCH -->
            <div v-if="pgdata.uiview.search" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="search-outline" title="Localizar Registros"
                    desc="Aplique os filtros para localizar os registros desejados" />

                <form class="form-row" @submit.prevent="page.list">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-4">
                            <label for="s-name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" id="s-name"
                                placeholder="Buscar por parte do nome" v-model="pgdata.search.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-sige" class="form-label">ID. Sige</label>
                            <input type="text" name="sige" class="form-control" id="s-sige" placeholder="00000000"
                                v-model="pgdata.search.sige">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-cpf" class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control" id="s-cpf" placeholder="000.000.000-00"
                                v-model="pgdata.search.cpf" v-maska:[masks.maskcpf]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-mother" class="form-label">Nome da Mãe</label>
                            <input type="text" name="mother" class="form-control" id="s-mother"
                                placeholder="Buscar por parte do nome da mãe" v-model="pgdata.search.mother">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-birth" class="form-label">Data de Nascimento</label>
                            <input type="text" name="birth" class="form-control" id="s-birth" placeholder="DD/MM/AAAA"
                                v-model="pgdata.search.birth">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="s-status" class="form-label">Status</label>
                            <select name="s-status" class="form-control" id="s-status" v-model="pgdata.search.status">
                                <option></option>
                                <option v-for="s in pgdata.selects.status" :key="s.id" :value="s.id">{{ s.title }}
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
                <HeaderBoxUiComp icon="keypad-outline" title="Lista de Alunos Registrados"
                    desc="Listagem de alunos registrados junto ao sistema" />

                <div class="form-neg-box">
                    <TableList :header="pgdata.dataheader" :body="pgdata.datalist" :actions="['update', 'delete']"
                        :casts="{
                            organ: pgdata.selects.organs,
                            race: pgdata.selects.races,
                            sex: pgdata.selects.sexs,
                            status: pgdata.selects.status
                        }" @action:update="page.update" @action:delete="page.remove" />
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="people-outline" title="Registro de Alunos"
                    desc="Gestão de informações dos Alunos" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">

                        <div class="col-sm-12 col-md-4">
                            <label for="organ" class="form-label">Orgão</label>
                            <select name="organ" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.organ }" id="organ"
                                v-model="pgdata.data.organ">
                                <option></option>
                                <option v-for="s in pgdata.selects.organs" :key="s.id" :value="s.id">{{ s.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Nome Completo do Aluno" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="birth" class="form-label">Data de Nascimento</label>
                            <input type="text" name="birth" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.birth }" id="birth"
                                placeholder="DD/MM/AAAA" v-model="pgdata.data.birth" v-maska:[masks.maskdate]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="races" class="form-label">Raça</label>
                            <select name="races" class="form-control" id="races" v-model="pgdata.data.race">
                                <option></option>
                                <option v-for="s in pgdata.selects.races" :key="s.id" :value="s.id">{{ s.title }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="sex" class="form-label">Sexo</label>
                            <select name="sex" class="form-control" id="sex" v-model="pgdata.data.sex">
                                <option></option>
                                <option v-for="s in pgdata.selects.sexs" :key="s.id" :value="s.id">{{ s.title }}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="sige" class="form-label">ID. Sige</label>
                            <input type="text" name="sige" class="form-control" id="sige"
                                :class="{ 'form-control-alert': pgdata.rules.valids.sige }" placeholder="0000000000000"
                                v-model="pgdata.data.sige">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="censo" class="form-label">ID. Censo</label>
                            <input type="text" name="censo" class="form-control" id="censo" placeholder="0000000000000"
                                v-model="pgdata.data.censo">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control" id="cpf" placeholder="000.000.000-00"
                                v-model="pgdata.data.cpf" v-maska:[masks.maskcpf]>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="nis" class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" id="nis" placeholder="0000000000000"
                                v-model="pgdata.data.nis" v-maska:[masks.masknis]>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <label for="street" class="form-label">Endereço</label>
                            <input type="text" name="street" class="form-control" id="street"
                                placeholder="Logradouro, Num" v-model="pgdata.data.street">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="neighborhood" class="form-label">Bairro</label>
                            <input type="text" name="neighborhood" class="form-control" id="neighborhood"
                                v-model="pgdata.data.neighborhood">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="city" class="form-label">Cidade</label>
                            <input type="text" name="city" class="form-control" id="city" v-model="pgdata.data.city">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control" id="cep" placeholder="00000-000"
                                v-model="pgdata.data.cep" v-maska:[masks.maskcep]>
                        </div>
                        <div class="col-sm-12">
                            <label for="mother" class="form-label">Nome da Mãe/Responsável</label>
                            <input type="text" name="mother" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.mother }" id="mother"
                                placeholder="Nome da Mãe Completo" v-model="pgdata.data.mother">
                        </div>
                        <div class="col-sm-12">
                            <label for="father" class="form-label">Nome do Pai</label>
                            <input type="text" name="father" class="form-control" id="father"
                                placeholder="Nome da Mãe Completo" v-model="pgdata.data.father">
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
                                placeholder="aluno@example.com" v-model="pgdata.data.email">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.status }" id="status"
                                v-model="pgdata.data.status">
                                <option></option>
                                <option v-for="s in pgdata.selects.status" :key="s.id" :value="s.id">{{ s.title }}
                                </option>
                            </select>
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

<style scoped>
.ct-file-import {
    position: relative !important;
    width: 100%;
    height: 100px;
    padding: 20px;
    line-height: 50px;
    text-align: center;
    border: 2px dashed var(--cl-base);
    border-radius: 10px;
}

.file-description {
    color: var(--cl-txt-sec);
}

.input-file-import {
    position: absolute;
    width: 100%;
    height: 100px;
    left: 0;
    top: 0;
    opacity: 0;
    cursor: pointer;
}
</style>
