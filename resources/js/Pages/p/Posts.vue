<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import BlogCard from '@/Components/Blog/BlogCard.vue';
import Pagination from '@/Components/Pagination.vue';
import GeneralLayout from '@/Layouts/GeneralLayout.vue';

const props = defineProps({
    posts: {
        type: Object,
        required: true
    },
    isLoading: {
        type: Boolean,
        default: false
    },
    currentLocale: {
        type: String,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

// SEO Translations
const translations = {
    title: {
        en: 'Blog Posts - Lara4',
        fr: 'Articles de Blog - Lara4',
        es: 'Artículos del Blog - Lara4'
    },
    description: {
        en: 'Discover our latest articles about Laravel, Vue.js, and web development best practices',
        fr: 'Découvrez nos derniers articles sur Laravel, Vue.js et les meilleures pratiques de développement web',
        es: 'Descubre nuestros últimos artículos sobre Laravel, Vue.js y las mejores prácticas de desarrollo web'
    }
};

// Computed properties for SEO
const pageTitle = computed(() => translations.title[props.currentLocale]);
const pageDescription = computed(() => translations.description[props.currentLocale]);
const canonicalUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return `${window.location.origin}/${props.currentLocale}/posts`;
    }
    return `https://lara4.com/${props.currentLocale}/posts`;
});

</script>

<template>
    <GeneralLayout>
        <Head>
            <!-- Basic Meta Tags -->
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="pageDescription">
            <meta name="robots" content="index, follow">
            
            <!-- Open Graph / Facebook -->
            <meta property="og:type" content="website">
            <meta property="og:title" :content="pageTitle">
            <meta property="og:description" :content="pageDescription">
            <meta property="og:url" :content="canonicalUrl">
            <meta property="og:site_name" content="Lara4">
            <meta property="og:locale" :content="currentLocale">
            
            <!-- Twitter -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" :content="pageTitle">
            <meta name="twitter:description" :content="pageDescription">
            
            <!-- Canonical and Alternate Languages -->
            <link rel="canonical" :href="canonicalUrl">
            <link rel="alternate" hreflang="x-default" href="https://lara4.com/en/posts">
            <link rel="alternate" hreflang="en" href="https://lara4.com/en/posts">
            <link rel="alternate" hreflang="fr" href="https://lara4.com/fr/posts">
            <link rel="alternate" hreflang="es" href="https://lara4.com/es/posts">
            
            <!-- JSON-LD Schema -->
            <!-- <script type="application/ld+json" v-text="JSON.stringify(blogSchema)"></script> -->
        </Head>

        <!-- Hero Section -->
        <section class="relative py-20 bg-gradient-to-br from-primary-50 via-white to-primary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-grid-slate-100 opacity-5"></div>
            </div>
            <div class="container relative mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ $t('events') || 'Events & Posts' }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">
                        {{ $t('events_description') || 'Discover our latest events, workshops, and academic activities' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="relative md:py-24 py-16 bg-white dark:bg-gray-900">
            <div class="container relative mx-auto px-6">
                <!-- Breadcrumbs -->
                <nav aria-label="Breadcrumb" class="mb-8">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <Link :href="route('welcome')" class="text-gray-500 hover:text-primary-500 transition-colors">
                                {{ $t('home') || 'Home' }}
                            </Link>
                        </li>
                        <li class="text-gray-300 dark:text-gray-600">/</li>
                        <li class="text-gray-900 dark:text-white font-medium">
                            {{ $t('events') || 'Events' }}
                        </li>
                    </ol>
                </nav>

                <!-- Main Content -->
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">
                    <template v-if="isLoading">
                        <div v-for="n in 3" :key="n" class="group relative overflow-hidden">
                            <Skeleton width="100%" height="200px" />
                            <div class="mt-6">
                                <Skeleton width="70%" height="20px" />
                                <Skeleton width="50%" height="20px" class="mt-2" />
                                <Skeleton width="90%" height="20px" class="mt-2" />
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <BlogCard 
                            v-for="post in posts.data" 
                            :key="post.id" 
                            :item="post" 
                        />
                    </template>
                </div>

                <!-- Empty State -->
                <div v-if="!isLoading && (!posts.data || posts.data.length === 0)" class="col-span-full text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $t('no_events') || 'No Events Yet' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $t('no_events_description') || 'Check back soon for upcoming events and activities.' }}
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="posts.meta && posts.meta.last_page > 1" class="col-span-full mt-12">
                    <Pagination 
                        class="flex justify-center" 
                        :meta="posts.meta" 
                    />
                </div>
            </div>
        </section>
    </GeneralLayout>
</template>

<style scoped>
.bg-grid-slate-100 {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(51 65 85 / 0.1)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
</style>