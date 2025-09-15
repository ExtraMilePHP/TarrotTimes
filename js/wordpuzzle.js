const words = ['TRAINING', 'COMPENSATION', 'RESOURCES', 'BENIFITS', 'CAREER'];
const gridSize = 14 // Adjust grid size as needed
const puzzleGrid = document.getElementById('puzzle-grid');
const wordsToFind = document.getElementById('words-to-find');
const matchedWordsContainer = document.getElementById('matched-words-container');
const pointsDisplay = document.getElementById('points');
let storeAnswer = [];

// var points = 0;
const foundWords = new Set();
const selectedWords = new Set(); // Track selected words

// Function to create an empty grid
function createEmptyGrid(size) {
    return Array.from({ length: size }, () => Array(size).fill(''));
}

// Function to place words in the grid
function placeWordsInGrid(grid, words) {
    words.forEach(word => {
        let placed = false;
        let attempts = 0;
        while (!placed && attempts < 100) { // limit attempts to prevent infinite loop
            const direction = Math.floor(Math.random() * 3); // 0 = horizontal, 1 = vertical, 2 = diagonal
            const row = Math.floor(Math.random() * gridSize);
            const col = Math.floor(Math.random() * gridSize);
            if (canPlaceWord(grid, word, row, col, direction)) {
                placeWord(grid, word, row, col, direction);
                placed = true;
            }
            attempts++;
        }
        if (!placed) {
            console.error(`Failed to place the word: ${word}`);
        }
    });
}

// // Function to check if a word can be placed at a specific position
// function canPlaceWord(grid, word, row, col, direction) {
//     if (direction === 0) { // horizontal
//         if (col + word.length > gridSize) return false;
//         for (let i = 0; i < word.length; i++) {
//             if (grid[row][col + i] !== '' && grid[row][col + i] !== word[i]) return false;
//         }
//     } else if (direction === 1) { // vertical
//         if (row + word.length > gridSize) return false;
//         for (let i = 0; i < word.length; i++) {
//             if (grid[row + i][col] !== '' && grid[row + i][col] !== word[i]) return false;
//         }
//     } else { // diagonal
//         if (row + word.length > gridSize || col + word.length > gridSize) return false;
//         for (let i = 0; i < word.length; i++) {
//             if (grid[row + i][col + i] !== '' && grid[row + i][col + i] !== word[i]) return false;
//         }
//     }
//     return true;
// }

// Function to check if a word can be placed at a specific position without overlap
function canPlaceWord(grid, word, row, col, direction) {
    if (direction === 0) { // horizontal
        if (col + word.length > gridSize) return false;
        for (let i = 0; i < word.length; i++) {
            if (grid[row][col + i] !== '') return false;
        }
    } else if (direction === 1) { // vertical
        if (row + word.length > gridSize) return false;
        for (let i = 0; i < word.length; i++) {
            if (grid[row + i][col] !== '') return false;
        }
    } else { // diagonal
        if (row + word.length > gridSize || col + word.length > gridSize) return false;
        for (let i = 0; i < word.length; i++) {
            if (grid[row + i][col + i] !== '') return false;
        }
    }
    return true;
}

// Function to place a word in the grid
function placeWord(grid, word, row, col, direction) {
    if (direction === 0) { // horizontal
        for (let i = 0; i < word.length; i++) {
            grid[row][col + i] = word[i];
        }
    } else if (direction === 1) { // vertical
        for (let i = 0; i < word.length; i++) {
            grid[row + i][col] = word[i];
        }
    } else { // diagonal
        for (let i = 0; i < word.length; i++) {
            grid[row + i][col + i] = word[i];
        }
    }
}

// Function to fill the rest of the grid with random letters
function fillGridWithRandomLetters(grid) {
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            if (grid[row][col] === '') {
                grid[row][col] = String.fromCharCode(65 + Math.floor(Math.random() * 26));
            }
        }
    }
}

// Function to render the grid on the page
function renderGrid(grid) {
    puzzleGrid.innerHTML = '';
    for (let row = 0; row < grid.length; row++) {
        const tr = document.createElement('tr');
        for (let col = 0; col < grid[row].length; col++) {
            const td = document.createElement('td');
            td.textContent = grid[row][col];
            td.dataset.row = row;
            td.dataset.col = col;
            tr.appendChild(td);
        }
        puzzleGrid.appendChild(tr);
    }
}

