<script setup>
import { onMounted, ref, watch } from 'vue'

import Page from '@/services/page';
import masks from '@/utils/masks';
import dates from '@/utils/dates';

import HeaderMainComp from '@/components/HeaderMainComp.vue'
import NavMainComp from '@/components/NavMainComp.vue'
import HeaderBoxUiComp from '@/components/HeaderBoxUiComp.vue';
import TableList from '@/components/TableList.vue';

const pgdata = ref({
    baseURL: '/registrations',
    uiview: { search: false, register: false },
    data: {},
    dataimport: {
        info: 'Selecione um arquivo *.json',
        organ: null,
        school: null,
        serie: null,
        classe: null,
        year: null,
        years: dates.listYears(2022),
        header: [
            { key: 'name', title: 'NOME' },
            { key: 'birth', title: 'NASCIMENTO' },
            { key: 'cpf', title: 'CPF' },
            { key: 'id_censo', title: 'ID CENSO' },
            { key: 'id_sige', title: 'ID SIGE' },
            { key: 'street', title: 'ENDEREÇO', sub: [{ key: 'neighborhood' }, { key: 'city' }, { key: 'cep' }] },
            { key: 'mother', title: 'RESPOSÁVEIS', sub: [{ key: 'father' }] },
            { title: '' }
        ],
        import: []
    },
    search: {
        sent: false,
        modality: 'student'
    },
    dataheader: [
        { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'id_sige', title: 'ID. Sige: ' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'city', title: 'LOCALIZAÇÃO', sub: [{ key: 'street' }, { key: 'neighborhood' }] },
        { key: 'mother', title: 'RESPOSÁVEIS', sub: [{ key: 'father' }] },
        { key: 'status', cast: 'title', title: 'STATUS' }
    ],
    datalist: [],
    selects: {
        schools: [],
        series: [],
        classes: [],
        status: [],
    },
    selects_loc: {
        years: dates.listYears(2022)
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

function list(modality = null) {
    if (modality === 'student') {
        return;
    }

    return;
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
                        <h1 class="m-0 p-0">Gestão de Matrículas</h1>
                        <p class="m-0 p-0 form-text">Controle de matrículas por ano letivo</p>
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
                    desc="Aplique os filtros para localizar os registros desejados" />

                <div class="accordion my-4" id="accordion-search">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="accordion-search-header">
                            <button class="w-100 text-center px-2 py-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-search-collapse" aria-expanded="false"
                                aria-controls="accordion-search-collapse">
                                <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                    <ion-icon name="copy-outline" class="fs-6 me-1"></ion-icon>
                                    Localizar Matriculas por Vínculo
                                </h2>
                                <p class="form-text text-center m-0 p-0 mt-1">
                                    Localizar por Escola/Serie/Turma/Ano Letivo
                                </p>
                            </button>
                        </h2>
                        <div id="accordion-search-collapse" class="accordion-collapse collapse show"
                            aria-labelledby="accordion-search-header" data-bs-parent="#accordion-search">
                            <div class="accordion-body p-4">
                                <form class="form-row" @submit.prevent="list">
                                    <div class="row g-3">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="s-school" class="form-label">Escola</label>
                                            <select @change="page.selects('classe', `${pgdata.search.school},${pgdata.search.serie}`)" name="school" class="form-control" id="s-school" v-model="pgdata.search.school">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.schools" :key="s.id" :value="s.id">{{ s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label for="s-serie" class="form-label">Série</label>
                                            <select @change="page.selects('classe', `${pgdata.search.school},${pgdata.search.serie}`)" name="serie" class="form-control" id="s-serie" v-model="pgdata.search.serie">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.series" :key="s.id" :value="s.id">{{ s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label for="s-classe" class="form-label">Turma</label>
                                            <select name="classe" class="form-control" id="s-classe" v-model="pgdata.search.classe">
                                                <option></option>
                                                <option v-for="s in pgdata.selects.classes" :key="s.id" :value="s.id">{{ s.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label for="s-year" class="form-label">Ano Letivo</label>
                                            <select name="year" class="form-control" id="s-year" v-model="pgdata.search.year">
                                                <option></option>
                                                <option v-for="s in pgdata.selects_loc.years" :key="s" :value="s">{{ s }}</option>
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
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="accordion-search-header">
                            <button class="w-100 text-center px-2 py-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-search-collapse" aria-expanded="false"
                                aria-controls="accordion-search-collapse">
                                <h2 class="m-0 p-0 d-flex align-items-center justify-content-center">
                                    <ion-icon name="person-outline" class="fs-6 me-1"></ion-icon>
                                    Localizar Matrículas por Aluno
                                </h2>
                                <p class="form-text text-center m-0 p-0 mt-1">
                                    Visualizar todas as matriculas para o Estudante
                                </p>
                            </button>
                        </h2>
                        <div id="accordion-search-collapse" class="accordion-collapse collapse"
                            aria-labelledby="accordion-search-header" data-bs-parent="#accordion-search">
                            <div class="accordion-body p-4">
                                <form class="form-row" @submit.prevent="list('student')">
                                    <div class="row g-3">
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-name" class="form-label">Nome</label>
                                            <input type="text" name="name" class="form-control" id="s-name"
                                                placeholder="Buscar por parte do nome" v-model="pgdata.search.name">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-sige" class="form-label">ID. Sige</label>
                                            <input type="text" name="sige" class="form-control" id="s-sige"
                                                placeholder="00000000" v-model="pgdata.search.sige">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label for="s-cpf" class="form-label">CPF</label>
                                            <input type="text" name="cpf" class="form-control" id="s-cpf"
                                                placeholder="000.000.000-00" v-model="pgdata.search.cpf"
                                                v-maska:[masks.maskcpf]>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOX LIST -->
            <div v-if="!pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="keypad-outline" title="Lista de Matrículas"
                    desc="Listagem de matrículas por Escola/Série/Turma/Ano Letivo" />

                <div v-if="pgdata.search.sent" class="form-neg-box">
                    <TableList :header="pgdata.dataheader" :body="pgdata.datalist" :actions="['update', 'delete']"
                        :casts="{
                            organ: pgdata.selects.organs,
                            race: pgdata.selects.races,
                            sex: pgdata.selects.sexs,
                            status: pgdata.selects.status
                        }" @action:update="page.update" @action:delete="page.remove" />
                </div>

                <div class="text-center" v-else>
                    <ion-icon name="chatbox-ellipses-outline" class="form-text fs-3"></ion-icon>
                    <p class="form-text">Aplique o filtro na opção localizar para visualizar os alunos...</p>
                </div>
            </div>

            <!-- BOX REGISTER -->
            <div v-if="pgdata.uiview.register" class="box p-5 mb-3">
                <HeaderBoxUiComp icon="document-text-outline" title="Registro de Matrículas"
                    desc="Registro de matrículas por aluno" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">

                        
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
