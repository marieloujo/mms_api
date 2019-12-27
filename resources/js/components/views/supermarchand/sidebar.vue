</template>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('images/AdminLTELogo.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a class="d-block" href="#">Admin{{-- {{ Auth::user()->name }} --}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <router-link tag="a" to="/admin/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/admin/contrats" class="nav-link">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>Contrats</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/admin/clients" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Clients</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/admin/marchands" class="nav-link">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Marchands</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/admin/supermarchands" class="nav-link">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Super marchands</p>
                    </router-link>
                </li>


                <li class="nav-item">
                    <router-link tag="a" to="/conversation" class="nav-link">
                        <i class="nav-icon far fa-comments"></i>
                        <p>Conversations</p>
                    </router-link>
                </li>

                 <li class="nav-item">
                    <router-link tag="a" to="/profile" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>Profil</p>
                    </router-link>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>
<router-view></router-view>
</template>

<script>
    export default {
        data(){
            return {
            }
        }
    }
</script>
