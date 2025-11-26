<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ $t('event_participants') || 'Participants' }}
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    ({{ participants.length }})
                </span>
            </h3>
        </div>

        <div v-if="participants.length === 0" class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ $t('no_participants_yet') || 'Aucun participant pour le moment' }}
            </p>
        </div>

        <div v-else class="space-y-3">
            <div
                v-for="participant in participants"
                :key="participant.id"
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
                <img
                    :src="participant.user.avatar"
                    :alt="participant.user.name"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-primary-200 dark:ring-primary-800"
                />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {{ participant.user.name }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $t('joined') || 'Rejoint' }} {{ participant.joined_at }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    participants: {
        type: Array,
        default: () => []
    }
});
</script>

