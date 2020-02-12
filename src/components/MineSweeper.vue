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
      <div class="statusText">{{ statusText }}</div>
      <span style="display: flex;" v-for="(col, y) in board" :key="y">
        <div
          class="box"
          v-for="(row, x) in col"
          :key="x"
          @click="cellClicked({ x, y })"
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
    this.icon = cell.icon;
    this.coords = {
      x: cell.coords.x,
      y: cell.coords.y,
    };
  }
}
export default {
  name: 'MineSweeper',
  data() {
    return {
      board: [],
      rows: 8,
      cols: 8,
      elements: {
        bombs: {
          initial: 7,
          qty: 0,
        },
      },
      startedGame: false,
      statusText: '',
    };
  },
  computed: {
    bombedCells() {
      let cellsWithBombs = [];
      this.board.map(col => {
        cellsWithBombs = [
          ...cellsWithBombs,
          ...col.filter(item => item.icon === '💣'),
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
      } else if (this.isEmptyCell(cell)) {
        // reveal selected cell and recursively reveal surrounding empty cells
      }
      cell.hidden = true;
      this.board[y][x] = cell;
      console.log(this.board[y][x]);
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
      // this.board[0][1].value = 0;
      // this.board[0][1].icon = '🙂';
      // this.board[0][2].value = 0;
      // this.board[0][2].icon = '🧑';
      // this.getSurroundingCells(this.board[1][2]);
      this.incSurroundingCells(this.bombedCells);
      console.log(this.board);
    },
    populateBombs() {
      // Populate bombs ramdomly
      let { initial, qty } = this.elements.bombs;
      qty = initial;
      do {
        const randCol = this.random(0, this.cols);
        const randRow = this.random(0, this.rows);
        // const randCol = 0;
        // const randRow = 3;

        // Validate if bomb has not been placed already
        let currentCell = this.board[randCol][randRow];
        if (!currentCell.icon.includes('💣')) {
          // Place bomb
          this.board[randCol][randRow] = new Cell({
            hidden: false,
            value: null,
            icon: '💣',
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
            hidden: false,
            value: 0,
            icon: '',
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
      // console.log(arrayOfCells);
      // This will increment by one the value of every surrounding cell
      arrayOfCells.forEach(cell => {
        const cells = this.getSurroundingCells(cell);
        // console.log('getSurroundingCells', cells);
        // Increment only sorrounding cells if they're neither null nor icon === '💣'
        Object.values(cells).forEach(cell => {
          if (cell && cell.icon !== '💣') {
            const { y, x } = cell.coords;
            const newValue = cell.value + 1;
            cell.value = newValue;
            cell.icon = newValue;
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
      return cell.value === null && cell.icon.includes('💣');
    },
    isEmptyCell(cell) {
      return cell.value === 0 && cell.icon.includes('');
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
  border: 1px solid black;
  display: flex;
  justify-content: center;
  background-color: #fff;
  color: #444;
  cursor: pointer;
  width: 25px;
  height: 25px;
  /* padding: 20px; */
  font-size: 150%;
}
</style>
