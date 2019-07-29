<template>
    <div />
</template>

<script>
import db from './../db'

const startAt = Date.now()
const orderBy = 'timestamp'  // dont forget to index it under firebase rules
const collectionName = process.env.MIX_FB_COLLECTION_NAME
const authUrl = `${process.env.MIX_APP_URL}/broadcasting/auth`

export default {
    props: {
        userId: {
            type: String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            notifications: [],
            queue: []
        }
    },
    // firebase: {
    //     queue: db.ref(collectionName)
    //         .orderByChild(orderBy)
    //         .startAt(startAt)
    // },
    firestore: {
        queue: db.collection(collectionName)
            .where(orderBy, '>=', startAt)
            .orderBy(orderBy)
    },
    methods: {
        async manageNotifications(data) {
            let channel = this.getChannelData(data.channel)
            let isAuthorized = await this.isAuthorized(channel)

            if (this.isPrivate(channel) && isAuthorized) {
                data.channel = channel

                return this.addToNotifList(data)
            } else {
                // show a public notification
            }
        },
        addToNotifList(item) {
            let notifs = this.notifications

            if (!notifs.includes(item)) {
                notifs.push(item)
            }
        },
        getChannelData(channel) {
            let regex = /^\w+-/g
            let isPrivate = channel.match(regex)

            return {
                'name': channel,
                'type': isPrivate ? isPrivate[0].replace('-', '') : 'public'
            }
        },

        /* --------------------------------- checks -------------------------------- */

        isPrivate({type}) {
            return type == 'private'
        },
        async isAuthorized({name}) {
            try {
                let res = await axios.post(authUrl, {
                    'channel_name': name
                })

                return res.status == 200 ? true : false
            } catch (error) {
                console.clear()
            }
        },
        toOthers({name}) {
            return !name.includes(this.userId)
        }
    },
    watch: {
        queue(list) {
            if (list.length) {
                let index = list.length - 1
                
                this.manageNotifications(list[index])

                this.$nextTick(() => {
                    this.queue.splice(index, 1)
                })
            }
        }
    }
}
</script>
