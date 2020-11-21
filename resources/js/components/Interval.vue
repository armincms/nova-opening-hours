<template>
  <div class="flex mt-2">
    <div class="flex w-full ml-2" v-if="largeScreen">
      <a role=button v-if="! editable" @click="openModal" class="inline-block text-danger pt-2 leading-tight">{{ 
        __('Delete')
      }}</a> 

      <confirm-modal 
        v-if="! editable && removeInterval"
        @confirm="confirm"
        @close="closeModal"
      />

      <input 
        :value="data"
        :readonly="editable" 
        class="w-full ml-2 form-control form-input form-input-bordered" 
        :placeholder="__('Label')"
        @input="$emit('data', $event.target.value)"
      />
    </div> 

    <!-- Open From time picker -->
    <vue-clock-picker 
      input-class="form-control form-input form-input-bordered input-time ml-1 px-2" 
      @input="$emit('hours', $event + hours.substr(5))"
      :value="hours.substr(0, 5)" 
      :disabled-from="hours.substr(6)" 
      :done-text="__('Set Start Time')"
    />

    <span class="p-2">:</span>

    <vue-clock-picker 
      input-class="form-control form-input form-input-bordered input-time ml-1 px-2" 
      @input="$emit('hours', hours.substr(0, 6) + $event)"
      :value="hours.substr(6)" 
      disabled-from="00:00" 
      :disabled-to="hours.substr(0, 5)" 
      :done-text="__('Set End Time')"
    /> 
  </div> 
</template>

<script>  
import VueClockPicker from '@pencilpix/vue2-clock-picker';
import '@pencilpix/vue2-clock-picker/dist/vue2-clock-picker.min.css';
import ConfirmModal from './ConfirmModal.vue';

export default {  
  components: {
    ConfirmModal, VueClockPicker
  },

  props: ['editable', 'largeScreen', 'hours', 'data'],

  data:() => ({ 
    removeInterval: false,
  }),
 

  methods: { 
    openModal() { 
      this.removeInterval = true 
    },

    closeModal() { 
      this.removeInterval = false
    }, 

    confirm() {
      this.$emit('delete')
      this.closeModal()
    }
  }
  
}
</script>
<style> 
.input-time {
  width: 60px;
}
.timepicker, .timepicker * {
  direction: ltr !important;
}
</style>