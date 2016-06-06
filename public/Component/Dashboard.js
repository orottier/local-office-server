var Dashboard = Vue.extend({
    data: function () {
        return {
            appState: appState,
            userData: {
                mac_addresses: []
            }
        };
    },
    template: `
        <div>
            <p>Dashboard &ndash; {{ appState.username }}</p>
            <p>Your MAC addresses for identification:</p>
            Add new: <input v-model="newMacAddress" v-on:keyup.enter="addMacAddress">
            <ul>
                <li v-for="mac in userData.mac_addresses">
                    <span>{{ mac.mac_address }}</span>
                    <button v-on:click="removeAddress(mac)">X</button>
                </li>
            </ul>
        </div>`,
    methods: {
        addMacAddress: function () {
            var text = this.newMacAddress.trim();
            if (text) {
                this.$http.post('/api/users/me/mac-addresses', {address: text}).then(
                    function(result) {
                        this.newMacAddress = '';
                        this.loadAddresses();
                    });
            }
        },
        removeAddress: function (mac) {
            this.$http.delete('/api/mac-addresses/' + mac.id).then(
                function(result) {
                    this.loadAddresses();
                });
        },
        loadAddresses: function () {
            this.$http.get('/api/users/me/mac-addresses').then(
                function(result) {
                    this.userData.mac_addresses = result.data;
                }
            );
        }
    },
    ready: function () {
        this.loadAddresses();
    }
})
