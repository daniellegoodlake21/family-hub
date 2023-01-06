<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm, Head} from '@inertiajs/inertia-vue3';
import UserFamily from "@/Components/UserFamily.vue";
const form = useForm({
    family_username: '',
    status: 'Admin'
})
defineProps(['families']);
</script>
<template>
    <Head title="Families"/>

    <AuthenticatedLayout>
        <!--Create a new family (and set the current user as the Admin)-->
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('families.store'), {onSuccess: () => form.reset()})">
                <input
                    v-model="form.family_username"
                    placeholder="Enter a Group Family Username"
                    class="block w-full border-blue-300 focus:border-yellow-300 focus:ring-opacity-50 rounded-md shadow-md">
                <InputError :family_username="form.errors.family_username" class="mt-2"/>
                <input type="hidden" v-model="form.status" name="status" class="form-control">
                <PrimaryButton class="mt-4">Create Family Group</PrimaryButton>
            </form>
        </div>
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            <UserFamily
                v-for="userFamily in families"
                :family_username="userFamily.family_username"
                :status="userFamily.status"
                :userFamily="userFamily"
            />
        </div>
    </AuthenticatedLayout>        
</template>