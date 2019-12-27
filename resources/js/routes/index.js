import Vue from 'vue'
import VueRouter from 'vue-router';
import LoginComponent from './../components/views/auth/login';
import AdminDashboard from './../components/views/admin/dashboard';
import ClientDashboard from './../components/views/client/dashboard';
import MarchandDashboard from './../components/views/marchand/dashboard';
import SuperMarchandDashboard from './../components/views/supermarchand/dashboard';

Vue.use(VueRouter)
const routes = [

    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        component : LoginComponent,
        name:'Login'
    },
    {
        path: '/client/dashboard',
        component : ClientDashboard,
        name:'client.dashboard',
        beforeEnter: (to, from, next) => {
            if(localStorage.getItem('token')){
                next();
            }else{
                next('/login');
            }
        }
    },
    {
        path: '/marchand/dashboard',
        component : MarchandDashboard,
        name:'marchand.dashboard',
        beforeEnter: (to, from, next) => {
            if(localStorage.getItem('token')){
                next();
            }else{
                next('/login');
            }
        }
    },
    {
        path: '/supermarchand/dashboard',
        component : SuperMarchandDashboard,
        name:'supermarchand.dashboard',
        beforeEnter: (to, from, next) => {
            if(localStorage.getItem('token')){
                next();
            }else{
                next('/login');
            }
        }
    },
  
  
];

export default new VueRouter({
    routes
})
