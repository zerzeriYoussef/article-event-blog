<template>
    <article class="group bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
        <!-- Image Container -->
        <div class="relative h-48 sm:h-56 overflow-hidden">
            <img 
                :src="item.image" 
                :alt="item.name"
                class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                loading="lazy"
            >
            <!-- Category Badge -->
            <div class="absolute top-4 left-4 z-10">
                <span class="bg-primary-500 text-white text-xs px-3 py-1.5 rounded-full font-medium shadow-md">
                    {{ item.category.name }}
                </span>
            </div>
        </div>

        <!-- Content Container -->
        <div class="p-6">
            <!-- Metadata -->
            <div class="flex items-center space-x-4 mb-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <Timer class="w-4 h-4 mr-1.5" />
                    <span>{{ item.time_to_read }} {{ $t('min_read') }}</span>
                </div>
                
                <div class="flex items-center">
                    <!-- <img 
                        :src="item.author.avatar" 
                        :alt="item.author.name"
                        class="w-5 h-5 rounded-full mr-1.5"
                    > -->
                    <span>{{ item.author.name }}</span>
                </div>
            </div>

            <!-- Title -->
            <Link 
                :href="route('posts.show', item.slug)"
                class="block mb-3 text-xl font-semibold text-gray-900 dark:text-white hover:text-primary-500 dark:hover:text-primary-400 transition-colors duration-300"
            >
                {{ item.name }}
            </Link>

            <!-- Excerpt -->
            <p class="mb-4 text-gray-600 dark:text-gray-400 line-clamp-2">
                {{ item.excerpt }}
            </p>

            <!-- Tags -->
            <div v-if="item.tags && item.tags.length" class="mb-4 flex flex-wrap gap-2">
                <span 
                    v-for="tag in item.tags" 
                    :key="tag.id"
                    class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full"
                >
                    #{{ tag.name }}
                </span>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <Link 
                    :href="route('posts.show', item.slug)"
                    class="inline-flex items-center text-primary-500 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors duration-300"
                >
                    {{ $t('read_more') }}
                    <ChevronRight class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" />
                </Link>

                <!-- Join Event Button (only for authenticated users) -->
                <div v-if="$page.props.auth?.user && item.id">
                    <button
                        v-if="!item.participation_status || item.participation_status === 'none'"
                        @click="joinEvent"
                        :disabled="isJoining"
                        class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <UserPlus v-if="!isJoining" class="w-4 h-4 mr-2" />
                        <span v-if="!isJoining">{{ $t('join_event') || 'Join Event' }}</span>
                        <span v-else>{{ $t('joining') || 'Joining...' }}</span>
                    </button>
                    
                    <div v-else-if="item.participation_status === 'pending'" class="inline-flex items-center px-4 py-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-sm font-medium rounded-lg">
                        <Clock class="w-4 h-4 mr-2" />
                        {{ $t('pending_approval') || 'Pending Approval' }}
                    </div>
                    
                    <div v-else-if="item.participation_status === 'accepted'" class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-medium rounded-lg">
                        <CheckCircle class="w-4 h-4 mr-2" />
                        {{ $t('joined') || 'Joined' }}
                    </div>
                    
                    <button
                        v-else-if="item.participation_status === 'rejected'"
                        @click="joinEvent"
                        :disabled="isJoining"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-300"
                    >
                        <UserPlus class="w-4 h-4 mr-2" />
                        {{ $t('request_again') || 'Request Again' }}
                    </button>
                </div>
            </div>
        </div>
    </article>
</template>

<script setup>
import { ref } from 'vue';
import { Timer, ChevronRight, UserPlus, Clock, CheckCircle } from 'lucide-vue-next';
import { Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
});

const isJoining = ref(false);

const joinEvent = () => {
    if (isJoining.value) return;
    
    isJoining.value = true;
    
    router.post(route('events.join', props.item.slug), {}, {
        preserveScroll: true,
        onSuccess: () => {
            isJoining.value = false;
        },
        onError: () => {
            isJoining.value = false;
        }
    });
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>