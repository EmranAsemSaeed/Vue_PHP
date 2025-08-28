<!-- frontend/src/pages/MatchResults.vue -->
<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Match Results</h1>
      <button 
        @click="generateMatches" 
        class="btn btn-primary"
        :disabled="generating"
      >
        {{ generating ? 'Matching in Progress...' : 'Generate New Matches' }}
      </button>
    </div>

    <div v-if="generating" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
      Generating matches, please wait...
    </div>

    <div v-if="message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ message }}
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div v-if="loading" class="text-center py-8">
      <p>Loading data...</p>
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="py-2 px-4 border-b">Volunteer</th>
            <th class="py-2 px-4 border-b">Event</th>
            <th class="py-2 px-4 border-b">Match Score</th>
            <th class="py-2 px-4 border-b">Status</th>
            <th class="py-2 px-4 border-b">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="match in matches" :key="match.id" class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b">{{ match.volunteer_name }}</td>
            <td class="py-2 px-4 border-b">{{ match.event_title }}</td>
            <td class="py-2 px-4 border-b">
              <span class="px-2 py-1 rounded text-white" :class="getScoreClass(match.match_score)">
                {{ match.match_score }}%
              </span>
            </td>
            <td class="py-2 px-4 border-b">
              <span class="px-2 py-1 rounded text-white" :class="getStatusClass(match.status)">
                {{ getStatusText(match.status) }}
              </span>
            </td>
            <td class="py-2 px-4 border-b">
              <button 
                v-if="match.status === 'pending'"
                @click="updateMatchStatus(match.id, 'confirmed')"
                class="btn btn-primary mr-2"
              >
                Confirm
              </button>
              <button 
                v-if="match.status === 'pending'"
                @click="updateMatchStatus(match.id, 'rejected')"
                class="btn-warning mr-2"
              >
                Reject
              </button>
              <button 
                @click="deleteMatch(match.id)"
                class="btn-danger"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { matchService } from '../services/api'

export default {
  setup() {
    const matches = ref([])
    const loading = ref(true)
    const error = ref(null)
    const message = ref(null)
    const generating = ref(false)

    const loadMatches = async () => {
      try {
        loading.value = true
        matches.value = await matchService.getAll()
        error.value = null
      } catch (err) {
        error.value = err.message
      } finally {
        loading.value = false
      }
    }

    const generateMatches = async () => {
      try {
        generating.value = true
        const result = await matchService.generate()
        message.value = `Created ${result.matches_created} new matches`
        await loadMatches()
      } catch (err) {
        error.value = err.message
      } finally {
        generating.value = false
      }
    }

    const updateMatchStatus = async (id, status) => {
      try {
        await matchService.updateStatus(id, status)
        message.value = 'Match status updated successfully'
        await loadMatches()
      } catch (err) {
        error.value = err.message
      }
    }

    const deleteMatch = async (id) => {
      if (confirm('Are you sure you want to delete this match?')) {
        try {
          await matchService.delete(id)
          message.value = 'Match deleted successfully'
          await loadMatches()
        } catch (err) {
          error.value = err.message
        }
      }
    }

    const getScoreClass = (score) => {
      if (score >= 80) return 'bg-green-600'
      if (score >= 60) return 'bg-yellow-600'
      return 'bg-red-600'
    }

    const getStatusClass = (status) => {
      const statusClasses = {
        'pending': 'bg-yellow-600',
        'confirmed': 'bg-green-600',
        'rejected': 'bg-red-600'
      }
      return statusClasses[status] || 'bg-gray-600'
    }

    const getStatusText = (status) => {
      const statusText = {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'rejected': 'Rejected'
      }
      return statusText[status] || status
    }

    onMounted(loadMatches)

    return {
      matches,
      loading,
      error,
      message,
      generating,
      generateMatches,
      updateMatchStatus,
      deleteMatch,
      getScoreClass,
      getStatusClass,
      getStatusText
    }
  }
}
</script>
