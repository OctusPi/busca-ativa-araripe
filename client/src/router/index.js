import auth from '@/stores/auth'
import utils from '@/utils/utils'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: () => import('@/views/LoginView.vue')
    },
    {
      path: '/recover',
      name: 'recover',
      component: () => import('@/views/RecoverView.vue')
    },
    {
      path: '/renew/:token?',
      name: 'renew',
      component: () => import('@/views/RenewView.vue')
    },
    {
      path: '/home',
      name: 'home',
      meta:{ auth:true },
      component: () => import('@/views/HomeView.vue')
    },
    {
      path: '/users',
      name: 'users',
      meta:{ auth:true },
      component: () => import('@/views/UsersView.vue')
    },
    {
      path: '/organs',
      name: 'organs',
      meta:{ auth:true },
      component: () => import('@/views/OrgansView.vue')
    },
    {
      path: '/schools',
      name: 'schools',
      meta:{ auth:true },
      component: () => import('@/views/SchoolsView.vue')
    },
    {
      path: '/frequencies',
      name: 'frequencies',
      meta:{ auth:true },
      component: () => import('@/views/FrequenciesView.vue')
    },
    {
      path: '/students',
      name: 'students',
      meta:{ auth:true },
      component: () => import('@/views/StudentsView.vue')
    },
    {
      path: '/registrations',
      name: 'registrations',
      meta:{ auth:true },
      component: () => import('@/views/RegistrationsView.vue')
    },
    {
      path: '/series',
      name: 'series',
      meta:{ auth:true },
      component: () => import('@/views/SeriesView.vue')
    },
    {
      path: '/classes',
      name: 'classes',
      meta:{ auth:true },
      component: () => import('@/views/ClassesView.vue')
    },
    {
      path: '/subjects',
      name: 'subjects',
      meta:{ auth:true },
      component: () => import('@/views/SubjectsView.vue')
    },
    {
      path: '/teachers',
      name: 'teachers',
      meta:{ auth:true },
      component: () => import('@/views/TeachersView.vue')
    },
    {
      path: '/grids',
      name: 'grids',
      meta:{ auth:true },
      component: () => import('@/views/GridsView.vue')
    },
    {
      path: '/reports',
      name: 'reports',
      meta:{ auth:true },
      component: () => import('@/views/ReportsView.vue')
    },
    {
			path: '/forbidden',
			name: 'forbidden',
			component: () => import('../views/ForbiddenView.vue')
		},
		{
			path: '/:pathMatch(.*)*',
			name: 'notfound',
			component: () => import('../views/NotFoundView.vue')
		}
  ]
})

router.beforeEach(async (to) => {
	if (to.meta?.auth) {
		utils.load(true)
		try {
			const isAuthenticated = await auth.isLoggedIn(to.path)
			if (!isAuthenticated) {
				return '/'
			}
		} catch (e) {
			return e.response?.status === 403 ? '/forbidden' :
				e.response?.status === 404 ? '/notfound' : '/'
		}finally{
			utils.load(false)
		}
	}
})

export default router
