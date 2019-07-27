import firebase from 'firebase/app'
import 'firebase/firestore'
import 'firebase/database'

const fb = firebase.initializeApp({
    apiKey: process.env.MIX_FB_API_KEY,
    authDomain: process.env.MIX_FB_AUTH_DOMAIN,
    databaseURL: process.env.MIX_FB_DB_URL,
    storageBucket: process.env.MIX_FB_STORAGE_BUCKET,
    projectId: process.env.MIX_FB_PROJECT_ID
})

// https://firebase.google.com/docs/database/web/start
export const rtdb = fb.database()

// https://firebase.google.com/docs/firestore/quickstart
export const fsdb = fb.firestore()
