<!-- <script>
    export default {
        name: 'products',
        data() {
            return {
                products: [],
                product_id: ''
            }
        },
        mounted() {
            this.getProducts();
            this.getProductID();
        },
        methods: {
            getProducts(){
                axios.get('productlists').then((response) => { this.products = response.data});
            },

            getProductID(id){
                this.product_id = id;
            }
        },
    }
</script> -->

<script setup>
    import { computed, ref, onMounted, defineExpose } from 'vue';
    import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
    import Modal from '@/Components/Modal.vue';
    import Modal2 from '@/Components/Modal2.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import WarningButton from '@/Components/WarningButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';

    let products = ref([]);
    let products_backup = ref([]);
    let sCat = ref([]);
    let categories = ref([]);
    let categories_backup = ref([]);
    let pageNumber = ref(0);
    let size = ref(7);
    let total = ref("");
    let productName = ref("");
    let productDateTime = ref("");
    let productCategory = ref("");
    let productDescription = ref("");
    let updateBtnType = ref("button");

    let errorname = ref(false);
    let errordescription = ref(false);

    const confirmingProductDeletion = ref(false);
    const confirmingProductEdit = ref(false);
    const confirmProductDeletion = () => {
        confirmingProductDeletion.value = true;
    };
    const confirmProductEdit = () => {
        confirmingProductEdit.value = true;
    };
    const closeModal = () => {
        confirmingProductDeletion.value = false;
        confirmingProductEdit.value = false;
        
        productName.value = "";
        productDateTime.value = "";
        productCategory.value = "";

        form.reset();
    };

    const form = useForm({
        product_id: '',
        product_name: '',
        product_category: '',
        product_datetime: '',
        product_description: '',
    });

    const deleteProduct = () => {
        form.delete(route('products.destroy'), {
            // preserveScroll: true,
            onSuccess: () => closeModal(),
            onFinish: () => form.reset(),
        });
        location.reload();
    }; 

    const updateProduct = () => {
        if(form.product_id && form.product_name && form.product_category && form.product_datetime && form.product_description){
            updateBtnType.value = "submit";
            form.patch(route('products.update'), {
                // preserveScroll: true,
                onSuccess: () => closeModal(),
                onFinish: () => form.reset(),
            });
            location.reload();
        }
        else {
            if(!form.product_name && !form.product_description){
                errorname.value = true;
                errordescription.value = true;
                setTimeout(() => {
                    errorname.value = false;
                    errordescription.value = false;
                }, 2000); 
            }
            else if(!form.product_name) {
                errorname.value = true;
                setTimeout(() => {
                    errorname.value = false;
                }, 2000);
            }
            else if(!form.product_description){
                errordescription.value = true;
                setTimeout(() => {
                    errordescription.value = false;
                }, 2000);
            }
        }
        
    };

    // function getProducts(){
    //     axios.get('productlists').then((response) => { products.value = response.data});
    // };

    const getProducts = computed(() => {
        axios.get('productlists').then((response) => { 
            products.value = response.data.products;
            products_backup.value = response.data.products;
            categories.value = JSON.parse(response.data.categories);
            categories_backup.value = JSON.parse(response.data.categories);
        });
    });

    function getProductID(id, pname, dt, pcat, pdesc, action){
        form.product_id = id;
        productName.value = pname;
        productDateTime.value = dt;
        productCategory.value = pcat;
        productDescription.value = pdesc;

        form.product_name = pname;
        form.product_category = pcat;
        form.product_datetime = dt;
        form.product_description = pdesc;

        if(action == 'delete')
            confirmProductDeletion();
        else if (action == 'edit')
            confirmProductEdit();
    }

    function custom_pagination(paginate_records) {
        const start = pageNumber.value * size.value,
        end = start + size.value;

        total.value = paginate_records.length;
        return paginate_records.slice(start, end);
    }

    function nextPage() {
        pageNumber.value = (pageNumber.value + 1);
    }

    function prevPage() {
        pageNumber.value = (pageNumber.value - 1);
    }

    function pageCount() {
        return Math.ceil(total.value / size.value);
    }

    function convert_date(param_date){
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        const date_object = new Date(param_date);
        let _month = date_object.getMonth();
        let _year = date_object.getFullYear();
        let _day = date_object.getDate();

        let full_date = month[_month] + ' ' + _day + ', ' + _year;
        return full_date;
    }

    function filterCategory(event){
        products.value = [];
        for (const key in products_backup.value) {
            if (Object.hasOwnProperty.call(products_backup.value, key)) {
                const element = products_backup.value[key];
                if (element.category == event.target.value) {
                    products.value.push(element);
                };
            }   
        }
    }

    function onPressSearch(event){
        products.value = [];
        for (const key in products_backup.value) {
            if (Object.hasOwnProperty.call(products_backup.value, key)) {
                const element = products_backup.value[key];
                const record = (element.name +' '+ element.category +' '+ element.description)
                if (record.includes(event.target.value.toLowerCase())) {
                    products.value.push(element);
                };
            }   
        }
    }

    function editCategory(editCategory){
        form.product_category = event.target.value;
    }

    defineExpose({
        getProducts,
        products,
        productName,
        confirmingProductDeletion
    });
