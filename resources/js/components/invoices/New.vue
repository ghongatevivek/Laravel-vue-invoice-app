<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">New Invoice</h2>
                </div>
                <div>
                    <a class="btn btn-secondary" @click="invoiceList()">
                        List
                    </a>
                </div>
            </div>
            <form @submit.prevent="saveInvoice">
            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="form.customer_id">
                            <option value="" disabled>Select customer</option>
                            <option :value="cust.id" v-for="cust in allCustomersData" :key="cust.id">{{ cust.first_name }}</option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date">
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Invoice Number</p>
                        <input type="text" class="input" v-model="form.invoice_no">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items2" v-for="(itemCart, i) in listCart" :key="itemCart.id">
                        <p>#{{ itemCart.item_code }} {{ itemCart.description }} </p>
                        <p>
                            <input type="text" class="input" v-model="itemCart.price" readonly>
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemCart.quantity">
                        </p>
                        <p v-if="itemCart.quantity">
                            ₹ {{ (itemCart.quantity) * (itemCart.price) }}
                        </p>
                        <p v-else></p>

                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeCartItem(i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <a class="btn btn-sm btn__open--modal" @click="openModel()">
                            Add New Product
                        </a>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer">
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="3" v-model="form.terms_and_condition" class="textarea">Tax included in grand total</textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>₹ {{ subTotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>₹ {{ grandTotal() }}</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <button type="submit" class="btn btn-secondary">
                        Save
                    </button>
                </div>
            </div>
            </form>
        </div>
        <!--==================== add modal items ====================-->
        <div class="modal main__modal " :class="{show : showModel}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModel()">×</span>
                <h3 class="modal__title">Add Item</h3>
                <hr><br>
                <div class="modal__items">
                    <ul style="list-style: none;">
                        <li v-for="(item, i) in listProducts" :key="item.id" style="display:grid;grid-template-columns: 30px 350px 15px;align-items: center;padding-bottom: 5px;">
                            <p>{{ i++ }}</p>
                            <a href="#">{{ item.code }} {{ item.description }}</a>
                            <button @click="addCart(item)" style="border: 1px solid #e0e0e0; width: 35px; height: 35px;cursor: pointer;">+</button>
                        </li>
                    </ul>
                </div>
                <br>
                <hr>
                <div class="model__footer">
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModel()">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
    import axios from 'axios';
    import {onMounted, ref} from 'vue'
    import { useRouter } from 'vue-router'

    const router = useRouter()

    let form = ref([])
    let allCustomersData = ref([])
    let customer_id = ref([])
    let item = ref([])
    let listCart = ref([])

    const showModel = ref(false)
    const hideMode = ref(true)

    let listProducts = ref([])

    onMounted(async => {
        indexForm()
        getAllCustomers()
        getProducts( )
    })

    const indexForm = async () => {
        let response  = await axios.get('/api/create_invoice')
        // console.log(response);
        form.value = response.data.form_data

    }

    const getAllCustomers = async () => {
        let response = await axios.get('/api/customers')
        // console.log(response);
        allCustomersData.value =response.data.customers 
    }

    const addCart = (item) => {
        
        const itemCart = {
            id : item.id,
            item_code : item.item_code,
            description : item.description,
            price : item.price,
            quantity : item.quantity??1,
        }

        listCart.value.push(itemCart)
        closeModel()
    }

    const openModel = () => {
        showModel.value = !showModel.value
    }

    const closeModel = () => {
        showModel.value = !hideMode.value
    }

    const getProducts = async  () => {
        let result = await axios.get('/api/products');
        // console.log(result.data.products);
        listProducts.value = result.data.products
    }

    const removeCartItem = (i) => {
        listCart.value.splice(i,1)
    }

    const invoiceList = () => {
        router.push('/');
    }

    const subTotal = () => {
        let total = 0
        listCart.value.map((data) => {
            total = total + (data.quantity * data.price)
        })
        return total
    }

    const grandTotal = () => {
        return subTotal() - form.value.discount
    }

    const saveInvoice = async () => {
        if(listCart.value.length > 0){
            let subTotalAmt = 0
            subTotalAmt = subTotal()    
            
            let totalAmt = 0
            totalAmt = grandTotal()
            
            try {
                form.value.sub_total = subTotalAmt 
                form.value.total = totalAmt 
                form.value.cart_items = listCart.value 
                const response = await axios.post('/api/save-invoice', form.value);
                if(response.data.status == 200){
                    router.push('/');
                }else if(response.data.status == 500){
                    alert(response.data.message);
                }
                
            } catch (error) {
                console.error('Error submitting form:', error);
            }
        }else{
            console.error('Please select product');

        }
    }
</script>