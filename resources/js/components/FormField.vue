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
        <div class="flex mb-2" v-if="! isClosed(day) && ! isRestricted(day)">
          <div class="flex w-full ml-2" v-if="largeScreen">
            <a role=button @click="addInterval(day)" class="inline-block text-primary pt-2 leading-tight">{{
              __('Add New Item') 
            }}</a> 
          </div>
        </div>

        <interval 
          v-for="(interval, key) in getDayIntervals(day)"
          v-if="! isClosed(day)"
          :key="key" 
          :day="day"
          :interval="interval" 
          :restricted="isRestricted(day)"
          :placeholder="isRestricted(day) ? getRestrictions(day)[interval.data] : __('Label')"
          :large-screen="largeScreen"
          @input="interval.hours = $event"
          @delete="removeTheInterval"
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
    history: {},
    closed: [] 
  }),

  mounted() {       
    this.closed = this.field.days.filter((day) => this.isClosed(day)) 

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