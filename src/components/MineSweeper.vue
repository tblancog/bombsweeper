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
    <!-- <pre>{{ board }}</pre> -->
    <!-- <span
      class="box"
      style="display: flex;"
      v-for="(item, idx) in board"
      v-bind:key="idx"
    >
      <div class="box" v-for="(item, idx) in board" v-bind:key="idx">
        {{ item }}
      </div>
    </span> -->
    <div class="box" v-for="(item, idx) in board" v-bind:key="idx">
      {{ item }}
    </div>
  </div>
  <!-- </div> -->
</template>
<script>
export default {
  name: 'MineSweeper',
  data() {
    return {
      board: [
        [1, 4, 6],
        [8, 2, 11],
        [18, 22, 411],
      ],
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
      this.board = this.setUpBoard(this.cols, this.rows);
      for (let i = 0; i < this.cols; i++) {
        for (let j = 0; j < this.rows; j++) {
          this.board[i][j] = this.getRandomInt(1, 255);
        }
      }
    },
    setUpBoard(cols, rows) {
      let arr = new Array(cols);
      for (let i = 0; i < arr.length; i++) {
        arr[i] = new Array(rows);
      }
      return arr;
    },
    getRandomInt(min, max) {
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
.wrapper {
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
