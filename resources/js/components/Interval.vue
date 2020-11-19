<template>
  <div class="flex mt-2">
    <div class="flex w-full ml-2" v-if="largeScreen">
      <a role=button v-if="! restricted" @click="openModal" class="inline-block text-danger pt-2 leading-tight">{{ 
        __('Delete')
      }}</a> 

      <confirm-modal 
        v-if="! restricted && removeInterval"
        @confirm="confirm"
        @close="closeModal"
      />

      <input 
        :value="restricted ? placeholder : interval.data"
        :readonly="restricted" 
        class="w-full ml-2 form-control form-input form-input-bordered" 
        :placeholder="placeholder"
        @input="$emit('data', $event)"
      />
    </div> 

    <!-- Open From time picker -->
    <vue-clock-picker 
      input-class="form-control form-input form-input-bordered input-time ml-1 px-2" 
      @input="$emit('hours', $event + interval.hours.substr(5))"
      :value="interval.hours.substr(0, 5)" 
      :disabled-from="interval.hours.substr(6)" 
      :done-text="__('Set Start Time')"
    />

    <span class="p-2">:</span>

    <vue-clock-picker 
      input-class="form-control form-input form-input-bordered input-time ml-1 px-2" 
      @input="$emit('hours', interval.hours.substr(0, 6) + $event)"
      :value="interval.hours.substr(6)" 
      disabled-from="00:00" 
      :disabled-to="interval.hours.substr(0, 5)" 
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

  props: ['restricted', 'largeScreen', 'day', 'interval', 'placeholder', 'item'],

  data:() => ({
    hours: null,
    removeInterval: false,
  }),

  mounted() {
    this.hours = this.interval.hours
  },

  methods: { 
    openModal() { 
      this.removeInterval = true 
    },

    closeModal() { 
      this.removeInterval = false
    }, 

    confirm() {
      this.$emit('delete', this.day, this.interval)
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