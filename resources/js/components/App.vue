<template>


    <v-content>
    <router-view></router-view>
    </v-content>

</template>
<script>
  export default {
    data () {
      return {
        dialog: true,
        notifications: false,
        sound: true,
        widgets: false,
      }
    },
  }
</script>
