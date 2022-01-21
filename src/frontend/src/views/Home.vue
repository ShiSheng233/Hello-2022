<template>
  <div class="home">
    <div class="columns">
      <b-notification class="column is-half is-offset-one-quarter notice"
                      type="is-info"
                      has-icon
                      :closable="false">
        {{ notice.content }}
        <b-pagination style="margin-bottom: 0"
                      :total="notice.total"
                      v-model="notice.current"
                      @change="getNotice"
                      :per-page="1"
                      size="is-small"
                      order="is-right"
                      :simple="true">
        </b-pagination>
        <b-loading :is-full-page="false" v-model="notice.loading" :can-cancel="false"></b-loading>
      </b-notification>
    </div>
    <div class="columns">
      <div class="column">
        <div class="box">
          <h4 class="title is-4">题目</h4>
          <b-table :data="subject.data" :mobile-cards="false" :loading="subject.loading">
            <b-table-column field="id" label="ID" v-slot="props">
              <span>
                    {{ props.row.id }}
                </span>
            </b-table-column>
            <b-table-column field="title" label="题目" v-slot="props">
              <router-link :to="'/subject/'+props.row.id" target="_blank">
                {{ props.row.title }}
              </router-link>
            </b-table-column>
            <b-table-column field="score" label="分数" v-slot="props">
              <span>
                    {{ props.row.score }}
                </span>
            </b-table-column>
          </b-table>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <h4 class="title is-4">排行</h4>
          <b-table :data="rank.data" :mobile-cards="false" paginated per-page="10" backend-pagination
                   @page-change="getRank" :loading="rank.loading">
            <b-table-column field="user" label="用户" v-slot="props">
              <user :avatar="props.row.userPic" :user-name="props.row.userName"></user>
            </b-table-column>
            <b-table-column field="score" label="分数" v-slot="props">
                <span>
                    {{ props.row.userScore }}
                </span>
            </b-table-column>
          </b-table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import user from '@/components/user'

export default {

  name: 'Home',
  data() {
    return {
      notice: {
        total: 1,
        current: 1,
        content: "",
        loading: true
      },
      rank: {
        loading: true,
        totalPages: 1,
        data: []
      },
      subject: {
        loading: true,
        data: []
      }
    }
  },
  components: {user},
  mounted() {
    this.getRank()
    this.getSubject()
    this.getNotice()
  },
  methods: {
    async getRank(count) {
      if (count === undefined) {
        count = 1
        this.rank.loading = true
      }
      try {
        let result = await this.$axios.get('rank.php?page=' + count)
        this.rank.totalPages = result.data.data.totalPages
        this.rank.data = result.data.data.rank
        if (count === 1) {
          this.rank.loading = false
        }
      } catch (e) {

      }


    },
    async getSubject() {
      try {
        this.subject.loading = true
        let result = await this.$axios.get('subject.php')
        this.subject.data = result.data.data
        this.subject.loading = false
      } catch (e) {

      }
    },
    async getNotice(count) {
      this.notice.loading = true
      if (count === undefined) {
        count = 1
      }
      try {
        let result = await this.$axios.get('notice.php?id=' + count)
        this.notice.total = result.data.data.totalNotices
        this.notice.content = result.data.data.notice.content
      } catch (e) {

      } finally {
        this.notice.loading = false
      }
    }
  }
}
</script>

<style>
.home {
  margin: 0 20px;
}

.table {
  width: 100%;
}

.table td {
  vertical-align: middle !important;
}

.notice {
  margin: 0 20px;
}
</style>