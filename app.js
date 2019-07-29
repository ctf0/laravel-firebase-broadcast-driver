// vue
window.Vue = require('vue')

// axios
window.axios = require('axios')
axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
}
axios.interceptors.response.use(
    (response) => response,
    (error) => Promise.reject(error.response)
)

// components
Vue.component('Announcements', require('./Components/Announcements.vue').default)

new Vue({
    el: '#app'
})
