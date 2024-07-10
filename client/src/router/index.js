import { createRouter, createWebHistory } from 'vue-router'
import auth from '@/stores/auth'

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: '/',
			name: 'login',
			component: () => import('../views/LoginView.vue')
		},
		{
			path: '/recover',
			name: 'recover',
			component: () => import('../views/RecoverView.vue')
		},
		{
			path: '/renew/:token?',
			name: 'renew',
			component: () => import('../views/RenewView.vue')
		},
		{
			path: '/home',
			name: 'home',
			// meta: { auth: true },
			component: () => import('../views/HomeView.vue')
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
		
		try{
			const isAuthenticated = await auth.isLoggedIn(to.path)
			if (!isAuthenticated) {
				return '/'
			}
		}catch(e){
			return e.response?.status === 403 ? '/forbidden' :
			e.response?.status === 404 ? '/notfound' :'/'
		}
		
	}
})

export default router
