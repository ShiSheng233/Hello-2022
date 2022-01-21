<template>
  <div id="app">
    <b-navbar shadow>
      <template #brand>
        <b-navbar-item tag="router-link" :to="{ path: '/' }">
          Hello 2022
        </b-navbar-item>
      </template>

      <template #end>
        <!--        <b-navbar-item href="#">-->
        <!--          首页-->
        <!--        </b-navbar-item>-->
        <!--        <b-navbar-item href="#">-->
        <!--          排行榜-->
        <!--        </b-navbar-item>-->
        <!--        <b-navbar-item href="#">-->
        <!--          关于-->
        <!--        </b-navbar-item>-->
        <b-navbar-item tag="div">
          <b-loading :is-full-page="false" v-model="loading" :can-cancel="false"></b-loading>
          <b-tooltip :label="'当前分数：'+userScore"
                     position="is-bottom">
            <user v-if="isLogin" :avatar="avatar" :user-name="userName">
            </user>
          </b-tooltip>


          <b-button type="is-light"
                    icon-left="github" @click="clickButton">
            Login with Github
          </b-button>
        </b-navbar-item>
      </template>
    </b-navbar>
    <!--    <div id="nav">-->
    <!--      <router-link to="/">主页</router-link>-->
    <!--      |-->
    <!--      <router-link to="/about">关于</router-link>-->
    <!--    </div>-->
    <div class="container">
      <router-view/>
      </div>

  </div>
</template>


<script>
import user from '@/components/user'


export default {
  data(){
    return{
      isLogin:false,
      loginUrl:"",
      loading:true,
      avatar:"",
      userName:"",
      userScore:""
    }
  },
  async mounted() {
    try{
      const result = await this.$axios.get("user.php")
      if(result.data.code === -10){
        this.loginUrl = result.data.data.loginUrl
      }else if(result.data.code===200){
        this.isLogin = true
        this.avatar = result.data.data.userPic
        this.userName = result.data.data.userName
        this.userScore = result.data.data.userScore
      }
      // TODO
      // result = await this.$axios.get("notice.php")
    }catch(e){

    }finally {
      this.loading=false
    }


  },
  components: {
    user
  },
  methods:{
    clickButton(){
      if(this.loginUrl!==""){
        window.location.href=this.loginUrl
      }
    }
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /*text-align: center;*/
  color: #2c3e50;
}

.navbar {
  margin-bottom: 30px;
}
</style>
