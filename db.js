import firebase from 'firebase/app'

const fb = firebase.initializeApp({
    apiKey: process.env.MIX_FB_API_KEY,
    authDomain: process.env.MIX_FB_AUTH_DOMAIN,
    databaseURL: process.env.MIX_FB_DB_URL,
    storageBucket: process.env.MIX_FB_STORAGE_BUCKET,
    projectId: process.env.MIX_FB_PROJECT_ID
})

let connection

if (process.env.MIX_FB_Type == 'firestore') {
    require('./firebase/firestore')
    // https://firebase.google.com/docs/firestore/quickstart
    connection = fb.firestore()
} else {
    require('./firebase/database')
    // https://firebase.google.com/docs/database/web/start
    connection = fb.database()
}

export default connection
