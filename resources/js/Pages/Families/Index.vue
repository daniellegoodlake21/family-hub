<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm, Head} from '@inertiajs/inertia-vue3';
import UserFamily from "@/Components/UserFamily.vue";
import Pagination from "@/Components/Pagination.vue";
import PendingFamilyRequest from '@/Components/PendingFamilyRequest.vue';
const form = useForm({
    family_username: '',
    status: 'Admin'
});
const props = defineProps(
    {
        families:
        {
            type: Object,
            default: () => ({family_username: ''}),
        },
        pending_families: 
        {
            type: Object,
            default: () => ({}),
        },
        pending_family_join_requests:
        {
            type: Object,
            default: () => ({}),
        }
    }
);
function deleteFamilyLink(request)
{
    form.delete(route('families.destroy', request.id));
}
</script>
<template>
    <Head title="My Families"/>

    <AuthenticatedLayout>
        <!--Create a new family (and set the current user as the Admin)-->
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 class="text-xl mx-auto">My Families</h1>
            <br>
            <h2 class="text-lg mx-auto">Create a new Family Group</h2>
            <p class="text-gray-400 mt-1">You will automatically become the Admin of this group.</p>
            <form @submit.prevent="form.post(route('families.store'), {onSuccess: () => form.reset()})">
                <input
                    v-model="form.family_username"
                    placeholder="Enter a Group Family Username"
                    class="block w-full border-black-300 focus:border-violet-300 focus:ring-opacity-50 rounded-md shadow-md my-3 p-1">
                <input type="hidden" name="status" id="status" v-model="form.status"/>
                <InputError :message="form.errors.family_username" class="mt-2"/>
                <PrimaryButton class="mt-4">Create Family Group</PrimaryButton>
            </form>
        </div>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <!--List families the user is a member of-->
            <h2 class="text-lg mx-auto">My Joined Families</h2>
            <div v-for="userFamily in families.data" v-if="families.data.length > 0" class="mt-1 bg-white shadow-sm rounded-lg">
                <UserFamily
                    :family_username="userFamily.family_username"
                    :status="userFamily.status"
                    :userFamily="userFamily"
                />
                    <div v-if="userFamily.status == 'Admin'">
                        <PrimaryButton v-on:click="deleteFamilyLink(userFamily)" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2 text-center ml-4 mt-1">Delete</PrimaryButton>
                        <p class="text-gray-400 mt-4 ml-4">This will delete the family for all members.</p>
                    </div>
                  <PrimaryButton v-else v-on:click="deleteFamilyLink(userFamily)" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2 text-center ml-2 mb-2">Leave</PrimaryButton>

               <Pagination :data="families"/>
            </div>
            
            <div v-else class="mt-1 divide-y">
                <p class="text-gray-400">You have not yet joined any Family Groups.</p>
            </div>
        </div>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <!--List families the user has requested to join-->
            <h2 class="text-lg mx-auto">Pending Family Groups</h2>
            <div v-if="pending_families.data.length > 0" class="mt-1 bg-white shadow-sm rounded-lg divide-y">
                <UserFamily
                    v-for="userFamily in pending_families.data"
                    :family_username="userFamily.family_username"
                    :status="userFamily.status"
                    :userFamily="userFamily"
                />
                <PrimaryButton v-for="userFamily in pending_families.data" v-on:click="deleteFamilyLink(userFamily)" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2 text-center ml-2 mb-2">Cancel Request</PrimaryButton>
                <Pagination :data="pending_families"/>
               
            </div>
            <div v-else class="mt-1 divide-y">
                <p class="text-gray-400">You have no Pending Family Groups.</p>
            </div>
        </div>
        
            <div class="max-w-2xl mx-auto p-4 sm:p-5 lg:p-8">
                <h2 class="text-lg mx-auto">Join Requests</h2>
                <p class="text-gray-400">If you are an admin of a Family Group and someone requests to join, their request will appear here.</p>
                <!--List pending join requests where the user is the Admin of the group-->
                <div v-if="pending_family_join_requests.data.length > 0" class="mt-1 bg-white shadow-sm rounded-lg divide-y">
                    <PendingFamilyRequest
                        v-for="pendingJoin in pending_family_join_requests.data"
                        :family_username="pendingJoin.family_username"
                        :user_id="pendingJoin.user_id"
                        :pendingJoin="pendingJoin"
                    />
                    <Pagination :data="pending_family_join_requests"/>
                </div>
                <div v-else>
                    <p class="text-gray-400 mt-4">There are currently no join requests for Family Groups in which you are the Admin.</p>
                </div>
            </div>
        
    </AuthenticatedLayout>        
</template>