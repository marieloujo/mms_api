<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" app clipped >
      <v-list dense>
            <v-list-item v-for="item in items" :key="item.text" link >
                <v-list-item-action>
                    <v-icon>mdi-{{ item.icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>
                        {{ item.text }}
                    </v-list-item-title>
                    
                </v-list-item-content>
            </v-list-item>
            <v-list-item  class="mt-4" link>
                <v-list-item-action>
                    <v-icon color="grey darken-1">mdi-plus-circle-outline</v-icon>
                </v-list-item-action>
                <v-list-item-title class="grey--text text--darken-1">Browse Channels</v-list-item-title>
            </v-list-item>
            
            <v-list-item link>
                <v-list-item-action>
                    <v-icon color="grey darken-1">mdi-settings</v-icon>
                </v-list-item-action>
                <v-list-item-title class="grey--text text--darken-1">Manage Subscriptions</v-list-item-title>
            </v-list-item>
            <v-list-item link @click='logout'>
                <v-list-item-action>
                    <v-icon color="grey darken-1">mdi-logout</v-icon>
                </v-list-item-action>
                <v-list-item-title class="grey--text text--darken-1">logout</v-list-item-title>
            </v-list-item>
          
            <v-subheader class="mt-4 grey--text text--darken-1">Conversations <v-icon color="grey darken-1">mdi-comments</v-icon></v-subheader>
        
            <v-list>
                <v-list-item v-for="item in items2" :key="item.text" link>
                    <v-list-item-avatar>
                        <img :src="`https://randomuser.me/api/portraits/men/${item.picture}.jpg`" alt="">
                    </v-list-item-avatar>
                    <v-list-item-title v-text="item.text" />
                </v-list-item>
            </v-list>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-left color="black" dense >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" color="white" />
        <v-icon class="mx-4">fab fa-youtube</v-icon>
        <v-toolbar flat color="transparent" >
            <v-toolbar-title class="mr-12 align-center">
              <span class="title white--text">Prévoyance déces</span>
            </v-toolbar-title>
        </v-toolbar>
        <v-spacer />
        <v-row background-color="white" align="center" style="max-width: 650px" >
            <v-text-field
                :append-icon-cb="() => {}"
                placeholder="Recherche..."
                single-line
                append-icon="mdi-magnify"
                color="white"
                hide-details
            />
        </v-row>
    </v-app-bar>

    <v-content>
      <v-container class="fill-height">
        <v-row
          justify="center"
          align="center">
          <v-col class="shrink">
                <v-snackbar v-model="snackbar">
                    {{snackbar_message}}
                  <v-btn color="green" text @click="snackbar = false">
                    Close
                  </v-btn>
                </v-snackbar>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
  export default {
      props: {
          source: String,
      },
      data: () => ({
          drawer: null,
          items: [
            { icon: 'view-dashboard-outline', text: 'Home' },
            { icon: 'security', text: 'Contrats' },
            { icon: 'account-supervisor-circle', text: 'Marchands' },
            { icon: 'account', text: 'Parametre' },
          ],
          items2: [
            { picture: 28, text: 'Joseph' },
            { picture: 38, text: 'Apple' },
            { picture: 48, text: 'Xbox Ahoy' },
            { picture: 58, text: 'Nokia' },
            { picture: 78, text: 'MKBHD' },
          ],
          snackbar:false,
          snackbar_message:'You are logged in successful !!!',
          access_token:'',
      }),
      created () {
        this.$vuetify.theme.light = true
        this.snackbar=true;
      },
      methods: {
          logout : function(){
                  this.access_token=localStorage.getItem('token');                
                  axios.defaults.headers.common['Authorization'] = 'Bearer '+this.access_token;                
                  axios.post('api/logout')
                  .then(() =>{
                      localStorage.removeItem('token')
                      delete axios.defaults.headers.common['Authorization'];
                      this.$router.push('/login')
                      .then(res => console.log('LoggedIn successful'))
                      .catch(err => { console.log(err)})
                  })
                  .catch(err => {
                     /*  localStorage.setItem('token',this.access_token);
                      axios.defaults.headers.common['Authorization'] =  this.access_token;    */
                       
                       this.snackbar_message=err.response.data.errors.message[0];
                      
                      this.snackbar=true;
                                            
                  });

          }
      },
  }
</script>