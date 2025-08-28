<!-- frontend/src/App.vue -->
<template>
  <div id="app">
    <nav class="bg-blue-600 text-white shadow-lg">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
          <div class="text-xl font-bold">Volunteer Management System</div>
          <ul class="flex space-x-6">
            <li>
              <router-link to="/" class="hover:text-blue-200 transition">Home</router-link>
            </li>
            <li>
              <router-link to="/volunteers" class="hover:text-blue-200 transition">Volunteers</router-link>
            </li>
            <li>
              <router-link to="/events" class="hover:text-blue-200 transition">Events</router-link>
            </li>
            <li>
              <router-link to="/matches" class="hover:text-blue-200 transition">Matches</router-link>
            </li>
            <li>
              <router-link to="/admin" class="hover:text-blue-200 transition">Admin</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>
