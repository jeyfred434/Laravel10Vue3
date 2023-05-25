
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import Table from './Table.vue';
    import { Head } from '@inertiajs/vue3';

    
</script>

<template>
    <Head title="Products" />
    <AuthenticatedLayout>
        <div class="flex items-center">
            <PrimaryButton class="flex flex-row items-center justify-end space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    Create Product
                </span>
            </PrimaryButton>
        </div>
        <Table />
    </AuthenticatedLayout>
</template>

