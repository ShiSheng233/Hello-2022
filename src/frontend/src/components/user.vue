<template>
  <div class="user">
    <figure class="image is-48x48">
      <img class="is-rounded" :src="avatar">
    </figure>
    <span>{{ userName }}</span>
  </div>
</template>

<script>
export default {
  name: "user",
  props:[
    "avatar","userName"
  ]
}
</script>

<style scoped>
  .user{
    display: flex;
    align-items: center;
  }
  figure{
    margin-right: 10px;
  }
</style>