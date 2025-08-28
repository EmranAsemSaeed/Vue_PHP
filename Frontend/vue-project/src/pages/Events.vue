<!-- frontend/src/pages/Events.vue -->
<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Event Management</h1>
      <button 
        @click="showForm = true; editingEvent = null" 
        class="btn btn-primary"
      >
        Add New Event
      </button>
    </div>

    <EventForm 
      v-if="showForm" 
      :event="editingEvent"
      @save="handleSave"
      @cancel="showForm = false"
    />

    <div v-if="loading" class="text-center py-8">
      <p>Loading data...</p>
    </div>

    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="py-2 px-4 border-b">Title</th>
            <th class="py-2 px-4 border-b">Date</th>
            <th class="py-2 px-4 border-b">Time</th>
            <th class="py-2 px-4 border-b">Location</th>
            <th class="py-2 px-4 border-b">Required Skills</th>
            <th class="py-2 px-4 border-b">Number of Volunteers</th>
            <th class="py-2 px-4 border-b">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="event in events" :key="event.id" class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b">{{ event.title }}</td>
            <td class="py-2 px-4 border-b">{{ event.date }}</td>
            <td class="py-2 px-4 border-b">{{ event.time }}</td>
            <td class="py-2 px-4 border-b">{{ event.location }}</td>
            <td class="py-2 px-4 border-b">{{ event.required_skills }}</td>
            <td class="py-2 px-4 border-b">{{ event.volunteer_count }}</td>
            <td class="py-2 px-4 border-b">
              <button 
                @click="editEvent(event)"
                class="btn-warning mr-2"
              >
                Edit
              </button>
              <button 
                @click="deleteEvent(event.id)"
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
import { eventService } from '../services/api'
import EventForm from '../components/EventForm.vue'

export default {
  components: { EventForm },
  setup() {
    const events = ref([])
    const loading = ref(true)
    const error = ref(null)
    const showForm = ref(false)
    const editingEvent = ref(null)

    const loadEvents = async () => {
      try {
        loading.value = true
        events.value = await eventService.getAll()
        error.value = null
      } catch (err) {
        error.value = err.message
      } finally {
        loading.value = false
      }
    }

    const editEvent = (event) => {
      editingEvent.value = { ...event }
      showForm.value = true
    }

    const deleteEvent = async (id) => {
      if (confirm('Are you sure you want to delete this event?')) {
        try {
          await eventService.delete(id)
          await loadEvents()
        } catch (err) {
          error.value = err.message
        }
      }
    }

    const handleSave = async () => {
      showForm.value = false
      await loadEvents()
    }

    onMounted(loadEvents)

    return {
      events,
      loading,
      error,
      showForm,
      editingEvent,
      editEvent,
      deleteEvent,
      handleSave
    }
  }
}
</script>
