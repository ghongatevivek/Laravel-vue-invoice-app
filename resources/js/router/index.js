import { createRouter, createWebHistory } from 'vue-router'

import invoiceIndex from '../components/invoices/Index.vue'
import newInvoice from '../components/invoices/New.vue'
import customerList from '../components/customer/Index.vue'
import productList from '../components/product/Index.vue'
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
        path: '/customers/list/',
        component: customerList,

    },
    {
        path: '/products/list/',
        component: productList,

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