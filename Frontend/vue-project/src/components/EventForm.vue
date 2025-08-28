<!-- frontend/src/components/EventForm.vue -->
<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4">
          {{ event ? 'Edit Event' : 'Add New Event' }}
        </h2>
        
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Event Title</label>
            <input 
              v-model="formData.title"
              type="text" 
              required
              class="form-input"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="formData.description"
              rows="3"
              class="form-input"
            ></textarea>
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Date</label>
            <input 
              v-model="formData.date"
              type="date" 
              required
              class="form-input"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Time</label>
            <input 
              v-model="formData.time"
              type="time" 
              class="form-input"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Location</label>
            <input 
              v-model="formData.location"
              type="text" 
              class="form-input"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Required Skills (comma-separated)</label>
            <input 
              v-model="formData.required_skills"
              type="text" 
              class="form-input"
              placeholder="Design, Programming, Marketing"
            >
          </div>
          
          <div class="mb-6">
            <label class="block text-gray-700 mb-2">Number of Volunteers Needed</label>
            <input 
              v-model="formData.volunteer_count"
              type="number" 
              min="1"
              class="form-input"
            >
          </div>
          
          <div class="flex justify-end space-x-4">
            <button 
              type="button" 
              @click="$emit('cancel')"
              class="px-4 py-2 text-gray-600 hover:text-gray-800"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="btn btn-primary"
            >
              {{ saving ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue'
import { eventService } from '../services/api'

export default {
  props: {
    event: Object
  },
  emits: ['save', 'cancel'],
  setup(props, { emit }) {
    const formData = ref({
      title: '',
      description: '',
      date: '',
      time: '',
      location: '',
      required_skills: '',
      volunteer_count: 1
    })
    
    const saving = ref(false)

    watch(() => props.event, (newVal) => {
      if (newVal) {
        formData.value = { ...newVal }
      } else {
        const tomorrow = new Date()
        tomorrow.setDate(tomorrow.getDate() + 1)
        const tomorrowStr = tomorrow.toISOString().split('T')[0]
        
        formData.value = {
          title: '',
          description: '',
          date: tomorrowStr,
          time: '09:00',
          location: '',
          required_skills: '',
          volunteer_count: 1
        }
      }
    }, { immediate: true })

    const handleSubmit = async () => {
      saving.value = true
      try {
        if (props.event) {
          await eventService.update(props.event.id, formData.value)
        } else {
          await eventService.create(formData.value)
        }
        emit('save')
      } catch (error) {
        alert(error.message)
      } finally {
        saving.value = false
      }
    }

    return {
      formData,
      saving,
      handleSubmit
    }
  }
}
</script>
