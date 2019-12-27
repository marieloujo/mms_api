<template>
  <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="dark"
                dark
                flat
              >
                <v-toolbar-title>Login form</v-toolbar-title>
                
                 <v-progress-linear
                    :active="loading"
                    :indeterminate="loading"
                    absolute
                    bottom
                    color="white accent-4"
                  ></v-progress-linear>
                  <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field color="success"
                    label="Login"
                    name="login"
                    v-model="login"
                    prepend-icon="mdi-account-circle-outline"
                    type="text"
                  />

                  <v-text-field color="success"
                    id="password"
                    label="Password"
                    name="password"
                    v-model="password"
                    prepend-icon="mdi-account-lock-outline"
                    type="password"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="dark" @click="auth()">Login</v-btn>
              </v-card-actions>
            </v-card>
            <v-snackbar v-model="snackbar">
                 <div v-if="Array.isArray(this.errors.message)">
                    <v-card-text v-for='(error,id) in errors.message' :key="id">
                      <p v-for='log in error.login' >{{log}}</p>
                      <p v-for='pass in error.password' >{{pass}}</p>
                  </v-card-text>
                 </div>
                 <p v-else> {{errors.message}}</p>
                <v-btn color="red" text @click="snackbar = false">
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
        data(){
            return{
                login:'',
                password:'',
                accessToken:'',
                loading: false,
                snackbar: false,
                errors:{
                  login:'',
                  password:'',
                  message:'',

                },
            }
        },
        methods:{
            auth: function(){
                // Add a request interceptor
                axios.interceptors.request.use((config)=> {
                    // Do something before request is sent
                    
                    this.loading=true

                    return config;
                  }, (error) =>{
                    // Do something with request error
                    this.loading=false
                    return Promise.reject(error);
                  });

                // Add a response interceptor
                axios.interceptors.response.use((response)=> {
                    // Any status code that lie within the range of 2xx cause this to trigger=>
                    // Do something with response data
                    this.loading=false;
                    return response;
                    // Do something with request error
                  }, (error) =>{
                    // Any status codes that falls outside the range of 2xx cause this function to trigger
                    // Do something with response error
                    this.loading=false
                    return Promise.reject(error);
                  });
                  axios.post('api/login',{'login':this.login,'password':this.password})
                  .then(response =>{
                                            
                      localStorage.setItem('token',response.data.access_token);
                      axios.defaults.headers.common['Authorization'] = 'Bearer '+localStorage.getItem('token'); 
                                                
                      this.$router.push('/client/dashboard')
                      .then(res => console.log('LoggedIn successful'))
                      .catch(err => {
                              localStorage.removeItem('token')                      
                              console.log(err)
                      })
                  })
                  .catch(err => {
                      localStorage.removeItem('token') 
                      this.errors=err.response.data.errors;
                                         
                      this.snackbar=true;
                      
                  });
            },
        },
    }
</script>
