<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
defineProps(['pendingJoin']);
const form = useForm({
    family_username: '',
    user_id: -1,
    status: "Joined"
});

function approveRequest(request)
{
    form.family_username = request.family_username;
    form.user_id = request.user_id;
    form.put(route('families.update', request.id));
}
function denyRequest(request)
{
    form.delete(route('families.destroy', request.id));
}
</script>
<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-black-800"><strong>{{ pendingJoin.user.name }}</strong> requested to join {{ pendingJoin.family_username }}.</span>
                    <br>
                    <small class="text-sm text-gray-600">Requested At: {{ new Date(pendingJoin.created_at).toLocaleString() }}</small>
                </div>
            </div>
        </div>
        <div class="mx-auto p-4 sm:p-1 lg:p-3">
              <PrimaryButton v-on:click="approveRequest(pendingJoin)" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-4 py-2 text-center ml-1 mb-2">Approve</PrimaryButton>
              <PrimaryButton v-on:click="denyRequest(pendingJoin)" class="mt-4 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2 text-center ml-6 mb-2">Deny</PrimaryButton>
        </div> 
    </div>
</template>