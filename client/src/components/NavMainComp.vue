<script setup>
import auth from '@/stores/auth';
import { ref } from 'vue';
import { RouterLink } from 'vue-router';

const user = ref(auth.getUser())
const show_menu = ref(false)

const nav_items = [
    { label: 'Frequência', icon:'/assets/icons/frequencies.svg', to: '/frequencies' },
    { label: 'Alunos', icon: '/assets/icons/students.svg', to: '/students' },
    { label: 'Matrículas', icon: '/assets/icons/registrations.svg', to: '/registrations' },
    { label: 'Séries', icon: '/assets/icons/series.svg', to: '/series' },
    { label: 'Turmas', icon: '/assets/icons/classes.svg', to: '/classes' },
    { label: 'Disciplinas', icon: '/assets/icons/subjects.svg', to: '/subjects' },
    { label: 'Professores', icon: '/assets/icons/teachers.svg', to: '/teachers' },
    { label: 'Grade', icon: '/assets/icons/grids.svg', to: '/grids' },
    {
        label: 'Gestão', icon: '/assets/icons/manager.svg', to: '/manager', sub: [
            { label: 'Orgãos', icon: '/assets/icons/organs.svg', to: '/organs' },
            { label: 'Escolas', icon: '/assets/icons/schools.svg', to: '/schools' },
            { label: 'Usuários', icon: '/assets/icons/users.svg', to: '/users' }
        ]
    },
    { label: 'Relatórios', icon: '/assets/icons/reports.svg', to: '/reports' },
];

function auth_access(rt){
    if(user.value.navigation){
        return (user.value.navigation).find(obj => {
            return obj.module == rt.replace('/', '')
        })
    }

    return false;
}



</script>
<template>
    <nav class="container px-3">
        <div v-if="user" class="nav-content" :class="{ 'min-view': !show_menu }">
            <ul class="nav-list">
                <li v-for="item in nav_items" :key="item.label">
                    <div v-if="auth_access(item.to)">
                        <div v-if="item.sub" class="dropdown">
                            <button class="nav-rtl" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img :src="item.icon" class="nav-icon" :alt="item.label">
                                <span>{{ item.label }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-for="sub_item in item.sub" :key="sub_item.label" class="nav-rtl-sub">
                                    <RouterLink v-if="auth_access(sub_item.to)" :to="sub_item.to" class="nav-rtl-sub-link">
                                        <img :src="sub_item.icon" class="nav-icon" :alt="sub_item.label">
                                        <span>{{ sub_item.label }}</span>
                                    </RouterLink>
                                </li>
                            </ul>
                        </div>
                        <RouterLink v-else :to="item.to" class="nav-rtl">
                            <img :src="item.icon" class="nav-icon" :alt="item.label">
                            <span>{{ item.label }}</span>
                        </RouterLink>
                    </div>
                </li>
            </ul>
        </div>
        <button class="nav-btntoggle" @click="show_menu = !show_menu">
            <img src="/assets/icons/btn-menu.svg">
        </button>
    </nav>
</template>



<style scoped>
.nav-btntoggle {
    border: none;
    background-color: transparent;
    width: 34px;
    height: 34px;
    display: none;
    margin: 5px 0 10px 0;
}

.nav-btntoggle img {
    width: 34px;
    height: 34px;
}

.nav-content {
    background-color: var(--bg-box-translucid);
    padding: 8px 0;
    border-radius: 30px;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-rtl {
    background-color: transparent;
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: var(--cl-txt);
    border-radius: 15px;
    padding: 10px;
    margin: 0 5px;
}

.nav-rtl:hover {
    color: var(--cl-txt-sec);
}

.nav-rtl span {
    font-size: 0.8rem;
    margin: 7px 0 0 0;
    padding: 0;
}

.nav-rtl img {
    width: 24px;
}

.dropdown-menu {
    background-color: var(--bg-box-translucid);
    border: none;
    padding: 15px;
}

.nav-rtl-sub {
    padding: 10px 15px;
    border-radius: 15px;
}

.nav-rtl-sub:hover {
    background-color: var(--bg-fundo);
    color: var(--cl-txt-sec);
}

.nav-rtl-sub-link {
    display: block;
    text-decoration: none;
    color: var(--cl-txt);
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: start;
}

.nav-rtl-sub-link img {
    width: 18px;
    margin-right: 10px
}

@media(max-width:992px) {
    .nav-btntoggle {
        display: block;
    }

    .nav-content {
        width: 260px;
    }

    .nav-list {
        flex-direction: column;
        justify-content: start;
        align-items: start;
        margin: 10px 20px;
    }

    .nav-list li {
        width: 100%;
    }

    .nav-rtl {
        flex-direction: row;
        justify-content: start;
        margin: 0;
        width: 100%;
    }

    .nav-rtl:hover {
        background-color: var(--bg-fundo);
        color: var(--cl-txt-sec);
    }

    .nav-rtl img {
        margin-right: 10px;
    }

    .nav-rtl span {
        margin: 0;
    }

    .dropdown-menu {
        width: 100%;
    }

    .min-view {
        display: none !important;
    }
}
</style>