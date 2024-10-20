<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Manage Customers</h2>
                </div>
                <div>
                    <a class="btn btn-secondary" @click="invoiceList()">
                        Home
                    </a>
                </div>
            </div>

            <div class="table card__content">
                <div>
                    <a class="btn btn-secondary" @click="openModel()">Create</a>
                </div>
                

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
                    <p>Action</p>
                    <p>Name</p>
                    <p>Email</p>
                    <p>Address</p>
                </div>

                <!-- item 1 -->
                <div class="table--items" v-for="item in customers" :key="item.id" v-if="customers.length">
                    <p>
                        <a href="javascript:void(0)" @click="editCustomer(item.id)" style="cursor: pointer;margin: 5px;">Edit</a>
                        <a href="javascript:void(0)" @click="confirmDelete(item.id)" style="cursor: pointer;margin: 5px;">Delete</a>    
                    </p>
                    <p>{{ item.first_name }} {{ item.last_name }}</p>
                    <p>#{{ item.email }}</p>
                    <p>{{ item.address }}</p>
                </div>
                <div class="table--items" v-else>
                    <p class="item-empty">Customer not found </p>
                </div>
            </div>
        </div>
        <!--==================== add customer modal ====================-->
        <div class="modal main__modal " :class="{show : showModel}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModel()">×</span>
                <h3 class="modal__title">Add Customer</h3>
                <hr><br>
                <form @submit.prevent="saveCustomer">
                <div class="">
                        <div>
                            <p class="my-1">First Name</p>
                            <input placeholder="First Name" type="text" class="input" v-model="customerFrm.first_name" id="first_name" />
                        </div>
                        <div>
                            <p class="my-1">Last Name</p>
                            <input placeholder="Last Name" type="text" class="input" v-model="customerFrm.last_name" id="last_name" />
                        </div>
                        <div>
                            <p class="my-1">Email</p>
                            <input placeholder="Email" type="text" class="input" v-model="customerFrm.email" id="email" />
                        </div>
                        <div>
                            <p class="my-1">Address</p>
                            <textarea placeholder="Address" v-model="customerFrm.address" class="textarea" id="address"></textarea>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="model__footer">
                        <button class="btn btn-light mr-2 btn__close--modal" @click="closeModel()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-light btn__close--modal ">Save</button>
                    </div>
                </form>
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
    const showModel = ref(false)
    const hideMode = ref(true)
    let customerFrm = ref({
        first_name: '',
        last_name: '',
        email: '',
        address: ''
    })

    onMounted(async => {
        getCustomersList()
    })

    const openModel = () => {
        showModel.value = !showModel.value
    }

    const closeModel = () => {
        showModel.value = !hideMode.value
    }

    const saveCustomer = async () => {   
        try {
            const response = await axios.post('/api/save-customer', customerFrm.value);
            if(response.data.status == 200){
                closeModel()
                getCustomersList()
            }else if(response.data.status == 500){
                alert(response.data.message);
            }
            
        } catch (error) {
            console.error('Error submitting form:', error);
        }
    }

    const getCustomersList = async () => {
        let result = await axios.get("/api/customers")
        //console.log(result)
        customers.value = result.data.customers
    }

    const invoiceList = () => {
        router.push('/');
    }

    const deleteCustomer = async (customerId) => {
        try {
            const response = await axios.delete(`/api/customers/${customerId}`);
            if(response.data.status == 200){
                getCustomersList()
            }else if(response.data.status == 500){
                alert(response.data.message);
            }
            
        } catch (error) {
            console.error('Error submitting form:', error);
        }
    }

    const confirmDelete = (customerId) => {
        if (confirm('Are you sure you want to delete this customer?')) {
            deleteCustomer(customerId);
      }
    }

    // edit code 
    const editCustomer =  async (customerId) => {
        showModel.value = !showModel.value;
        try {
            // Fetch the product data based on customerId
            const response = await axios.get(`/api/customers/${customerId}`);
            
            if (response.data.status === 200) {
            // Populate the form fields with the fetched product data
            customerFrm.value = {
                first_name: response.data.customer.first_name,
                last_name: response.data.customer.last_name,
                email: response.data.customer.email,
                address: response.data.customer.address,
                id: response.data.customer.id,
            };
            } else {
                alert('Error fetching customer data');
            }
        } catch (error) {
            console.error('Error fetching customer data:', error);
        }
    }
   
</script>