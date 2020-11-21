<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <!-- The navigator  -->
      <navigator 
        @toggle="toggle" 
        @navigate="navigation = $event"
        :active="navigation"
        :days="field.days" 
        :closed="closed" 
        :editing=true
      /> 

      <div 
        class="flex flex-col justify-around mt-6"  
        v-if="navigation == day"
        v-for="day in field.days"
        :key="day"
      >       
        <div class="flex border-t border-30 p-4">
          <span class="btn px-4 py-2">{{ __(titleCase(day)) }}:</span>

          <span 
            role=button 
            class="btn-outline btn-sm leading-loose" 
            :class="[
              isClosed(day) 
                ? 'text-success hover:text-success-dark border-success hover:border-success-dark'
                : 'text-danger hover:text-danger-dark border-danger hover:border-danger-dark'
            ]" 
            @click="toggle(day)"
          >{{ 
            isClosed(day) ? (largeScreen ? __('Opening') : '+') : (largeScreen ? __('Close') : '-') 
          }}</span>  

            <span
              role=button 
              @click="addInterval(day)" 
              v-if="! isRestricted(day) && ! isClosed(day)"
              class="ml-2 btn-outline btn-sm leading-loose text-success hover:text-success-dark border-success hover:border-success-dark"
            >{{
              __('Add') 
            }}</span> 
        </div>

        <interval 
          v-for="(interval, key) in getDayIntervals(day)"
          :key="key"  
          :hours="interval.hours"
          :data="isRestricted(day) ? getRestrictions(day)[interval.data] : interval.data" 
          :editable="isRestricted(day)" 
          :large-screen="largeScreen"
          v-on:data="interval.data = $event"
          v-on:hours="interval.hours = $event"
          v-on:delete="removeTheInterval"
        />
      </div>  
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import IntractsWithIntervals from './IntractsWithIntervals'
import HandlesScreen from './HandlesScreen' 
import Navigator from './Navigator.vue'
import Interval from './Interval'

export default {
  mixins: [FormField, HandlesValidationErrors, HandlesScreen, IntractsWithIntervals],

  components: {Interval, Navigator}, 

  props: ['resourceName', 'resourceId', 'field'],

  data: () => ({  
    navigation: null,
    history: {} 
  }),

  mounted() {       
    this.field.days.forEach(day => {   
      Object.keys(this.getRestrictions(day))
                .filter(restriction => ! this.isClosed(day) && ! this.existsRestriction(day, restriction))
                .forEach(interval => this.addInterval(day))  
    }) 
  }, 

  methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue() {
          this.navigation = this.field.today 
          this.value = this.field.value || {}  
      }, 

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill(formData) { 
          formData.append(this.field.attribute, JSON.stringify(this.value)) 
      },  

      toggle(day) {  
        this.isClosed(day) ? this.open(day) : this.close(day) 
      },

      open(day) {
        this.closed = this.closed.filter((value) => value !== day)

        if(this.getHistory(day))
          this.$set(this.value, day, this.getHistory(day))
        else if(this.isRestricted(day))
          this.applyRestrictionsOn(day)
        else
          this.addInterval(day) 
      },

      close(day) {  
        this.$set(this.closed, this.closed.length, day)
        this.clearIntervals(day)
      },

      clearIntervals(day) { 
        this.$set(this.history, day, this.getDayIntervals(day))
        this.$set(this.value, day, [])
      },

      getHistory(day) { 
        return this.history[day]
      }, 

      applyRestrictionsOn(day) {
        Object.keys(this.getRestrictions(day)).forEach((data) => this.addInterval(day, data))           
      },  

      existsRestriction(day, restriction) { 
        return this.getDayIntervals(day).find(interval => {  
          return interval.data === restriction
        }) != undefined
      }, 

      titleCase(string) {
        return _.upperFirst(string)
      }
  },

  computed: { 
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