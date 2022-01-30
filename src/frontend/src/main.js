import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

axios.defaults.baseURL = "//2022.hello.shishengstore.com"
axios.defaults.withCredentials = true
axios.interceptors.response.use(function (response) {
    if (response.data.code !== 200 && response.data.code !== -10) {
        vue.$buefy.toast.open({
            duration: 5000,
            message: '错误：' + response.data.msg,
            type: 'is-danger'
        })
    }
    return response;
}, function (error) {
    console.error(error)
    vue.$buefy.toast.open({
        duration: 5000,
        message: '请求失败',
        type: 'is-danger'
    })
    return Promise.reject(error);
});
Vue.prototype.$axios = axios

import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

Vue.use(Buefy)
Vue.config.productionTip = false

const vue = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')

export {vue}