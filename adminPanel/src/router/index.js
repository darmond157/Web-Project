import { createRouter, createWebHashHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    redirect: '/Login',
  },
  {
    path: '/Login',
    name: 'Login',
    component: () => import('@/pages/Login'),
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/components/DefaultLayout'),
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('@/pages/Dashboard.vue'),
      },
    ],
  },
  {
    path: '/users',
    name: 'users',
    component: () => import('@/components/DefaultLayout'),
    children: [
      {
        path: '/users',
        name: 'Users',
        component: () => import('@/pages/Users.vue'),
      },
    ],
  },
  {
    path: '/products',
    name: 'products',
    component: () => import('@/components/DefaultLayout'),
    children: [
      {
        path: '/products',
        name: 'Products',
        component: () => import('@/pages/Products.vue'),
      },
    ],
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('@/components/DefaultLayout'),
    children: [
      {
        path: '/categories',
        name: 'Categories',
        component: () => import('@/pages/Categories.vue'),
      },
    ],
  },
  {
    path: '/logs',
    name: 'logs',
    component: () => import('@/components/DefaultLayout'),
    children: [
      {
        path: '/logs',
        name: 'Logs',
        component: () => import('@/pages/Logs.vue'),
      },
    ],
  },

]

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

export default router
