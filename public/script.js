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
    }
})

router.start(App, '#app')
