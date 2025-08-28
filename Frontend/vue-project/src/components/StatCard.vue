<template>
  <div :class="` dark:bg-gray-700 rounded-lg p-4 text-center transition-transform hover:scale-105`">
    <div :class="`bg-${color} w-12 h-12 rounded-full flex items-center justify-center text-white mx-auto mb-3`">
      <i :class="icon" class="text-lg"></i>
    </div>
    <p class="text-3xl font-bold" :class="`text-${color}-600 dark:text-${color}-400`">{{ value }}</p>
    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">{{ title }}</p>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    value: [String, Number],
    color: {
      type: String,
      default: 'blue'
    },
    icon: {
      type: String,
      default: 'fas fa-chart-bar'
    }
  }
}
</script>