</script>

<template>
    <input type="text" hidden v-model="getProducts">
    <div class="flex items-center flex-row justify-between">
        <div class="flex flex-row w-3/4">
            <Link :href="route('products.create')" class="flex-row justify-end space-x-2 inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    Create Product
                </span>
            </Link>
        </div>
        <div class="flex flex-row items-center space-x-2 w-full">
            <select id="pname" name="pname" ref="sCat" v-on:change="filterCategory($event)" autocomplete="off" placeholder="Search" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="" selected hidden> Select Category </option>
                <option value="Food">Food</option>
                <option value="Drinks">Drinks</option>
                <option value="Medicine">Medicine</option>
                <option v-for="category in categories" :value="category.category">{{ category.category }}</option>
            </select>
            <input type="text" id="pname" name="pname" v-on:keyup="onPressSearch($event)" autocomplete="off" placeholder="Search" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-3/4 p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        </div>
        
    </div>
    <div class="relative overflow-x-auto mt-2">
        <table class="w-full text-sm text-left text-gray-100 dark:text-gray-400 bg-gray-800 rounded-md">
            <thead class="">
                <tr class="">
                    <th class="text-center py-2">Name </th>
                    <th class="text-center">Category </th>
                    <th class="text-center">Description </th>
                    <th class="text-center">Date and Time </th>
                    <th class="text-center">Action </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-gray-300 rounded-b-md text-gray-900" v-for="(product, index) in custom_pagination(products)" :key="index">
                    <td class="py-2 text-center ">{{ product.name }}</td> 
                    <td class="py-2 text-center ">{{ product.category }}</td> 
                    <td class="py-2 text-center ">{{ product.description }}</td> 
                    <td class="py-2 text-center ">{{ convert_date(product.datetime) }}</td> 
                    <td class="py-2 text-center ">
                        <div class="pr-2 flex items-center justify-center flex-col space-y-1 lg:space-y-0 space-x-0 lg:space-x-1 lg:flex-row">
                            <button @click="getProductID(product.id, product.name, product.datetime, product.category, product.description, 'edit')" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                            Edit
                            </button>
                            <button @click="getProductID(product.id, product.name, product.datetime, product.category, product.description, 'delete')" class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                            Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex flex-row items-center justify-between space-x-2 mt-4">
            <button type="button" @click="prevPage" :disabled="pageNumber==0 ? true : false"  class="flex flex-row items-center text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-md text-sm px-5 py-1.5 text-center mr-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button type="button" @click="nextPage" :disabled="(pageNumber >= pageCount()-1) ? true : false"  class="flex flex-row items-center text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-md text-sm px-5 py-1.5 text-center mr-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>
    </div>
    
    <Modal :show="confirmingProductDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this product?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Product Name:
                <span v-text="productName" class="font-bold text-gray-900"></span>
                <br>
                Category:
                <span v-text="productCategory" class="font-bold text-gray-900"></span>
                <br>
                <span v-text="convert_date(productDateTime)" class="font-bold text-gray-900"></span>
                
            </p>

            

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteProduct"
                >
                    Delete Product
                </DangerButton>
            </div>
        </div>
    </Modal>

    <Modal2 :show="confirmingProductEdit" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Edit Product
            </h2>

            <div class="flex flex-col space-y-3">
                <div class="mt-5">
                    <label for="pname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                    <input type="text" id="pname" name="pname" v-model="form.product_name" :class="{' ring-red-400 ring-1' : errorname }" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div class="mt-5">
                    <label for="pcategory" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Category</label>
                    <select id="pcategory" name="pcategory" v-on:change="editCategory($event)" :value="form.product_category" :class="{'ring-1 ring-red-400' : productCategory == '' }" class="bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="Food">Food</option>
                        <option value="Drinks">Drinks</option>
                        <option value="Medicine">Medicine</option>
                        <option v-for="category in categories" :value="category.category">{{ category.category }}</option>
                    </select>
                </div>
                <div clas="mt-5">
                    <label for="pdescription" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                    <textarea name="pdescription" id="pdescription" v-model="form.product_description" cols="30" rows="2" :class="{'ring-1 ring-red-400' : errordescription }" class="bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                </div>
                <div class="mt-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="pdatetime">Date and Time</label>
                    <input type="datetime-local" id="pdatetime" name="pdatetime" v-model="form.product_datetime" :class="{'ring-1 ring-red-400' : productDateTime == '' }" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                </div>
            </div>

            

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <WarningButton
                    :type="updateBtnType"
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="updateProduct"
                >
                    Update Product
                </WarningButton>
            </div>
        </div>
    </Modal2>
</template>