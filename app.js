window.Vue = require('vue')
window.EventHub = require('vuemit')

Vue.component('Announcements', require('scripts/Components/Announcements.vue').default)

new Vue({
    el: '#app'
})
