<template>
  <div class="flex mt-2">
    <div class="flex w-full ml-2">
      <a role=button v-if="editable" @click="openModal" class="inline-block text-danger pt-2 leading-tight">{{ 
        __('Delete')
      }}</a> 

      <a role=button v-else-if="hours" @click="close" class="inline-block text-danger pt-2 leading-tight">{{ 
        __('Close')
      }}</a> 

      <a role=button v-else @click="open" class="inline-block text-success pt-2 leading-tight">{{ 
        __('Open')
      }}</a> 

      <confirm-modal 
        v-if="removing"
        @confirm="confirm"
        @close="closeModal"
      />

      <input 
        :value="data"
        :readonly="! editable" 
        class="w-full ml-2 form-control form-input form-input-bordered" 
        :class="{'border-danger text-danger': ! hours}"
        :placeholder="__('Label')"
        @input="$emit('data', $event.target.value)"
        v-if="largeScreen"
      />
    </div> 

    <template v-if="hours">
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
    </template>
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
    removing: false,
    history: '08:00-23:00',
  }),
 

  methods: { 
    openModal() { 
      this.removing = true 
    },

    closeModal() { 
      this.removing = false
    }, 

    confirm() {
      this.$emit('delete')
      this.closeModal()
    },

    close() {
      this.history = this.hours
      this.$emit('hours', null)
    },

    open() {   
      this.$emit('hours', this.history)
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