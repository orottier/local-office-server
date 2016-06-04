var appState = {
    username: '',
    token: ''
}

var App = Vue.extend({});

var router = new VueRouter();

router.map({
    '/': {
        component: RequestToken
    },
    '/enter': {
        component: EnterToken
    },
    '/dashboard': {
        component: Dashboard
    }
})

router.start(App, '#app')
