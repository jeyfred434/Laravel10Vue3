<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    ring: {
        type: String,
        default: '',
    },
});

let form_index = ref(1);
// let pname = ref("");
// let pcategory = ref("");
// let pdescription = ref("");
// let pdatetime = ref("");


let pcat = ref(false);
let pn = ref(false);
let pdesc = ref(false);
let pimg = ref(false);
let pdt = ref(false);
let pbtn_submit = ref("button");

const form = useForm({
    pname: '',
    pcategory: '',
    pdescription: '',
    pdatetime: '',
    file: null,
});

function previous() {
    form_index.value > 1 ? form_index.value-- : '';
}

function next() {
    if(form_index.value == 1){
        if(form.pname && form.pcategory && form.pdescription)
            form_index.value < 3 ? form_index.value++ : '';
        else 
        {
            if(!form.pname && !form.pcategory && !form.pdescription){
                pcat.value = true;
                pn.value = true;
                pdesc.value = true;

                setTimeout(() => {
                    pcat.value = false;
                    pn.value = false;
                    pdesc.value = false;
                }, 2000);
            }
            else if(!form.pname){
                pn.value = true;
                setTimeout(() => {
                    pn.value = false;
                }, 2000);
            }
            else if(!form.pcategory){
                pcat.value = true;
                setTimeout(() => {
                    pcat.value = false;
                }, 2000);
            }
            else if(!form.pdescription){
                pdesc.value = true;
                setTimeout(() => {
                    pdesc.value = false;
                }, 2000);
            }
        }
    }
    else if (form_index.value == 2){
        if(!pimg.value)
            form_index.value < 3 ? form_index.value++ : '';
        else
            '';
    }
    else if(form_index.value == 3){
        if(!pdatetime.value){
            pdt.value = true;
            setTimeout(() => {
                pdt.value = false;
            }, 2000);
        }
    }
    // console.log(pimg.value);
    // console.log(form_index.value);
}

function check_image(event) {
    // let im = this.$refs.pimages.files[0];
    let allowed_files = ['jpeg', 'jpg', 'png', 'gif'];
    pimg.value = true;
    form.file = event.target.files;
    if(event.target.files){
        for (const key in event.target.files) {
            if (Object.hasOwnProperty.call(event.target.files, key)) {
                const element = event.target.files[key];
                let file_type = element.name.split('.')[1].toLowerCase();
                if(allowed_files.includes(file_type))
                    pimg.value = false;
                else {
                    pimg.value = true;
                    break;
                }
            }
        }
    }
}

function save_products(){
    if(!pdatetime.value){
        pdt.value = true;
        setTimeout(() => {
            pdt.value = false;
        }, 2000);
    }
    else {
        pbtn_submit.value = "submit";
    }
    // this.$refs.save_products_btn.type = 'submit';
}

const submit = () => {
    form.post(route('products.store'));
};
</script>

<template>
    <Head title="Create Product" />

    <AuthenticatedLayout>
        <div class="pt-2">
            <div class="max-w-7xl mx-auto space-y-6">
                <form class="" enctype="multipart/form-data"  @submit.prevent="submit">
                    <!-- <form class="" enctype="multipart/form-data" @submit.prevent="post(route('products.store'))"> -->
                    <header>
                        <h2 class="text-2xl font-medium text-gray-900">Create Product</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Enter details to create new product.
                        </p>
                    </header>

                    <div v-show="form_index == 1" class="transition duration-500 ease-in-out">
                        <div class="mt-5">
                            <label for="pname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                            <input type="text" id="pname" name="pname" v-model="form.pname" :class="{' ring-red-400 ring-1' : pn }" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div class="mt-5">
                            <label for="pcategory" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Category</label>
                            <select id="pcategory" name="pcategory" v-model="form.pcategory" :class="{'ring-1 ring-red-400' : pcat }" class="bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value=null hidden selected> Select Category </option>
                                <option value="Food">Food</option>
                                <option value="Drinks">Drinks</option>
                                <option value="Medicine">Medicine</option>
                            </select>
                        </div>
                        <div clas="mt-5">
                            <label for="pdescription" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                            <textarea name="pdescription" id="pdescription" v-model="form.pdescription" cols="30" rows="2" :class="{'ring-1 ring-red-400' : pdesc }" class="bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                        </div>
                    </div>
                    <div v-show="form_index ==  2" class="transition duration-500 ease-in-out">
                        <div class="mt-5">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="pimages">Upload files <span class="text-sm">(jpeg, jpg, png, gif)</span></label>
                            <input id="pimages" name="pimages" v-on:change="check_image" ref="pimages" :class="{'ring-1 ring-red-400' : pimg }" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required type="file" multiple>
                        </div>
                    </div>
                    <div v-show="form_index ==  3" class="transition duration-500 ease-in-out">
                        <div class="mt-5">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="pdatetime">Date and Time</label>
                            <input type="datetime-local" id="pdatetime" name="pdatetime" v-model="form.pdatetime" :class="{'ring-1 ring-red-400' : pdt }" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                        </div>
                    </div>
                    
                    <div class="flex flex-row items-center justify-start space-x-2 mt-4">
                        <button type="button" @click="previous" class="flex flex-row items-center text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-md text-sm px-5 py-1.5 text-center mr-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button type="button" @click="next" class="flex flex-row items-center text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-md text-sm px-5 py-1.5 text-center mr-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                        <button :type="pbtn_submit" ref="save_products_btn" @click="save_products" :class="{ 'hidden' : form_index != 3 }"  class="inline-flex flex-row items-center space-x-2 text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-md text-sm px-3 py-1.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span>Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
