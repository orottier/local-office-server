var StatusBar = Vue.extend({
    data: function () {
        return {
            currentSong: '',
            appState: appState,
        };
    },
    template: `
      <header>
          <h1 class="page-header">Kantoortuin 🌷 🌼 🌸</h1>
             <div class="ticker" v-on:click="getNowPlaying">Welcome to the TNW Kantoortuin! Now playing is {{ currentSong }}</div>
            <section id="debugbar">
                <p v-for="item in appState.log">{{ item.type }} {{ item.info }}</p>
            </section>
      </header>
      `,
    methods: {
        getNowPlaying: function () {
            this.$http.get('http://192.168.1.28:5005/Marketing/state', {}, {
                    timeout: 3000
                }).then(
                function(result) {
                    this.currentSong = result.data.currentTrack.artist + ' - ' + result.data.currentTrack.title;
                    setTimeout(this.getNowPlaying, 5000);
                },
                function(error) {
                    this.currentSong = '[error connecting to Sonos]';
                }
            );
        },
    },
    ready: function () {
        this.getNowPlaying();
    },
});

Vue.component('statusbar', StatusBar)