// Main function to initialize the puzzle
function initPuzzle() {
    const grid = createEmptyGrid(gridSize);
    placeWordsInGrid(grid, words);
    fillGridWithRandomLetters(grid);
    renderGrid(grid);
}

let selectedCells = [];
let isMouseDown = false;
let startCell = null;

// function handleCellMouseDown(event) {
//     const cell = event.target;
//     if (cell.tagName === 'TD') {
//         isMouseDown = true;
//         selectedCells = [cell];
//         startCell = cell;
//         cell.classList.add('highlighted');
//         event.preventDefault(); // Prevent text selection
//     }
// }

// function handleCellMouseOver(event) {
//     if (isMouseDown) {
//         clearHighlights();
//         const cell = event.target;
//         if (cell.tagName === 'TD') {
//             const endCell = cell;
//             highlightPath(startCell, endCell);
//         }
//     }
// }


function handleCellMouseDown(event) {
    const cell = event.target;
    if (cell.tagName === 'TD' && !cell.classList.contains('selected')) {
        isMouseDown = true;
        selectedCells = [cell];
        startCell = cell;
        cell.classList.add('highlighted');
        event.preventDefault(); // Prevent text selection
    }
}

function handleCellMouseOver(event) {
    if (isMouseDown) {
        clearHighlights();
        const cell = event.target;
        if (cell.tagName === 'TD' && !cell.classList.contains('selected')) {
            const endCell = cell;
            highlightPath(startCell, endCell);
        }
    }
}

function handleMouseUp() {
    if (isMouseDown) {
        const selectedWord = selectedCells.map(cell => cell.textContent).join('');
        const reversedSelectedWord = selectedCells.map(cell => cell.textContent).reverse().join('');
        
        if (words.includes(selectedWord) || words.includes(reversedSelectedWord)) {
            if (!selectedWords.has(selectedWord) && !selectedWords.has(reversedSelectedWord)) {
                selectedCells.forEach(cell => cell.classList.add('selected'));
                point += 1;
                points = points + 1;
                pointsDisplay.textContent = point;
                createMatchedWordTextBox(selectedWord);
                selectedWords.add(selectedWord);
                selectedWords.add(reversedSelectedWord);
            } else {
                // If the word has already been selected, re-add the 'selected' class
                selectedCells.forEach(cell => {
                    if (cell.textContent === selectedWord || cell.textContent === reversedSelectedWord) {
                        cell.classList.add('selected');
                    }
                });
            }
        } else {
            // Clear temporary highlights but not fixed selections
            clearTemporaryHighlights();
        }
        
        // Check points and display submit button if points equal 5
        const submitButtonRow = document.querySelector('.submitshow');
        if (points >= 2) {
            submitButtonRow.style.display = 'block';
        } 
        
        // Store points in localStorage
        window.point = point;
        // localStorage.setItem('points', points);
        console.log("points ", point);

        selectedCells = [];
        isMouseDown = false;
    }
}

function createMatchedWordTextBox(word) {
    const inputBox = document.createElement('input');
    inputBox.type = 'text';
    inputBox.readOnly = true;
    inputBox.value = word;
    matchedWordsContainer.appendChild(inputBox);
    storeAnswer.push(word);
    // console.log(word);
    let textValues = storeAnswer.filter(item => isNaN(item));
    window.textValues = textValues; 
    // console.log(storeAnswer , "dfghjk");
}

// function clearTemporaryHighlights() {
//     selectedCells.forEach(cell => cell.classList.remove('highlighted'));
//     selectedCells = [];
// }

// function clearHighlights() {
//     selectedCells.forEach(cell => {
//         if (!cell.classList.contains('selected')) {
//             cell.classList.remove('highlighted');
//         }
//     });
//     selectedCells = [];
// }

// function highlightPath(startCell, endCell) {
//     const startRow = parseInt(startCell.dataset.row);
//     const startCol = parseInt(startCell.dataset.col);
//     const endRow = parseInt(endCell.dataset.row);
//     const endCol = parseInt(endCell.dataset.col);

//     const deltaX = endCol - startCol;
//     const deltaY = endRow - startRow;

//     const absDeltaX = Math.abs(deltaX);
//     const absDeltaY = Math.abs(deltaY);

