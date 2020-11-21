<template>  
  <div class="flex justify-between">
    <h4 
      class="flex flex-col text-center" 
      :class="{'text-70': ! isActive(day)}"
      v-for="day in days" 
      :key="day" 
    >
      <span role=button @click="navigate(day)">{{ 
        label(day)
      }}</span>   
    </h4>
  </div>
</template>

<script> 
import HandlesScreen from './HandlesScreen'

export default {  
  mixins: [HandlesScreen],

  props: ['days', 'active', 'closed', 'editing'],  

  methods: {
    isActive: function(day) { 
      return day === this.active
    },

    isClosed: function(day) {  
      return this.closed.includes(day)
    },

    navigate: function(day) {  
      this.$emit('navigate', day)
    },

    toggle: function(day) {  
      this.$emit('toggle', day) 
      this.navigate(day) 
    },

    label(day) {
      var day = _.upperFirst(day);

      return this.largeScreen ? this.__(day) : this.__(day.substr(0, 3));
    }

  }
}
</script>
