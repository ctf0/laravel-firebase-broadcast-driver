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
    firebase: {
        notifications: db.ref(process.env.MIX_FB_COLLECTION_NAME)
    }
}
</script>
