<script> 
export default {  
  data: () => ({ 
    largeScreen: true,
  }),

  created() { 
    window.addEventListener("resize", (e) => {
    	this.checkScreen()
    });

    this.checkScreen();
  }, 

  methods: {
  	checkScreen() { 
      this.largeScreen = window.screen.width > 400
  	}
  }
}
</script>
