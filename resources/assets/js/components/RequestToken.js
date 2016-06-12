var RequestToken = Vue.extend({
    data: function () {
        return {
            appState: appState,
            errorMsg: '',
            cta: 'Submit',
            working: false
        };
    },
    template: `
        <div>
            <p>Leave your Slack username to get access</p>
            <form v-on:submit.prevent="requestToken">
                <p v-if="errorMsg">{{ errorMsg }}</p>
                <input id='loginInput' v-model="appState.username">
                <button action='submit' :disabled="working">{{ cta }}</button>
            </form>
        </div>`,
    methods: {
        lockForm: function() {
            this.working = true;
            this.cta = 'Loading...';
            this.errorMsg = '';
        },
        freeForm: function(errorMsg) {
            this.working = false;
            this.cta = 'Submit';
            this.errorMsg = errorMsg;
            document.getElementById('loginInput').focus();
        },
        requestToken: function() {
            if (this.appState.username) {
                this.lockForm();
                this.$http.post('/api/request-token',
                    {'username': this.appState.username}
                ).then(
                    function(result) {
                        if (result.data.status === 'success') {
                            this.appState.username = result.data.username;
                            router.go('/enter');
                        } else {
                            this.freeForm('Could not send login token to "' + result.data.username + '"');
                        }
                    }, function (error) {
                        this.freeForm('Something went wrong!');
                    }
                );
            } else {
                this.freeForm();
            }
        }
    },
    ready: function () {
        this.freeForm();
    }
})
