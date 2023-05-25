<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Table from './Table.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';

</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <div class="">
            <!-- <input type="text" v-model="getChildProduct"> -->
            <div class="max-w-7xl mx-auto space-y-6">
                <div class=" bg-white shadow sm:rounded-lg">
                    <!-- <div class="flex items-center flex-row justify-between">
                        <Link :href="route('products.create')" class="flex-row justify-end space-x-2 inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>
                                Create Product
                            </span>
                        </Link>
                        <input type="text" id="pname" name="pname" autocomplete="off" placeholder="Search" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div> -->
                    <Table />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
