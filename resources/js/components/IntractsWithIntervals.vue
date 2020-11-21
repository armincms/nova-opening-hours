<script> 
export default {  
  data: () => ({
    closed: [],
  	navigation: null, 
  	removeInterval: false, 
    value: {},
  }),  

  methods: {  
    getDayIntervals(day) { 
      var intervals = this.value[day] ? this.value[day] : [] 

      return intervals.filter(interval => this.isValidInterval(day, interval.data))
    },

    isClosed(day) {
      return this.closed.includes(day) 
    },

    isValidInterval(day, data) {
      return ! this.isRestricted(day) || this.getRestrictions(day)[data] != undefined;   
    },

    isRestricted(day) {  
      return this.field[_.lowerCase(day)] !== undefined
    },

    getRestrictions(day) {  
      return this.isRestricted(day) ? this.field[_.lowerCase(day)] : [];
    },

    addInterval(day, data = 'Opening Interval') {
      var current = this.value[day] ? this.value[day] : [];  

      current.push({ 
        hours: '08:00-22:00', 
        data: data 
      })  

      this.$set(this.value, day, current) 
    },  

    removeTheInterval(day, interval) {  
    	var intervals = _.pull(this.getDayIntervals(day), interval)   

    	this.$set(this.value, day, intervals); 
    },
  },

  watch: {
    value: function() { 
      this.closed = this.field.days.filter((day) =>  this.getDayIntervals(day).length < 1) 
    }
  }

}
</script>
