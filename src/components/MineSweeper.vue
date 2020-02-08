<template>
  <div class="wrapper">
    <div class="setup">
      <div class="input">
        Rows: <input type="text" v-model="rows" /> Cols:
        <input type="text" v-model="cols" />
      </div>
      <button @click="setup()" class="btn">
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
      v-for="(col, idx) in board"
      v-bind:key="idx"
    >
      <div class="box" v-for="(item, idx) in col" v-bind:key="idx">
        {{ item }}
      </div>
    </span>
    <!-- </div> -->
  </div>
</template>
<script>
export default {
  name: 'MineSweeper',
  data() {
    return {
      board: [],
      gameOver: true,
      rows: 5,
      cols: 4,
      bombs: 8,
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
    setup() {
      // Create an array inside an array to make a grid
      this.board = this.create2DArray(this.cols, this.rows);
      // Populate bombs ramdomly
      do {
        // First check if bom has been placed there
        const randomColIndex = this.random(0, this.cols - 1);
        const randomRowIndex = this.random(0, this.rows - 1);
        if (this.board[randomColIndex][randomRowIndex] === this.elements.bomb) {
          continue;
        } else {
          // Place bomb
          this.board[randomColIndex][randomRowIndex] = this.elements.bomb;
          this.bombs--;
        }
      } while (this.bombs > 0);
    },
    create2DArray(cols, rows) {
      let arr = new Array(cols);
      for (let i = 0; i < arr.length; i++) {
        arr[i] = new Array(rows);
      }
      return arr;
    },
    random(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min)) + min;
    },
  },
};
</script>

<style lang="css" scoped>
body {
  margin: 40px;
}
/* .wrapper {
} */
/* .grid {
  display: grid;
  grid-gap: 5px;
  background-color: #fff;
  justify-items: center;
  justify-content: center;
} */

.box {
  display: flex;
  justify-content: center;
  background-color: #fff;
  color: #444;
  padding: 20px;
  font-size: 150%;
}
</style>
