<template>
  <div id="app">
    <MineSweeper />
  </div>
</template>

<script>
import MineSweeper from "./components/MineSweeper.vue";

export default {
  name: "App",
  components: {
    MineSweeper
  }
};
</script>