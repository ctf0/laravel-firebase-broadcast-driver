<template>
    <div></div>
</template>

<script>
import db from './../db'

export default {
    data() {
        return {
            notifications: []
        }
    },
    beforeMount() {
        db.ref(process.env.MIX_FB_COLLECTION_NAME)
            .orderByChild('timestamp')
            .startAt(Date.now())
            .on('child_added', (snapshot) => {
                let notifs = this.notifications

                notifs.length ? notifs.unshift(snapshot.val()) : notifs.push(snapshot.val())
            })
    }
}
</script>
