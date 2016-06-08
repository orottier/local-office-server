var appState = {
    username: '',
    token: '',
    loggedIn: false,
}

var userIsloggedIn = appState.loggedIn;

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
        if (!appState.loggedIn) {
            transition.abort();
        }
        transition.next();
    } else {
        if (appState.loggedIn) {
            transition.abort();
        }
        transition.next();
    }
})

router.start(App, '#app')
