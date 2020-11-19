Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-opening-hours', require('./components/IndexField'))
  Vue.component('detail-nova-opening-hours', require('./components/DetailField'))
  Vue.component('form-nova-opening-hours', require('./components/FormField'))
})
