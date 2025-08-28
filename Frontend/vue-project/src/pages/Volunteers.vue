<!-- frontend/src/pages/Volunteers.vue -->
<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Volunteer Management</h1>
      <button 
        @click="showForm = true; editingVolunteer = null" 
        class="btn btn-primary"
      >
        Add New Volunteer
      </button>
    </div>

    <VolunteerForm 
      v-if="showForm" 
      :volunteer="editingVolunteer"
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
            <th class="py-2 px-4 border-b">Name</th>
            <th class="py-2 px-4 border-b">Email</th>
            <th class="py-2 px-4 border-b">Skills</th>
            <th class="py-2 px-4 border-b">Availability</th>
            <th class="py-2 px-4 border-b">Interests</th>
            <th class="py-2 px-4 border-b">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="volunteer in volunteers" :key="volunteer.id" class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b">{{ volunteer.name }}</td>
            <td class="py-2 px-4 border-b">{{ volunteer.email }}</td>
            <td class="py-2 px-4 border-b">{{ volunteer.skills }}</td>
            <td class="py-2 px-4 border-b">{{ getAvailabilityText(volunteer.availability) }}</td>
            <td class="py-2 px-4 border-b">{{ volunteer.interests }}</td>
            <td class="py-2 px-4 border-b">
              <button 
                @click="editVolunteer(volunteer)"
                class="btn-warning mr-2"
              >
                Edit
              </button>
              <button 
                @click="deleteVolunteer(volunteer.id)"
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
import { volunteerService } from '../services/api'
import VolunteerForm from '../components/VolunteerForm.vue'

export default {
  components: { VolunteerForm },
  setup() {
    const volunteers = ref([])
    const loading = ref(true)
    const error = ref(null)
    const showForm = ref(false)
    const editingVolunteer = ref(null)

    const loadVolunteers = async () => {
      try {
        loading.value = true
        volunteers.value = await volunteerService.getAll()
        error.value = null
      } catch (err) {
        error.value = err.message
      } finally {
        loading.value = false
      }
    }

    const getAvailabilityText = (availability) => {
      const availabilityMap = {
        'weekdays': 'Weekdays',
        'weekends': 'Weekends',
        'both': 'Both'
      }
      return availabilityMap[availability] || availability
    }

    const editVolunteer = (volunteer) => {
      editingVolunteer.value = { ...volunteer }
      showForm.value = true
    }

    const deleteVolunteer = async (id) => {
      if (confirm('Are you sure you want to delete this volunteer?')) {
        try {
          await volunteerService.delete(id)
          await loadVolunteers()
        } catch (err) {
          error.value = err.message
        }
      }
    }

    const handleSave = async () => {
      showForm.value = false
      await loadVolunteers()
    }

    onMounted(loadVolunteers)

    return {
      volunteers,
      loading,
      error,
      showForm,
      editingVolunteer,
      editVolunteer,
      deleteVolunteer,
      handleSave,
      getAvailabilityText
    }
  }
}
</script>
