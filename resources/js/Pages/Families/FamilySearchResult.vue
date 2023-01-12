<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm, Head} from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { watch } from 'vue';

const form = useForm({
    family_username: '',
});
const joinForm = useForm({
    family_username: '',
    status: 'Pending Admin Approval'
});
const props = defineProps(
    {
        families:
        {
            type: Object,
            default: () => ({}),
        },
        pending_families: 
        {
            type: Object,
            default: () => ({}),
        }
    }
)
var search = ref('');
watch(search, (value) => {
    Inertia.get('/family_search',
    {search: value},
    {
        preserveState: true,
    });
});
function joinFamily(familyUsername) 
{
    //  Assign family username to joinForm
    joinForm.family_username = familyUsername;
    joinForm.status = "Pending Admin Approval";
    joinForm.post(route('family_search.store'), {onSuccess: () => joinForm.reset()});
}
</script>
<template>
    <Head title="Family Search Results"/>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 class="text-xl">Family Search Results</h1>
            <h2 class="text-gray-400 my-3">You'll just need to wait for approval from the Admin of the Family Group.</h2>
            <input
                v-model="search"
                type="text"
                placeholder="Search existing Family Group Usernames..."
                class="block w-full border-black-300 focus:border-violet-300 focus:ring-opacity-50 rounded-md shadow-md">
            <InputError :message="form.errors.family_username" class="mt-2"/>
        </div>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h2 class="text-lg mx-auto">Available Family Groups</h2>
            <div v-if='families.data.length > 0' class="mt-1 divide-y">
                <table class="w-full text-sm text-center">
                    <thead class="text-md uppercase bg-blue-50 dark:bg-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3">Family Group Username</th>
                            <th scope="col" class="px-6 py-3">Created At</th>
                            <th scope="col" class="px-6 py-3">Join</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="family in families.data" :key="family.family_username" class="bg-gray border-b">
                            <td scope="row" class="px-6 py-4 text-md">
                                {{ family.family_username }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-md">
                                {{ new Date(family.created_at).toLocaleString() }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-md">
                                <input type="hidden" name="status" id="status" v-model="joinForm.status"/>
                                <PrimaryButton v-on:click="joinFamily(family.family_username)" class="mt-4">Request to Join</PrimaryButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="mt-1 divide-y">
                <p class="text-gray-400">There are no Family Groups matching your search.</p>
            </div>
        </div>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        </div>
    </AuthenticatedLayout>
</template>