<template>
    <GeneralLayout>
        <Head>
            <title>{{ $t('my_events') || 'My Events' }} - Lara4</title>
        </Head>

        <!-- Hero Section -->
        <section class="relative py-20 bg-gradient-to-br from-primary-50 via-white to-primary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="container relative mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ $t('my_events') || 'My Events' }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">
                        {{ $t('my_events_description') || 'View all events you have requested to join' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="relative md:py-24 py-16 bg-white dark:bg-gray-900">
            <div class="container relative mx-auto px-6">
                <!-- Tabs -->
                <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            @click="activeTab = 'pending'"
                            :class="[
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                                activeTab === 'pending'
                                    ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                            ]"
                        >
                            {{ $t('pending') || 'Pending' }}
                            <span v-if="pending.length > 0" class="ml-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ pending.length }}
                            </span>
                        </button>
                        <button
                            @click="activeTab = 'accepted'"
                            :class="[
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                                activeTab === 'accepted'
                                    ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                            ]"
                        >
                            {{ $t('accepted') || 'Accepted' }}
                            <span v-if="accepted.length > 0" class="ml-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ accepted.length }}
                            </span>
                        </button>
                        <button
                            @click="activeTab = 'rejected'"
                            :class="[
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                                activeTab === 'rejected'
                                    ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                            ]"
                        >
                            {{ $t('rejected') || 'Rejected' }}
                            <span v-if="rejected.length > 0" class="ml-2 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ rejected.length }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Pending Tab -->
                <div v-if="activeTab === 'pending'">
                    <div v-if="pending.length === 0" class="text-center py-20">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <Clock class="w-12 h-12 text-gray-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $t('no_pending_events') || 'No Pending Requests' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $t('no_pending_events_description') || 'You have no pending event participation requests.' }}
                        </p>
                    </div>
                    <div v-else class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">
                        <BlogCard 
                            v-for="participation in pending" 
                            :key="participation.id" 
                            :item="participation.post" 
                        />
                    </div>
                </div>

                <!-- Accepted Tab -->
                <div v-if="activeTab === 'accepted'">
                    <div v-if="accepted.length === 0" class="text-center py-20">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <CheckCircle class="w-12 h-12 text-gray-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $t('no_accepted_events') || 'No Accepted Events' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $t('no_accepted_events_description') || 'You have not been accepted to any events yet.' }}
                        </p>
                    </div>
                    <div v-else class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">
                        <BlogCard 
                            v-for="participation in accepted" 
                            :key="participation.id" 
                            :item="participation.post" 
                        />
                    </div>
                </div>

                <!-- Rejected Tab -->
                <div v-if="activeTab === 'rejected'">
                    <div v-if="rejected.length === 0" class="text-center py-20">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <XCircle class="w-12 h-12 text-gray-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $t('no_rejected_events') || 'No Rejected Events' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $t('no_rejected_events_description') || 'You have no rejected event participation requests.' }}
                        </p>
                    </div>
                    <div v-else class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">
                        <BlogCard 
                            v-for="participation in rejected" 
                            :key="participation.id" 
                            :item="participation.post" 
                        />
                    </div>
                </div>
            </div>
        </section>
    </GeneralLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Clock, CheckCircle, XCircle } from 'lucide-vue-next';
import GeneralLayout from '@/Layouts/GeneralLayout.vue';
import BlogCard from '@/Components/Blog/BlogCard.vue';

const props = defineProps({
    pending: {
        type: Array,
        default: () => []
    },
    accepted: {
        type: Array,
        default: () => []
    },
    rejected: {
        type: Array,
        default: () => []
    }
});

const activeTab = ref('pending');
</script>

