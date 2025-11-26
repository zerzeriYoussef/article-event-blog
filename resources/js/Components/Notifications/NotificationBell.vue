<template>
    <div class="relative">
        <button
            @click="toggleNotifications"
            class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"
            ></span>
            <span
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Notifications Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <div
                v-if="showNotifications"
                class="absolute right-0 mt-2 w-80 md:w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 max-h-96 overflow-y-auto"
            >
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $t('notifications') || 'Notifications' }}
                    </h3>
                    <button
                        @click="markAllAsRead"
                        v-if="unreadCount > 0"
                        class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                    >
                        {{ $t('mark_all_read') || 'Mark all as read' }}
                    </button>
                </div>

                <div v-if="notifications.length === 0" class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">
                        {{ $t('no_notifications') || 'No notifications' }}
                    </p>
                </div>

                <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        @click="markAsRead(notification)"
                        :class="[
                            'p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors',
                            !notification.read_at ? 'bg-primary-50 dark:bg-primary-900/20' : ''
                        ]"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                :class="[
                                    'flex-shrink-0 w-2 h-2 rounded-full mt-2',
                                    notification.data.type === 'event_participation_accepted' ? 'bg-green-500' : 'bg-red-500'
                                ]"
                            ></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ notification.data.message }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ formatDate(notification.created_at) }}
                                </p>
                                <Link
                                    v-if="notification.data.post_slug"
                                    :href="route('posts.show', notification.data.post_slug)"
                                    class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mt-2 inline-block"
                                    @click.stop
                                >
                                    {{ $t('view_event') || 'View Event' }} →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="notifications.length > 0" class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
                    <Link
                        :href="route('my-events')"
                        class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                    >
                        {{ $t('view_all') || 'View all events' }}
                    </Link>
                </div>
            </div>
        </Transition>

        <!-- Backdrop -->
        <Transition
            enter-active-class="transition-opacity ease-linear duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showNotifications"
                @click="showNotifications = false"
                class="fixed inset-0 bg-gray-500 bg-opacity-25 z-40"
            ></div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const showNotifications = ref(false);
const notifications = ref(page.props.notifications || []);

const unreadCount = computed(() => {
    return notifications.value.filter(n => !n.read_at).length;
});

const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
    if (showNotifications.value) {
        fetchNotifications();
    }
};

const fetchNotifications = async () => {
    try {
        const response = await fetch('/notifications');
        const data = await response.json();
        notifications.value = data;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

const markAsRead = async (notification) => {
    if (notification.read_at) return;
    
    try {
        await fetch(`/notifications/${notification.id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
        
        notification.read_at = new Date().toISOString();
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
};

const markAllAsRead = async () => {
    try {
        await fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
        
        notifications.value.forEach(n => {
            n.read_at = new Date().toISOString();
        });
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
};

const formatDate = (date) => {
    const now = new Date();
    const notificationDate = new Date(date);
    const diffInSeconds = Math.floor((now - notificationDate) / 1000);
    
    if (diffInSeconds < 60) {
        return 'Just now';
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days} day${days > 1 ? 's' : ''} ago`;
    }
};

const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        showNotifications.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    // Poll for new notifications every 30 seconds
    const interval = setInterval(fetchNotifications, 30000);
    onUnmounted(() => clearInterval(interval));
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

