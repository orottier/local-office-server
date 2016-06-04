var Login = Vue.extend({
    template: `<p>Leave your Slack username to get access</p>\
        <form v-on:submit.prevent="login">
            <input id='loginInput' v-model="username">
            <button action='submit'>Submit</button>
        </form>`,
    methods: {
        login: function() {
            alert(this.username);
        }
    },
    ready: function () {
        document.getElementById('loginInput').focus();
    }
})

var Bar = Vue.extend({
    template: '<p>This is bar!</p>'
})

var App = Vue.extend({});

var router = new VueRouter();

router.map({
    '/': {
        component: Login
    },
    '/bar': {
        component: Bar
    }
})

router.start(App, '#app')
