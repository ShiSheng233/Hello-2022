<template>
  <div class="subject">
    <b-loading :is-full-page="true" v-model="loading" :can-cancel="false">
    </b-loading>
    <div class="columns">
      <div class="column is-4">
        <div class="box">
          <h4 class="title is-4">{{ title }}</h4>
        </div>
        <div v-html="extendHtml"></div>
        <div class="flag">
          <b-loading :is-full-page="false" v-model="flagLoading" :can-cancel="false">
          </b-loading>
          <b-field :type="type" :message="message">
            <b-input placeholder="输入flag" v-model="flag"></b-input>
          </b-field>
          <b-button @click="submitFlag">提交</b-button>
        </div>
      </div>
      <div class="column content">
        <div class="box">
          <vue-markdown :source="content"></vue-markdown>
        </div>
      </div>
    </div>

  </div>

</template>

<script>
import VueMarkdown from 'vue-markdown'

export default {
  name: "Subject",
  components: {
    VueMarkdown
  },
  data() {
    return {
      flag: "",
      title: "",
      content: "",
      loading: true,
      message: "",
      type: "",
      extendHtml:"",
      flagLoading: false

    }
  },
  async mounted() {
    try {
      let result = await this.$axios.get("subject.php?id=" + this.$route.params.id)
      this.title = result.data.data.title
      this.content = result.data.data.description
      this.extendHtml = result.data.data.rawHtml
    } catch (e) {

    } finally {
      this.loading = false
    }
  },
  methods: {
    async submitFlag() {
      if (!this.flag) {
        return
      }
      this.flagLoading = true
      try {
        let result = await this.$axios.get("submit.php?flag=" + this.flag)
        this.$buefy.toast.open({
          duration: 5000,
          message: '提交成功',
          type: 'is-success'
        })
      } catch (e) {

      } finally {
        this.flagLoading = false
      }
    }
  }
}
</script>

<style>
.flag {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.subject {
  margin: 0 20px;
}

.content {
  /*margin: 20px;*/
}
</style>