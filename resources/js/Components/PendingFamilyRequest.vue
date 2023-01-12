<script setup>
defineProps(['pendingJoin']);
</script>
 
<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-black-800"><strong>{{ pendingJoin.user.name }}</strong> requested to join {{ pendingJoin.family_username }}.</span>
                    <small class="ml-2 text-sm text-gray-600">Requested At: {{ new Date(pendingJoin.created_at).toLocaleString() }}</small>
                </div>
            </div>
        </div>
    </div>
</template>