//     if (absDeltaX === absDeltaY || deltaX === 0 || deltaY === 0) {
//         const stepX = deltaX === 0 ? 0 : deltaX / absDeltaX;
//         const stepY = deltaY === 0 ? 0 : deltaY / absDeltaY;
        
//         for (let i = 0; i <= Math.max(absDeltaX, absDeltaY); i++) {
//             const row = startRow + i * stepY;
//             const col = startCol + i * stepX;
//             const cell = puzzleGrid.querySelector(`td[data-row='${row}'][data-col='${col}']`);
//             if (cell) {
//                 cell.classList.add('highlighted');
//                 console.log("correct");
//                 selectedCells.push(cell);
//             }
//         }
//     }
// }

function clearTemporaryHighlights() {
    selectedCells.forEach(cell => {
        if (!cell.classList.contains('selected')) {
            cell.classList.remove('highlighted');
        }
    });
    selectedCells = [];
}

function clearHighlights() {
    selectedCells.forEach(cell => {
        if (!cell.classList.contains('selected')) {
            cell.classList.remove('highlighted');
        }
    });
    selectedCells = [];
}

// function highlightPath(startCell, endCell) {
//     const startRow = parseInt(startCell.dataset.row);
//     const startCol = parseInt(startCell.dataset.col);
//     const endRow = parseInt(endCell.dataset.row);
//     const endCol = parseInt(endCell.dataset.col);

//     const deltaX = endCol - startCol;
//     const deltaY = endRow - startRow;

//     const absDeltaX = Math.abs(deltaX);
//     const absDeltaY = Math.abs(deltaY);

//     if (absDeltaX === absDeltaY || deltaX === 0 || deltaY === 0) {
//         const stepX = deltaX === 0 ? 0 : deltaX / absDeltaX;
//         const stepY = deltaY === 0 ? 0 : deltaY / absDeltaY;
        
//         for (let i = 0; i <= Math.max(absDeltaX, absDeltaY); i++) {
//             const row = startRow + i * stepY;
//             const col = startCol + i * stepX;
//             const cell = puzzleGrid.querySelector(`td[data-row='${row}'][data-col='${col}']`);
//             if (cell && !cell.classList.contains('selected')) {
//                 cell.classList.add('highlighted');
//                 selectedCells.push(cell);
//             }
//         }
//     }
// }

function highlightPath(startCell, endCell) {
    const startRow = parseInt(startCell.dataset.row);
    const startCol = parseInt(startCell.dataset.col);
    const endRow = parseInt(endCell.dataset.row);
    const endCol = parseInt(endCell.dataset.col);

    const deltaX = endCol - startCol;
    const deltaY = endRow - startRow;

    const absDeltaX = Math.abs(deltaX);
    const absDeltaY = Math.abs(deltaY);

    if ((absDeltaX === absDeltaY || deltaX === 0 || deltaY === 0) && (deltaX >= 0 && deltaY >= 0)) {
        const stepX = deltaX === 0 ? 0 : deltaX / absDeltaX;
        const stepY = deltaY === 0 ? 0 : deltaY / absDeltaY;
        
        for (let i = 0; i <= Math.max(absDeltaX, absDeltaY); i++) {
            const row = startRow + i * stepY;
            const col = startCol + i * stepX;
            const cell = puzzleGrid.querySelector(`td[data-row='${row}'][data-col='${col}']`);
            if (cell && !cell.classList.contains('selected')) {
                cell.classList.add('highlighted');
                selectedCells.push(cell);
            }
        }
    }
}

puzzleGrid.addEventListener('mousedown', handleCellMouseDown);
puzzleGrid.addEventListener('mouseover', handleCellMouseOver);
document.addEventListener('mouseup', handleMouseUp);

// Touch events for mobile support
puzzleGrid.addEventListener('touchstart', function(event) {
    event.preventDefault(); // Prevent text selection
    const touch = event.touches[0];
    const cell = document.elementFromPoint(touch.clientX, touch.clientY);
    handleCellMouseDown({ target: cell });
});

puzzleGrid.addEventListener('touchmove', function(event) {
    const touch = event.touches[0];
    const cell = document.elementFromPoint(touch.clientX, touch.clientY);
    handleCellMouseOver({ target: cell });
});

puzzleGrid.addEventListener('touchend', function(event) {
    handleMouseUp();
});

initPuzzle();
