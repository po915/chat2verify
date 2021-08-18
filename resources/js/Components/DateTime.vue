<template>
  <span>{{ datetime }}</span>
</template>

<script>
import TimeAgo from 'javascript-time-ago';
import en from 'javascript-time-ago/locale/en';

const {DateTime} = require("luxon");

TimeAgo.addDefaultLocale(en);

const timeAgo = new TimeAgo('en-US');

export default {
  name: "DateTime",
  props: ['date', 'relative', 'update'],

  watch: {
    date: {
      immediate: true, handler(date) {
        this.datetime = this.formatDate(date);
      },
    }
  },

  mounted() {
    if (this.update) {
      this.interval = setInterval(() => {
        this.datetime = this.formatDate(this.date);
      }, 20 * 1000);
    }
  },

  destroyed() {
    if (this.update) {
      clearInterval(this.interval);
    }
  },

  data() {
    return {interval: null, datetime: '',};
  },

  methods: {
    timeSince(date) {
      return timeAgo.format(date);
    },

    formatDate(date) {
      if (this.relative) {
        return this.timeSince(DateTime.fromSeconds(date).toJSDate());
      }

      return DateTime.fromSeconds(date).toFormat("ff");
    }
  }
}
</script>

<style scoped>

</style>
