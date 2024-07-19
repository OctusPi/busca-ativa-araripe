<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" @click="toggleMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" :class="{ show: isMenuOpen }">
                <ul class="navbar-nav">
                    <li v-for="item in menuItems" :key="item.to" class="nav-item">
                        <RouterLink :to="item.to" class="nav-link d-flex flex-column align-items-center">
                            <img :src="item.iconPath" alt="" class="nav-icon">
                            <span>{{ item.title }}</span>
                        </RouterLink>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'MainNav',
    setup() {
        const isMenuOpen = ref(false);
        const menuItems = [
            { iconPath: new URL('@/assets/icons/settings.png', import.meta.url).href, title: 'Gestão', to: '/manager' },
            { iconPath: new URL('@/assets/icons/users.png', import.meta.url).href, title: 'Usuários', to: '/users' },
            { iconPath: new URL('@/assets/icons/building.png', import.meta.url).href, title: 'Orgãos', to: '/organs' },
            { iconPath: new URL('@/assets/icons/school.png', import.meta.url).href, title: 'Escolas', to: '/schools' },
            { iconPath: new URL('@/assets/icons/calendar.png', import.meta.url).href, title: 'Séries/Anos', to: '/series' },
            { iconPath: new URL('@/assets/icons/classroom.png', import.meta.url).href, title: 'Turmas', to: '/classes' },
            { iconPath: new URL('@/assets/icons/book.png', import.meta.url).href, title: 'Disciplinas', to: '/subjects' },
            { iconPath: new URL('@/assets/icons/student.png', import.meta.url).href, title: 'Estudantes', to: '/students' },
            { iconPath: new URL('@/assets/icons/registration.png', import.meta.url).href, title: 'Matrículas', to: '/registrations' },
            { iconPath: new URL('@/assets/icons/teatcher.png', import.meta.url).href, title: 'Professores', to: '/teachers' },
            { iconPath: new URL('@/assets/icons/education.png', import.meta.url).href, title: 'Educacional', to: '/grids' },
            { iconPath: new URL('@/assets/icons/attendance.png', import.meta.url).href, title: 'Frequencias', to: '/frequencies' },
            { iconPath: new URL('@/assets/icons/report.png', import.meta.url).href, title: 'Relatórios', to: '/reports' }
        ];


        function toggleMenu() {
            isMenuOpen.value = !isMenuOpen.value;
        }

        return {
            isMenuOpen,
            menuItems,
            toggleMenu
        };
    }
};
</script>

<style scoped>
    .nav-link {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .nav-link i {
        font-size: 14px; 
    }
    .nav-link span {
        font-size: 14px;
    }
</style>
