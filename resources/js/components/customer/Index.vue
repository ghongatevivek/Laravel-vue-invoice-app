<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Invoices</h2>
                </div>
                <div>
                    <a class="btn btn-secondary" @click="createInvoice()">
                        Manage Products
                    </a>
                    <a class="btn btn-secondary" @click="createInvoice()">
                        Manage Customers
                    </a>
                    <a class="btn btn-secondary" @click="createInvoice()">
                        Create
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
                        <input class="table--search--input" type="text" placeholder="Search customer" v-model="searchInvoice" @keyup="search()">
                    </div>
                </div>

                <div class="table--heading">
                    <p>ID</p>
                    <p>Name</p>
                    <p>Email</p>
                    <p>Address</p>
                </div>

                <!-- item 1 -->
                <div class="table--items" v-for="item in customers" :key="item.id" v-if="customers.length">
                    <a href="#" class="table--items--transactionId">#{{item.id}}</a>
                    <p>{{ item.first_name }} {{ item.last_name }}</p>
                    <p>#{{ item.email }}</p>
                    <p>{{ item.address }}</p>
                </div>
                <div class="table--items" v-else>
                    <p class="item-empty">Customer not found </p>
                </div>
            </div>


        </div>
    </div>

</template>

<script setup>
    import { onMounted, ref } from "vue"

    import { useRouter } from 'vue-router'

    const router = useRouter()
    let customers = ref([])
    let searchInvoice = ref([])

    onMounted(async => {
        getCustomersList()
    })

    const getCustomersList = async () => {
        let result = await axios.get("/api/customers")
        //console.log(result)
        customers.value = result.data.customers
    }
   
</script>