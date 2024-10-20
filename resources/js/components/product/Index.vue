<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Manage Products</h2>
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
                        <input class="table--search--input" type="text" placeholder="Search product" v-model="searchInvoice" @keyup="search()">
                    </div>
                </div>

                <div class="table--heading">
                    <p>Action</p>
                    <p>Item code</p>
                    <p>Description</p>
                    <p>Price</p>
                </div>

                <!-- item 1 -->
                <div class="table--items" v-for="item in products" :key="item.id" v-if="products.length">
                    <p>
                        <a href="javascript:void(0)" @click="editProduct(item.id)" style="cursor: pointer;margin: 5px;">Edit</a>
                        <a href="javascript:void(0)" @click="confirmDelete(item.id)" style="cursor: pointer;margin: 5px;">Delete</a>    
                    </p>
                    <p>{{ item.item_code }}</p>
                    <p>{{ item.description }}</p>
                    <p> ₹ {{ item.price }}</p>
                </div>
                <div class="table--items" v-else>
                    <p class="item-empty">Product not found </p>
                </div>
            </div>


        </div>

        <!--==================== add product modal ====================-->
        <div class="modal main__modal " :class="{show : showModel}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModel()">×</span>
                <h3 class="modal__title">Add Product</h3>
                <hr><br>
                <form @submit.prevent="saveProduct">
                <div class="">
                        <div>
                            <p class="my-1">Product Code</p>
                            <input placeholder="Product Code" type="text" class="input" v-model="productFrm.code" id="code" />
                        </div>
                        <div>
                            <p class="my-1">Product Description</p>
                            <textarea placeholder="Product Description" v-model="productFrm.description" class="textarea" id="description"></textarea>
                        </div>
                        <div>
                            <p class="my-1">Product Price</p>
                            <input placeholder="Product Price" type="text" class="input" v-model="productFrm.price" id="price" />
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

    const showModel = ref(false)
    const hideMode = ref(true)
    let productFrm = ref({
        code: '',
        price: '',
        description: ''
    })

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

    const openModel = () => {
        showModel.value = !showModel.value
        productFrm.value = {
            id: '',
            code: '',
            price: '',
            description: '',
        };
    }

    const closeModel = () => {
        showModel.value = !hideMode.value
    }

    const saveProduct = async () => {   
        try {
            const response = await axios.post('/api/save-product', productFrm.value);
            if(response.data.status == 200){
                closeModel()
                getProductsList()
            }else if(response.data.status == 500){
                alert(response.data.message);
            }
            
        } catch (error) {
            console.error('Error submitting form:', error);
        }
    }

    const invoiceList = () => {
        router.push('/');
    }

    const deleteProduct = async (productId) => {
        try {
            const response = await axios.delete(`/api/products/${productId}`);
            if(response.data.status == 200){
                getProductsList()
            }else if(response.data.status == 500){
                alert(response.data.message);
            }
            
        } catch (error) {
            console.error('Error submitting form:', error);
        }
    }

    const confirmDelete = (productId) => {
        if (confirm('Are you sure you want to delete this product?')) {
        deleteProduct(productId);
      }
    }

    // edit code 
    const editProduct =  async (productId) => {
        showModel.value = !showModel.value;
        try {
            // Fetch the product data based on productId
            const response = await axios.get(`/api/products/${productId}`);
        
            console.log(response.data);
            
            if (response.data.status === 200) {
            // Populate the form fields with the fetched product data
            productFrm.value = {
                id: response.data.product.id,
                code: response.data.product.item_code,
                price: response.data.product.price,
                description: response.data.product.description,
            };
            } else {
            alert('Error fetching product data');
            }
        } catch (error) {
            console.error('Error fetching product data:', error);
        }
    }

</script>