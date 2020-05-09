<template>
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <!-- <span v-if="updates.length == 0" class="badge badge-warning navbar-badge"></span> -->
          <span class="badge badge-warning navbar-badge">{{updates.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{updates.length}} Notifications</span>
            <li v-for="update in updates" :key="update.id">
                
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <!-- <i class="fas fa-envelope mr-2"></i>  -->

                <span class="text-muted text-sm">{{update}}</span>
            </a>
            </li>
          <div class="dropdown-divider"></div>
          <a href="#" @click="clearNotif" class="dropdown-item dropdown-footer">Clear Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
    export default {
        name: "NavbarNotifications",
        data(){
            return{
                updates:[]
            }
        },
        // props:{
        //     updates: Array
        // },
        methods:{
            getUpdates(){
               this.updates = JSON.parse(localStorage.getItem("responses") || "null") || [];
               this.$forceUpdate()
            },
            clearNotif(){
                localStorage.clear()
                this.getUpdates()
            }
        },
        created(){
            this.getUpdates()
            window.Echo.channel('Products').listen('ProductsEvent',(e)=>{
                this.$forceUpdate()
                this.getUpdates()
            })
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
