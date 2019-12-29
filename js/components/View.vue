<template>
  <k-view class="k-acl-pages-view">
    <k-header>Your Pages</k-header>
    <k-collection layout="list" :items="pages" />
  </k-view>
</template>

<script>
export default {
  data() {
    return {
      pages: [],
    };
  },
  created() {
    this.load();
  },
  methods: {
    load() {
      this.$api
        .get("panel-acl/pages")
        .then(pages => {
          this.pages = pages;
        });
    }
  }
}
</script>

<style lang="scss">
/** put your css here **/
</style>
