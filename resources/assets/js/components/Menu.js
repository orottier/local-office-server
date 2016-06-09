var loginMenu = Vue.extend({
    data: function () {
        return {
            'currentSong': ''
        };
    },
    template: `

      <header>
          <h1 class="page-header">Kantoortuin 🌷 🌼 🌸</h1>

             <div class="ticker" v-on:click="getNowPlaying">Welcome to the TNW Kantoortuin! Now playing is {{ currentSong }}</div>


      </header>
      `,
    methods: {
        getNowPlaying: function () {
            this.$http.get('http://192.168.1.28:5005/Marketing/state').then(
                function(result) {
                    this.currentSong = result.data.currentTrack.artist + ' - ' + result.data.currentTrack.title;

                    this.$set('currentSong', result.data.currentTrack.artist + ' - ' + result.data.currentTrack.title);
                    setTimeout(this.getNowPlaying, 2000);
                }
            );
        },
    },
    ready: function () {
        this.getNowPlaying();
    },
});

Vue.component('loginmenu', loginMenu),
    Vue.extend({
        el: 'body',
        components:  {
            'loginmenu': loginMenu
        },
    })
