import { createRouter, createWebHistory } from 'vue-router'

import invoiceIndex from '../components/invoices/Index.vue'
import newInvoice from '../components/invoices/New.vue'
import notFound from '../components/NotFound.vue'

const routes = [
    {
        path: '/',
        component: invoiceIndex,

    },
    {
        path: '/invoice/new/',
        component: newInvoice,

    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound,

    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router