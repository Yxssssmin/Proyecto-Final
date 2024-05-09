<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from './ApplicationLogo.vue';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});

const { route } = useRoute();
</script>

<template>
    <div class="flex lg:justify-center lg:col-start-2">
        <ApplicationLogo/>
    </div>
    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
            Home
        </Link>

        <template v-else>
            <Link :href="route('login')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Log in
            </Link>

            <Link v-if="canRegister" :href="route('register')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Register
            </Link>
        </template>
    </nav>

</template>

