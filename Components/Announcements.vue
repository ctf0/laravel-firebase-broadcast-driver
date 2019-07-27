<template>
    <div></div>
</template>

<script>
import * as firebase from './../db'

export default {
    data() {
        return {
            notifications: [],
            unsubscribe: null
        }
    },
    beforeMount() {
        // this.rtdb()
        // this.fsdb()
    },
    beforeDestroy() {
        unsubscribe()
    },
    methods: {
        rtdb() {
            firebase.rtdb.ref(process.env.MIX_FB_COLLECTION_NAME)
                .orderByChild('timestamp')
                .startAt(Date.now())
                .on('child_added', (doc) => {
                    this.addToNotifList(doc.val())
                })
        },
        fsdb() {
            this.unsubscribe = firebase.fsdb.collection(process.env.MIX_FB_COLLECTION_NAME)
                .where('timestamp', '>=', Date.now())
                .onSnapshot((docs) => {
                    docs.forEach((doc) => {
                        this.addToNotifList(doc.data())
                    })
                })
        },
        addToNotifList(item) {
            let notifs = this.notifications

            notifs.length ? notifs.unshift(item) : notifs.push(item)
        }
    }
}
</script>
