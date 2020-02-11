<template>
  <div class="wrapper">
    <div class="setup" v-if="!startedGame">
      <div class="input">
        <label for="cols">
          Cols:
        </label>
        <input id="cols" type="number" v-model.number="cols" />
        <label for="rows">
          Rows:
        </label>
        <input id="rows" type="number" v-model.number="rows" />
      </div>
      <button @click="startGame()" class="btn">
        Start game
      </button>
    </div>
    <!-- </div> -->
    <div v-else>
      <span style="display: flex;" v-for="(col, y) in board" :key="y">
        <div
          class="box"
          v-for="(row, x) in col"
          :key="x"
          @click="boxClicked({ x, y })"
        >
          {{ row.icon }}
        </div>
      </span>
      <button @click="startedGame = false" class="btn">
        End game
      </button>
    </div>
  </div>
</template>
<script>
class Cell {
  constructor(cell = {}) {
    this.hidden = cell.hidden;
    this.value = cell.value;
    (this.icon = cell.icon),
      (this.coords = {
        x: cell.x,
        y: cell.y,
      });
  }
}
export default {
  name: 'MineSweeper',
  data() {
    return {
      board: [],
      startedGame: false,
      rows: 10,
      cols: 10,
      elements: {
        bombs: {
          initial: 8,
          qqty: 0,
        },
      },
    };
  },
  methods: {
    boxClicked(coords) {
      console.log(coords);
    },
    startGame() {
      this.setup();
      this.startedGame = true;
    },
    setup() {
      // Create an array inside an array to make a grid
      this.board = this.create2DArray();
      this.setEmpty();
      this.populateBombs();
      console.log(this.board);
      // Populate bombs ramdomly
    },
    populateBombs() {
      let { initial, qqty } = this.elements.bombs;
      qqty = initial;
      do {
        const randCol = this.random(0, this.cols);
        const randRow = this.random(0, this.rows);

        // Validate if bomb has been placed already
        let currentCell = this.board[randCol][randRow];
        if (!currentCell.icon.includes('💣')) {
          // Place bomb
          this.board[randCol][randRow] = {
            hidden: false,
            value: null,
            icon: '💣',
            coords: {
              x: randRow,
              y: randCol,
            },
          };
          qqty--;
        }
      } while (qqty > 0);
    },
    create2DArray() {
      let arr = new Array(this.cols);
      for (let i = 0; i < arr.length; i++) {
        arr[i] = new Array(this.rows);
      }
      return arr;
    },
    setEmpty() {
      // Initialize every position, first y (columns) and x (rows)
      for (let y = 0; y < this.board.length; y++) {
        for (let x = 0; x < this.board[y].length; x++) {
          // this.board[i][j] = this.elements.empty.emoji;
          this.board[y][x] = new Cell({
            hidden: false,
            value: null,
            icon: '⬜',
            coords: {
              x,
              y,
            },
          });
        }
      }
    },
    random(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min)) + min;
    },
    getSurroundings(cell, y, x) {},
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
  border: 1px solid black;
  display: flex;
  justify-content: center;
  background-color: #fff;
  color: #444;
  cursor: pointer;
  /* padding: 20px;
  font-size: 150%; */
}
</style>
