<!-- frontend/src/components/VolunteerForm.vue -->
<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4">
          {{ volunteer ? 'Edit Volunteer' : 'Add New Volunteer' }}
        </h2>
        
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Name</label>
            <input 
              v-model="formData.name"
              type="text" 
              required
              class="form-input"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input 
              v-model="formData.email"
              type="email" 
              required
              class="form-input"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Skills (comma-separated)</label>
            <input 
              v-model="formData.skills"
              type="text" 
              class="form-input"
              placeholder="Programming, Design, Teaching"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Availability</label>
            <select 
              v-model="formData.availability"
              class="form-input"
            >
              <option value="weekdays">Weekdays</option>
              <option value="weekends">Weekends</option>
              <option value="both">Both</option>
            </select>
          </div>
          
          <div class="mb-6">
            <label class="block text-gray-700 mb-2">Interests</label>
            <textarea 
              v-model="formData.interests"
              rows="3"
              class="form-input"
              placeholder="Interests and hobbies..."
            ></textarea>
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
import { volunteerService } from '../services/api'

export default {
  props: {
    volunteer: Object
  },
  emits: ['save', 'cancel'],
  setup(props, { emit }) {
    const formData = ref({
      name: '',
      email: '',
      skills: '',
      availability: 'weekdays',
      interests: ''
    })
    
    const saving = ref(false)

    watch(() => props.volunteer, (newVal) => {
      if (newVal) {
        formData.value = { ...newVal }
      } else {
        formData.value = {
          name: '',
          email: '',
          skills: '',
          availability: 'weekdays',
          interests: ''
        }
      }
    }, { immediate: true })

    const handleSubmit = async () => {
      saving.value = true
      try {
        if (props.volunteer) {
          await volunteerService.update(props.volunteer.id, formData.value)
        } else {
          await volunteerService.create(formData.value)
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
