<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import GeneralLayout from '@/Layouts/GeneralLayout.vue';
import { Mail, Phone, MapPin, Send, CheckCircle, AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    currentLocale: {
        type: String,
        required: true
    }
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
});

const isSubmitting = ref(false);
const showSuccess = ref(false);
const showError = ref(false);

const submit = () => {
    isSubmitting.value = true;
    showSuccess.value = false;
    showError.value = false;

    form.post('/api/contactus', {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;
            form.reset();
            isSubmitting.value = false;
            setTimeout(() => {
                showSuccess.value = false;
            }, 5000);
        },
        onError: () => {
            showError.value = true;
            isSubmitting.value = false;
            setTimeout(() => {
                showError.value = false;
            }, 5000);
        }
    });
};

const translations = {
    title: {
        en: 'Contact Us - Get in Touch',
        fr: 'Contactez-nous - Restons en contact',
        es: 'Contáctanos - Mantengámonos en contacto'
    },
    description: {
        en: 'Have questions? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.',
        fr: 'Des questions? Nous aimerions avoir de vos nouvelles. Envoyez-nous un message et nous répondrons dès que possible.',
        es: '¿Tienes preguntas? Nos encantaría saber de ti. Envíanos un mensaje y responderemos lo antes posible.'
    }
};

const pageTitle = translations.title[props.currentLocale];
const pageDescription = translations.description[props.currentLocale];
</script>

<template>
    <GeneralLayout>
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="pageDescription">
            <meta name="robots" content="index, follow">
        </Head>

        <!-- Hero Section -->
        <section class="relative py-20 bg-gradient-to-br from-primary-50 via-white to-primary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-grid-slate-100 opacity-5"></div>
            </div>
            
            <div class="container relative mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ $t('contact_us') || 'Get in Touch' }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">
                        {{ pageDescription }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-20 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Contact Information -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ $t('contact_information') || 'Contact Information' }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $t('contact_info_description') || 'We\'re here to help and answer any question you might have. We look forward to hearing from you.' }}
                            </p>
                        </div>

                        <!-- Contact Cards -->
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4 p-6 bg-gray-50 dark:bg-gray-800 rounded-xl hover:shadow-lg transition-shadow">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                    <Mail class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                        {{ $t('email') || 'Email' }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        info@lara4.com
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-6 bg-gray-50 dark:bg-gray-800 rounded-xl hover:shadow-lg transition-shadow">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                    <Phone class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                        {{ $t('phone') || 'Phone' }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        +1 (555) 123-4567
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-6 bg-gray-50 dark:bg-gray-800 rounded-xl hover:shadow-lg transition-shadow">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                    <MapPin class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                        {{ $t('address') || 'Address' }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        123 University Street<br>
                                        City, State 12345
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            {{ $t('send_message') || 'Send us a Message' }}
                        </h2>

                        <!-- Success Message -->
                        <div v-if="showSuccess" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center space-x-3">
                            <CheckCircle class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" />
                            <p class="text-green-800 dark:text-green-200">
                                {{ $t('message_sent_success') || 'Your message has been sent successfully! We\'ll get back to you soon.' }}
                            </p>
                        </div>

                        <!-- Error Message -->
                        <div v-if="showError" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center space-x-3">
                            <AlertCircle class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" />
                            <p class="text-red-800 dark:text-red-200">
                                {{ $t('message_sent_error') || 'Something went wrong. Please try again.' }}
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $t('name') || 'Name' }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                    :class="{ 'border-red-500': form.errors.name }"
                                    :placeholder="$t('your_name') || 'Your Name'"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $t('email') || 'Email' }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                    :class="{ 'border-red-500': form.errors.email }"
                                    :placeholder="$t('your_email') || 'your.email@example.com'"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $t('phone') || 'Phone' }} <span class="text-gray-400">({{ $t('optional') || 'Optional' }})</span>
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                    :placeholder="$t('your_phone') || '+1 (555) 123-4567'"
                                />
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $t('subject') || 'Subject' }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="subject"
                                    v-model="form.subject"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                    :class="{ 'border-red-500': form.errors.subject }"
                                    :placeholder="$t('message_subject') || 'What is this regarding?'"
                                />
                                <p v-if="form.errors.subject" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.subject }}
                                </p>
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $t('message') || 'Message' }} <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    required
                                    rows="6"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors resize-none"
                                    :class="{ 'border-red-500': form.errors.message }"
                                    :placeholder="$t('your_message') || 'Tell us more about your inquiry...'"
                                ></textarea>
                                <p v-if="form.errors.message" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.message }}
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="isSubmitting || form.processing"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <Send class="w-5 h-5" />
                                <span>
                                    {{ isSubmitting || form.processing ? ($t('sending') || 'Sending...') : ($t('send_message') || 'Send Message') }}
                                </span>
                            </button>
                        </form>
                    </div>
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

