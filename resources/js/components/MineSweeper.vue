<template>
  <div class="wrapper">
    <div class="setup" v-if="!gameOver">
      <div class="input">
        <label for="cols">
          Cols:
        </label>
        <input id="cols" type="number" v-model.number="cols" />
        <label for="rows">
          Rows:
        </label>
        <input id="rows" type="number" v-model.number="rows" />
        <label for="cols">
          Mines:
        </label>
        <input id="cols" type="number" v-model.number="elements.bombs.qty" />
      </div>
      <button @click="startGame()" class="btn">
        Start game
      </button>
    </div>
    <!-- </div> -->
    <div v-else>
      <div class="statusText">{{ statusText }}</div>
      <span style="display: flex;" v-for="(col, y) in board" :key="y">
        <div
          :class="{ box: true, hidden: cell.hidden }"
          v-for="(cell, x) in col"
          :key="x"
          @click="cellClicked({ x, y })"
          @contextmenu.prevent="cellRightClicked($event, { x, y })"
        >
          {{ cell.icon }}
        </div>
      </span>
      <button @click="gameOver = false" class="btn">
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
    this.icon = cell.icon;
    this.flaged = cell.flaged;
    this.coords = {
      x: cell.coords.x,
      y: cell.coords.y,
    };
  }
}

import Axios from 'axios';
export default {
  name: 'MineSweeper',
  data() {
    return {
      board: [],
      rows: 10,
      cols: 10,
      elements: {
        bombs: {
          qty: 10,
        },
      },
      gameOver: false,
      statusText: '',
    };
  },
  computed: {
    bombedCells() {
      let cellsWithBombs = [];
      this.board.map(col => {
        cellsWithBombs = [
          ...cellsWithBombs,
          ...col.filter(item => item.value === null),
        ];
      });
      return cellsWithBombs;
    },
  },
  methods: {
    cellClicked(coords) {
      const { y, x } = coords;
      const cell = this.getCell(y, x);

      // change state of cell
      if (this.isCellABomb(cell)) {
        this.statusText = 'Boom!! You Lost';
        // reveal all cells
        console.log(cell);
        this.revealAll();
      } else if (this.isEmptyCell(cell)) {
        // reveal selected cell and recursively reveal surrounding empty cells
        this.revealCell(cell);
      } else {
        // clicked a cell with value
        cell.hidden = false;
        cell.icon = cell.value;
        this.board[y][x] = cell;
      }
      this.$forceUpdate();
    },
    cellRightClicked(e, coords) {
      const { y, x } = coords;
      const cell = this.getCell(y, x);
      if (cell.flagged) {
        cell.icon = '';
        cell.flagged = false;
      } else {
        cell.icon = '🚩';
        cell.flagged = true;
      }
      this.board[y][x] = cell;
      this.$forceUpdate();
    },
    startGame() {
      this.setup();
      this.gameOver = true;
      this.statusText = '';
    },
    setup() {
      // Create an array inside an array to make a grid
      this.board = this.create2DArray();

      this.setEmpty();
      this.populateBombs();
      this.incSurroundingCells(this.bombedCells);
    },
    populateBombs() {
      // Populate bombs ramdomly
      let qty = this.elements.bombs.qty;
      do {
        const randCol = this.random(0, this.cols);
        const randRow = this.random(0, this.rows);

        // Validate if bomb has not been placed already
        let currentCell = this.board[randCol][randRow];
        if (currentCell.value !== null) {
          // Place bomb
          this.board[randCol][randRow] = new Cell({
            hidden: true,
            value: null,
            icon: '',
            flagged: false,
            coords: {
              x: randRow,
              y: randCol,
            },
          });
          qty--;
        }
      } while (qty > 0);
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
          this.board[y][x] = new Cell({
            hidden: true,
            value: 0,
            icon: '',
            flagged: false,
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
    getSurroundingCells(cell) {
      const { y, x } = cell.coords;
      return {
        up: this.getCell(y - 1, x),
        upRight: this.getCell(y - 1, x + 1),
        right: this.getCell(y, x + 1),
        downRight: this.getCell(y + 1, x + 1),
        down: this.getCell(y + 1, x),
        downLeft: this.getCell(y + 1, x - 1),
        left: this.getCell(y, x - 1),
        upLeft: this.getCell(y - 1, x - 1),
      };
    },
    incSurroundingCells(arrayOfCells) {
      // This will increment by one the value of every surrounding cell
      arrayOfCells.forEach(cell => {
        const cells = this.getSurroundingCells(cell);
        // Increment only sorrounding cells if they're neither null
        Object.values(cells).forEach(cell => {
          if (cell && cell.value !== null) {
            const { y, x } = cell.coords;
            const newValue = cell.value + 1;
            cell.value = newValue;
            this.board[y][x] = cell;
          }
        });
      });
    },
    getCell(y, x) {
      let cell;
      try {
        cell = this.board[y][x];
      } catch (e) {
        cell = null;
      }
      return cell;
    },
    getCellValue(cell) {
      return cell.value;
    },
    setCellValue(cell, newValue) {
      cell.value = newValue;
    },
    isCellABomb(cell) {
      return cell.value === null;
    },
    isEmptyCell(cell) {
      return cell.value === 0;
    },
    revealCell(cell) {
      cell.hidden = false;
      const { y, x } = cell.coords;
      this.board[y][x] = cell;

      const cells = this.getSurroundingCells(cell);
      // get only those that are not bombs
      Object.values(cells)
        .filter(cell => cell && cell.hidden === true && cell.value === 0)
        .forEach(cell => {
          cell.hidden = false;
          this.board[y][x] = cell;
          console.log('Revealed cells: ', this.board[y][x]);
          this.revealCell(cell);
        });
    },
    revealAll() {
      let board = this.board;
      for (let y = 0; y < board.length; y++) {
        for (let x = 0; x < board.length; x++) {
          const cell = board[y][x];
          cell.hidden = false;
          cell.icon = cell.value;
          if (cell.value === 0) {
            cell.icon = '';
          }
          if (cell.value === null) {
            cell.icon = '💣';
          }
          board[y][x] = cell;
        }
      }
      this.board = board;
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

.box {
  border: 1px solid black;
  display: flex;
  justify-content: center;
  background-color: #fff;
  color: #444;
  cursor: pointer;
  width: 32px;
  height: 32px;
  font-size: 150%;
}
.hidden {
  background-color: #ddd;
}
</style>
