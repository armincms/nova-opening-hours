<template>
  <panel-item :field="field">
		<div slot="value"> 
      <navigator  
        @navigate="navigation = $event"
        :active="navigation"
        :days="field.days" 
        :closed="closed"  
        :editing=false
      /> 

      <div 
        class="flex justify-between border-t py-4"  
        v-if="navigation == day"
        v-for="day in field.days"
        :key="day"
      >     
        <span v-if="isClosed(day)" class="text-danger text-center w-full">{{  
          __('This day is closed; Please go to edit for opening this.')
        }}</span>
        <div
          v-else
          v-for="(interval, key) in getDayIntervals(day)"
          class="flex mt-2"
          :key="key"
        >  
          <h4 class="text-info">{{ isRestricted(day) ? getRestrictions(day)[interval.data] : interval.data }}:</h4>
          <span class="ml-2" :class="{'text-danger': ! interval.hours}">{{ 
            interval.hours ? interval.hours : __('Closed') 
          }}</span> 
       	</div>
      </div>
   	</div>
</panel-item>
</template>

<script>
import Navigator from './Navigator.vue'
import IntractsWithIntervals from './IntractsWithIntervals'

export default { 
  mixins: [IntractsWithIntervals],

	components: {Navigator},

  props: ['resource', 'resourceName', 'resourceId', 'field'],

  data: () => ({
  	navigation: null
  }),

  mounted() {
    this.navigation = this.field.today 
    this.value = this.field.value || {} 
  }, 

}
</script>
