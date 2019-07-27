window.Vue = require('vue')
window.EventHub = require('vuemit')

Vue.component('Announcements', require('./Components/Announcements.vue').default)

new Vue({
    el: '#app'
})
