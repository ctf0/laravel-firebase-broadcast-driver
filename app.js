window.Vue = require('vue')

Vue.component('Announcements', require('./Components/Announcements.vue').default)

new Vue({
    el: '#app'
})
