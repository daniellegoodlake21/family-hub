<script setup>
defineProps(['userFamily']);
</script>
 
<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-black-800">{{ userFamily.family_username }}</span>
                    <br>
                    <span class="text-gray-400">{{ userFamily.status }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(userFamily.created_at).toLocaleString() }}</small>
                </div>
            </div>
        </div>
    </div>
</template>