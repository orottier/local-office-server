var EnterToken = Vue.extend({
    data: function () {
        return {
            'appState': appState,
            'errorMsg': '',
        };
    },
    template: `<p>An access token has been sent to @{{ appState.username }} on Slack, please enter it here:</p>
        <form v-on:submit.prevent="enterToken">
            <p v-if="errorMsg">{{ errorMsg }}</p>
            <input id='tokenInput' v-model="appState.token">
            <button action='submit'>Submit</button>
        </form>
        <a v-link="{ path: '/' }">Try again</a>`,
    methods: {
        enterToken: function() {
            if (this.appState.token) {
                Vue.http.headers.common['Authorization'] = 'Bearer ' + this.appState.token;
                this.errorMsg = 'Authorization has been set';
            } else {
                document.getElementById('tokenInput').focus();
            }
        }
    }
})

