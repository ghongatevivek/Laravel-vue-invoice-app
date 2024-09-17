<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Manage Products</h2>
                </div>
                <div>
                    <a class="btn btn-secondary" @click="createInvoice()">
                        Manage Invoice
                    </a>
                </div>
            </div>

            <div class="table card__content">

                <div class="table--search">
                    <div class="table--search--wrapper">
                        <select class="table--search--select" name="" id="">
                            <option value="">Filter</option>
                        </select>
                    </div>
                    <div class="relative">
                        <i class="table--search--input--icon fas fa-search "></i>
                        <input class="table--search--input" type="text" placeholder="Search product" v-model="searchInvoice" @keyup="search()">
                    </div>
                </div>

                <div class="table--heading">
                    <p>ID</p>
                    <p>Item code</p>
                    <p>Description</p>
                    <p>Price</p>
                </div>

                <!-- item 1 -->
                <div class="table--items" v-for="item in products" :key="item.id" v-if="products.length">
                    <a href="#" class="table--items--transactionId">#{{item.id}}</a>
                    <p>{{ item.item_code }}</p>
                    <p>{{ item.description }}</p>
                    <p> ₹ {{ item.price }}</p>
                </div>
                <div class="table--items" v-else>
                    <p class="item-empty">Invoice not found </p>
                </div>
            </div>


        </div>
    </div>

</template>

<script setup>
    import { onMounted, ref } from "vue"

    import { useRouter } from 'vue-router'

    const router = useRouter()
    let products = ref([])
    let searchInvoice = ref([])

    onMounted(async => {
        getProductsList()
    })

    const getProductsList = async () => {
        let result = await axios.get("/api/products")
        //console.log(result)
        products.value = result.data.products
    }

    // const search = async () => {
    //     let result = await axios.get("api/get_all_invoices?search="+searchInvoice.value)
    //     //console.log(result)
    //     invoices.value = result.data.invoices
    // }

</script>