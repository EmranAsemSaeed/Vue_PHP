<!-- frontend/src/pages/Home.vue -->
<template>
  <div>
    <h1 class="text-3xl font-bold mb-8">Welcome to the Volunteer Management System</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Volunteers</h2>
        <p class="text-3xl font-bold text-blue-600">{{ stats.volunteers || 0 }}</p>
        <p class="text-gray-600">Registered Volunteer</p>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Events</h2>
        <p class="text-3xl font-bold text-green-600">{{ stats.events || 0 }}</p>
        <p class="text-gray-600">Upcoming Event</p>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Matches</h2>
        <p class="text-3xl font-bold text-purple-600">{{ stats.matches || 0 }}</p>
        <p class="text-gray-600">Successful Match</p>
      </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Latest Volunteers</h2>
        <ul class="divide-y divide-gray-200">
          <li v-for="volunteer in recentVolunteers" :key="volunteer.id" class="py-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">{{ volunteer.name }}</p>
                <p class="text-sm text-gray-500">{{ volunteer.email }}</p>
              </div>
              <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                {{ volunteer.skills }}
              </span>
            </div>
          </li>
        </ul>
        <router-link to="/volunteers" class="block text-center mt-4 text-blue-600 hover:text-blue-800">
          View All
        </router-link>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Upcoming Events</h2>
        <ul class="divide-y divide-gray-200">
          <li v-for="event in upcomingEvents" :key="event.id" class="py-3">
            <div>
              <p class="font-medium">{{ event.title }}</p>
              <p class="text-sm text-gray-500">{{ event.date }} - {{ event.location }}</p>
              <p class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded mt-1 inline-block">
                Needs {{ event.volunteer_count }} Volunteers
              </p>
            </div>
          </li>
        </ul>
        <router-link to="/events" class="block text-center mt-4 text-blue-600 hover:text-blue-800">
          View All
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { volunteerService, eventService, matchService } from '../services/api'

export default {
  setup() {
    const stats = ref({})
    const recentVolunteers = ref([])
    const upcomingEvents = ref([])
    
    const loadData = async () => {
      try {
        // Load statistics
        const [volunteersStats, eventsStats, matches] = await Promise.all([
          volunteerService.getStats(),
          eventService.getStats(),
          matchService.getAll()
        ])
        
        stats.value = {
          volunteers: volunteersStats.total_volunteers,
          events: eventsStats.upcoming_events,
          matches: matches.length
        }
        
        // Load latest volunteers
        const volunteers = await volunteerService.getAll()
        recentVolunteers.value = volunteers.slice(0, 5)
        
        // Load upcoming events
        upcomingEvents.value = await eventService.getUpcoming()
      } catch (error) {
        console.error('Failed to load data:', error)
      }
    }
    
    onMounted(loadData)
    
    return {
      stats,
      recentVolunteers,
      upcomingEvents
    }
  }
}
</script>
