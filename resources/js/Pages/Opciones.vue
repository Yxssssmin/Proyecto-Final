<script setup>
import { defineProps } from 'vue'

const props = defineProps(['title', 'links'])
</script>

<template>
  <div>
    <h1>{{ title }}</h1>
    <div>
      <a v-for="(link, index) in links" :key="index" :href="link.href" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ link.title }}</h5>
        <p class="font-normal text-gray-700 dark:text-gray-400">{{ link.description }}</p>
      </a>
    </div>
  </div>
</template>
