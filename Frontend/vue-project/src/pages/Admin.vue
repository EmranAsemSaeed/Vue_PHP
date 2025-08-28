<!-- frontend/src/pages/Admin.vue -->
<template>
  <div>
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Volunteer Statistics</h2>
        <div v-if="volunteerStats" class="space-y-3">
          <p><span class="font-medium">Total Volunteers:</span> {{ volunteerStats.total_volunteers }}</p>
        </div>
        <div v-else class="text-center">
          <p>Loading data...</p>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Event Statistics</h2>
        <div v-if="eventStats" class="space-y-3">
          <p><span class="font-medium">Total Events:</span> {{ eventStats.total_events }}</p>
          <p><span class="font-medium">Upcoming Events:</span> {{ eventStats.upcoming_events }}</p>
          <p><span class="font-medium">Events Needing Volunteers:</span> {{ eventStats.events_need_volunteers }}</p>
        </div>
        <div v-else class="text-center">
          <p>Loading data...</p>
        </div>
      </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-semibold mb-4">Admin Tools</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button 
          @click="generateMatches" 
          class="btn btn-primary"
          :disabled="generating"
        >
          {{ generating ? 'Processing...' : 'Generate Automatic Matches' }}
        </button>
        
        <button 
          @click="exportData('volunteers')" 
          class="btn-warning"
        >
          Export Volunteer Data
        </button>
        
        <button 
          @click="exportData('events')" 
          class="btn-danger"
        >
          Export Event Data
        </button>
      </div>
    </div>
    
    <div v-if="message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
      {{ message }}
    </div>
    
    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { volunteerService, eventService, matchService } from '../services/api'

export default {
  setup() {
    const volunteerStats = ref(null)
    const eventStats = ref(null)
    const message = ref(null)
    const error = ref(null)
    const generating = ref(false)

    const loadStats = async () => {
      try {
        const [volunteerData, eventData] = await Promise.all([
          volunteerService.getStats(),
          eventService.getStats()
        ])
        
        volunteerStats.value = volunteerData
        eventStats.value = eventData
      } catch (err) {
        error.value = 'Failed to load statistics: ' + err.message
      }
    }

    const generateMatches = async () => {
      try {
        generating.value = true
        const result = await matchService.generate()
        message.value = `Created ${result.matches_created} new matches`
      } catch (err) {
        error.value = 'Failed to generate matches: ' + err.message
      } finally {
        generating.value = false
      }
    }

    const exportData = async (type) => {
      try {
        let data, filename
        if (type === 'volunteers') {
          data = await volunteerService.getAll()
          filename = 'volunteers.json'
        } else {
          data = await eventService.getAll()
          filename = 'events.json'
        }
        
        const jsonString = JSON.stringify(data, null, 2)
        const blob = new Blob([jsonString], { type: 'application/json' })
        const url = URL.createObjectURL(blob)
        
        const link = document.createElement('a')
        link.href = url
        link.download = filename
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        URL.revokeObjectURL(url)
        
        message.value = `Successfully exported ${type} data`
      } catch (err) {
        error.value = `Failed to export ${type} data: ` + err.message
      }
    }

    onMounted(loadStats)

    return {
      volunteerStats,
      eventStats,
      message,
      error,
      generating,
      generateMatches,
      exportData
    }
  }
}
</script>
