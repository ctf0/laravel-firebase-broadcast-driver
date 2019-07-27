import firebase from 'firebase/app'
import 'firebase/database'

export default firebase
    .initializeApp({
        apiKey: process.env.MIX_FB_API_KEY,
        authDomain: process.env.MIX_FB_AUTH_DOMAIN,
        databaseURL: process.env.MIX_FB_DB_URL,
        storageBucket: process.env.MIX_FB_STORAGE_BUCKET
    })
    .database()
