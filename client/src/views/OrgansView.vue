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
    search:{},
    dataheader:[
    { key: 'name', title: 'IDENTIFICAÇÃO', sub: [{ key: 'cnpj' }] },
        { key: 'phone', title: 'CONTATO', sub: [{ key: 'email' }] },
        { key: 'postalcity', title: 'LOCALIZAÇÃO', sub: [{ key: 'address' }] },
        { key: 'status', cast: 'title', title: 'STATUS' }
    ],
    datalist:[],
    selects:{
        status:[]
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

function handleFile(event) {
	const file = event.target.files[0]
	if (file) {

		const reader  = new FileReader()
		reader.readAsDataURL(file)
		reader.onloadend = () =>{
			pgdata.value.data.logomarca = reader.result
		}
	}
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
                            <label for="s-cnpj" class="form-label">CNPJ</label>
                            <input type="text" name="cnpj" class="form-control" id="s-cnpj"
                                placeholder="00.000.000/0000-00" v-model="pgdata.search.cnpj" v-maska:[masks.maskcnpj]>
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
                icon="keypad-outline" title="Orgãos Credênciados"
                desc="Listagem de orgãos registrados junto ao sistema" />

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
                icon="home-outline" title="Registro de Orgãos"
                desc="Gerenciamento de Unidades Gestoras Municipais" />

                <form class="form-row" @submit.prevent="page.save">
                    <div class="row g-3">
                        <div class="col-sm-12 mb-2">
                            <div class="ct-logo mx-auto position-relative">
                                <img v-if="pgdata.data.logomarca" :src="pgdata.data.logomarca" alt="logomarca" class="img-logo">
                                <input @change="handleFile" type="file" name="logo" class="file-logo">
                            </div>
                            <p class="form-text text-center">Logomarca</p>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.name }" id="name"
                                placeholder="Definição Entidade" v-model="pgdata.data.name">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" name="cnpj" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.cnpj }" id="cnpj"
                                placeholder="00.000.000/0000-00" v-model="pgdata.data.cnpj" v-maska:[masks.maskcnpj]>
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
                        
                        <div class="col-sm-12 col-md-8">
                            <label for="address" class="form-label">Endereço</label>
                            <input type="text" name="address" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.address }" id="address"
                                placeholder="Logradouro, Num - Bairro" v-model="pgdata.data.address">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="postalcity" class="form-label">Cidade</label>
                            <input type="text" name="postalcity" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.postalcity }" id="postalcity"
                                placeholder="Cidade/UF" v-model="pgdata.data.postalcity">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="postalcode" class="form-label">CEP</label>
                            <input type="text" name="postalcode" class="form-control"
                                :class="{ 'form-control-alert': pgdata.rules.valids.postalcode }" id="postalcode"
                                placeholder="00000-000" v-model="pgdata.data.postalcode" v-maska:[masks.maskcep]>
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
                                placeholder="organ@example.com" v-model="pgdata.data.email">
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

<style scoped>
    .ct-logo{
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 2px dashed var(--cl-border);
        overflow: hidden;
        background-color: var(--bg-fundo)
    }

    .img-logo{
        height: 120px;
    }

    .file-logo{
        width: 120px;
        height: 120px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
    }
</style>
