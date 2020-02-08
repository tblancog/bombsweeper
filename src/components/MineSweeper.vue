<template>
  <div class="wrapper">
    <div class="setup" v-if="!gameOver">
      <div class="input">
        Rows: <input type="text" v-model="rows" /> Cols:
        <input type="text" v-model="cols" />
      </div>
      <button @click="setUpBoard()" class="btn">
        Start game
      </button>
    </div>
    <!-- <div
      class="grid"
      v-bind:style="{
        gridTemplateColumns: `repeat(${cols}, 50px)`,
        gridTemplateRows: `repeat(${rows}, 50px)`,
      }"
    > -->
    <span
      class="box"
      style="display: flex;"
      v-for="(row, rowIndex) in board"
      v-bind:key="rowIndex"
    >
      <div class="box" v-for="(col, colIndex) in row" v-bind:key="colIndex">
        {{ col }}
      </div>
    </span>
  </div>
  <!-- </div> -->
</template>
<script>
export default {
  name: 'MineSweeper',
  data() {
    return {
      board: [
        [1, 2, 4, 6],
        [3, 6, 8, 9],
        [13, 16, 18],
      ],
      gameOver: false,
      rows: 5,
      cols: 5,
      elements: {
        bomb: '💣',
        empty: '⬜',
      },
    };
  },
  computed: {
    getBoard() {
      return this.board;
    },
  },
  methods: {
    setUpBoard() {
      return this.rows * this.cols;
    },
  },
};
</script>

<style lang="css" scoped>
body {
  margin: 40px;
}
.setup {
  display: flex;
  justify-content: center;
}
.grid {
  display: grid;
  grid-gap: 5px;
  background-color: #fff;
  justify-items: center;
  justify-content: center;
}

.box {
  background-color: #fff;
  color: #444;
  padding: 20px;
  font-size: 150%;
}
</style>
