var appState = {
    username: '',
    token: '',
    logged_in: false,
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
        component: Dashboard,
        auth: true
    },
    '/sonos': {
        component: Sonos,
    }
})

router.beforeEach(function (transition) {
    if (transition.to.auth) {
        if (!appState.logged_in) {
            transition.abort();
        }
        transition.next();
    } else {
        if (appState.logged_in) {
            transition.abort();
        }
        transition.next();
    }
})

router.start(App, '#app')
