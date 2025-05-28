// Laravel Puzzle Game Implementation
class PuzzleGame {
  constructor(config = {}) {
    // Game configuration
    this.config = {
      rows: config.rows || 3,
      columns: config.columns || 3,
      timeLimit: config.timeLimit || 60, // seconds
      maxRounds: config.maxRounds || 3,
      playerId: config.playerId || null,
      roomId: config.roomId || null,
      apiBaseUrl: config.apiBaseUrl || "http://127.0.0.1:8000",
      csrfToken:
        config.csrfToken ||
        document
          .querySelector('meta[name="csrf-token"]')
          ?.getAttribute("content"),
      ...config,
    };

    // Game state
    this.gameState = {
      selectedPiece: null,
      placedPieces: 0,
      timeLeft: this.config.timeLimit,
      gameActive: false,
      score: 0,
      round: 1,
      isWin: false,
      timer: null,
      winnerCheckInterval: null,
    };

    // Puzzle configuration
    this.puzzlePieces = [
      {
        id: "A1",
        src: this.config.useServerImages
          ? "/assets/images/3x3/A1.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzRFQ0RDNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QTE8L3RleHQ+PC9zdmc+",
      },
      {
        id: "A2",
        src: this.config.useServerImages
          ? "/assets/images/3x3/A2.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI0ZGNkI2QiI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QTI8L3RleHQ+PC9zdmc+",
      },
            {
        id: "A3",
        src: this.config.useServerImages
          ? "/assets/images/3x3/A3.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI0ZGNkI2QiI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QTI8L3RleHQ+PC9zdmc+",
      },
      {
        id: "B1",
        src: this.config.useServerImages
          ? "/assets/images/3x3/B1.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzQ1QjdEMSI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjE8L3RleHQ+PC9zdmc+",
      },
      {
        id: "B2",
        src: this.config.useServerImages
          ? "/assets/images/3x3/B2.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzk2Q0VCNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjI8L3RleHQ+PC9zdmc+",
      },
            {
        id: "B3",
        src: this.config.useServerImages
          ? "/assets/images/3x3/B3.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzk2Q0VCNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjI8L3RleHQ+PC9zdmc+",
      },
            {
        id: "C1",
        src: this.config.useServerImages
          ? "/assets/images/3x3/C1.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzQ1QjdEMSI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjE8L3RleHQ+PC9zdmc+",
      },
      {
        id: "C2",
        src: this.config.useServerImages
          ? "/assets/images/3x3/C2.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzk2Q0VCNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjI8L3RleHQ+PC9zdmc+",
      },
            {
        id: "C3",
        src: this.config.useServerImages
          ? "/assets/images/3x3/C3.png"
          : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzk2Q0VCNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjI8L3RleHQ+PC9zdmc+",
      },
    ];

    this.correctOrder = ["A1", "A2", "A3", "B1", "B2", "B3", "C1", "C2", "C3"];
  }

  // Initialize the game
  init() {
    this.createBoard();
    this.createPieces();
    this.initializeScoreDisplay();
    this.showStatusMessage("Welcome! Click a puzzle piece to get started.");

    // Auto-start with countdown
    this.startGameWithCountdown();
  }

  // Create game board
  createBoard() {
    const board = document.getElementById("board");
    if (!board) {
      console.error("Board element not found");
      return;
    }

    board.innerHTML = "";

    this.correctOrder.forEach((pieceId, index) => {
      const slot = document.createElement("img");
      const piece = this.puzzlePieces.find((p) => p.id === pieceId);

      slot.src = piece ? piece.src : "";
      slot.dataset.position = index;
      slot.dataset.expectedId = pieceId;
      slot.classList.add("board-slot", "board");
      slot.style.filter = "grayscale(100%) opacity(0.5)";

      // Add event listeners for both click and drag & drop
      slot.addEventListener("click", (e) => this.handleSlotClick(e));
      this.addDragListeners(slot);

      board.appendChild(slot);
    });
  }

  // Create puzzle pieces
  createPieces() {
    const pieces = document.getElementById("pieces");
    if (!pieces) {
      console.error("Pieces element not found");
      return;
    }

    pieces.innerHTML = "";

    // Shuffle pieces for random order
    const shuffledPieces = [...this.puzzlePieces].sort(
      () => Math.random() - 0.5
    );

    shuffledPieces.forEach((piece) => {
      const img = document.createElement("img");
      img.src = piece.src;
      img.dataset.pieceId = piece.id;
      img.classList.add("puzzle-piece");
      img.draggable = true;

      // Add event listeners
      img.addEventListener("click", (e) => this.handlePieceClick(e));
      this.addDragListeners(img);

      pieces.appendChild(img);
    });
  }

  // Add drag and drop listeners
  addDragListeners(element) {
    // Desktop drag events
    element.addEventListener("dragstart", (e) => this.dragStart(e));
    element.addEventListener("dragover", (e) => this.dragOver(e));
    element.addEventListener("dragenter", (e) => this.dragEnter(e));
    element.addEventListener("dragleave", (e) => this.dragLeave(e));
    element.addEventListener("drop", (e) => this.dragDrop(e));
    element.addEventListener("dragend", (e) => this.dragEnd(e));

    // Mobile touch events
    element.addEventListener("touchstart", (e) => this.touchStart(e), {
      passive: false,
    });
    element.addEventListener("touchmove", (e) => this.touchMove(e), {
      passive: false,
    });
    element.addEventListener("touchend", (e) => this.touchEnd(e));
  }

  // Handle piece click
  handlePieceClick(e) {
    if (!this.gameState.gameActive || e.target.classList.contains("hidden"))
      return;

    // Clear previous selections
    document.querySelectorAll(".puzzle-piece").forEach((p) => {
      p.classList.remove("selected");
    });
    document.querySelectorAll(".board-slot").forEach((s) => {
      s.classList.remove("target-highlight");
    });

    // Select this piece
    this.gameState.selectedPiece = e.target;
    e.target.classList.add("selected");

    // Highlight correct target slot
    const pieceId = e.target.dataset.pieceId;
    const targetIndex = this.correctOrder.indexOf(pieceId);
    const targetSlot = document.querySelector(
      `.board-slot[data-position="${targetIndex}"]`
    );

    if (targetSlot && !targetSlot.classList.contains("placed")) {
      targetSlot.classList.add("target-highlight");
    }

    this.showStatusMessage(
      "Now click the highlighted slot to place the piece!"
    );
  }

  // Handle slot click
  handleSlotClick(e) {
    if (
      !this.gameState.gameActive ||
      !this.gameState.selectedPiece ||
      e.target.classList.contains("placed")
    )
      return;

    const pieceId = this.gameState.selectedPiece.dataset.pieceId;
    const expectedId = e.target.dataset.expectedId;

    if (pieceId === expectedId) {
      this.placePiece(e.target, this.gameState.selectedPiece);
    } else {
      this.showWrongPlacement();
    }
  }

  // Drag and drop handlers
  dragStart(e) {
    if (!this.gameState.gameActive) return;
    this.gameState.currentTile = e.target;

    if (e.type === "touchstart") {
      const touch = e.touches[0];
      e.target.initialX = touch.clientX - e.target.getBoundingClientRect().left;
      e.target.initialY = touch.clientY - e.target.getBoundingClientRect().top;
    }
  }

  dragOver(e) {
    if (!this.gameState.gameActive) return;
    e.preventDefault();
  }

  dragEnter(e) {
    if (!this.gameState.gameActive) return;
    e.preventDefault();
  }

  dragLeave(e) {
    if (!this.gameState.gameActive) return;
  }

  dragDrop(e) {
    if (!this.gameState.gameActive) return;
    this.gameState.otherTile = e.target;
  }

  dragEnd(e) {
    if (!this.gameState.gameActive) return;

    if (!this.gameState.currentTile || !this.gameState.otherTile) return;

    const pieceId = this.gameState.currentTile.dataset.pieceId;
    const boardIndex = Array.from(
      document.getElementById("board").getElementsByTagName("img")
    ).indexOf(this.gameState.otherTile);

    if (boardIndex !== -1 && pieceId === this.correctOrder[boardIndex]) {
      this.placePiece(this.gameState.otherTile, this.gameState.currentTile);
    }

    // Reset
    this.gameState.currentTile = null;
    this.gameState.otherTile = null;
  }

  // Touch handlers for mobile
  touchStart(e) {
    this.dragStart(e);
  }

  touchMove(e) {
    if (!this.gameState.gameActive) return;
    e.preventDefault();

    const touch = e.touches[0];
    const offsetX = touch.clientX - e.target.initialX;
    const offsetY = touch.clientY - e.target.initialY;

    e.target.style.position = "absolute";
    e.target.style.left = `${offsetX}px`;
    e.target.style.top = `${offsetY}px`;
    e.target.style.zIndex = "1000";
  }

  touchEnd(e) {
    if (!this.gameState.gameActive) return;

    const rect = e.target.getBoundingClientRect();
    const targetTile = document.elementFromPoint(
      rect.left + rect.width / 2,
      rect.top + rect.height / 2
    );

    if (targetTile && targetTile.classList.contains("board-slot")) {
      this.gameState.otherTile = targetTile;
      this.dragEnd(e);
    }

    // Reset position
    e.target.style.position = "static";
    e.target.style.zIndex = "auto";
  }

  // Place piece correctly
  placePiece(slot, piece) {
    slot.classList.add("placed");
    slot.classList.remove("target-highlight");
    slot.style.filter = "none";
    slot.style.opacity = "1";

    piece.classList.add("hidden");
    piece.classList.remove("selected");
    piece.style.opacity = "0";

    this.gameState.placedPieces++;
    this.gameState.score += 10;
    this.updateScore();

    this.showStatusMessage("Correct! Well done!", "success");

    // Clear selection
    this.gameState.selectedPiece = null;

    // Check if puzzle is complete
    if (this.gameState.placedPieces === this.puzzlePieces.length) {
      this.completePuzzle();
    }
  }

  // Show wrong placement
  showWrongPlacement() {
    this.gameState.selectedPiece.classList.add("shake");
    this.showStatusMessage("Wrong slot! Try the highlighted one.", "error");

    setTimeout(() => {
      if (this.gameState.selectedPiece) {
        this.gameState.selectedPiece.classList.remove("shake");
      }
    }, 500);
  }

  // Complete puzzle
  async completePuzzle() {
    this.gameState.gameActive = false;
    clearInterval(this.gameState.timer);
    clearInterval(this.gameState.winnerCheckInterval);

    // Calculate bonus points
    const timeBonus = Math.floor(this.gameState.timeLeft / 10) * 5;
    this.gameState.score += timeBonus;
    this.updateScore();

    this.showStatusMessage(
      `Congratulations! Puzzle completed! Time bonus: +${timeBonus} points`,
      "success"
    );
    this.generateConfetti();

    // Save game data and check for winner
    if (this.config.playerId && this.config.roomId) {
      try {
        const correctPieces = this.countCorrectPieces();
        const timeTaken = this.config.timeLimit - this.gameState.timeLeft;

        await this.saveGameData(correctPieces, timeTaken);
        await this.handleWinnerLogic();
      } catch (error) {
        console.error("Error handling game completion:", error);
      }
    } else {
      // Single player mode
      setTimeout(() => {
        if (confirm("Congratulations! Would you like to play again?")) {
          this.startNewGame();
        }
      }, 3000);
    }
  }

  // Handle winner logic for multiplayer
  async handleWinnerLogic() {
    try {
      // Update user level
      const updateResponse = await fetch(
        `${this.config.apiBaseUrl}/updateLevel/${this.config.playerId}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": this.config.csrfToken,
          },
        }
      );

      const updateData = await updateResponse.json();
      console.log("Level update response:", updateData);

      // Update score display
      if (updateData.success) {
        const scoreDisplay = document.getElementById("score");
        if (scoreDisplay) {
          scoreDisplay.innerText = updateData.wins;
        }
      }

      // Show winner popup
      const popup = document.getElementById("popup");
      if (popup) {
        popup.style.display = "flex";
        this.generateConfetti();

        if (this.gameState.round < this.config.maxRounds) {
          this.startCountdown(10, "winnerCountdown", () => {
            this.nextRound();
          });
        } else {
          this.startCountdown(5, "winnerCountdown", () => {
            this.showFinishModal();
          });
        }
      }
    } catch (error) {
      console.error("Error handling winner logic:", error);
    }
  }

  // Start game with countdown
  startGameWithCountdown() {
    this.showCountdown(3, () => {
      this.gameState.timeLeft = this.config.timeLimit;
      this.gameState.gameActive = true;
      this.startTimer();

      if (this.config.roomId) {
        this.gameState.winnerCheckInterval = this.checkOtherPlayerWon();
      }
    });
  }

  // Show countdown overlay
  showCountdown(seconds, callback) {
    const countdownElement = document.createElement("div");
    countdownElement.id = "countdown-overlay";
    countdownElement.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            color: white;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            z-index: 1000;
        `;
    document.body.appendChild(countdownElement);

    const countdownInterval = setInterval(() => {
      countdownElement.textContent = seconds;
      seconds--;

      if (seconds < 0) {
        clearInterval(countdownInterval);
        document.body.removeChild(countdownElement);
        callback();
      }
    }, 1000);
  }

  // Timer functions
  startTimer() {
    clearInterval(this.gameState.timer);
    this.gameState.timer = setInterval(() => this.updateTimer(), 1000);
  }

  updateTimer() {
    const timerElement =
      document.getElementById("timer") || document.getElementById("time");
    if (!timerElement) return;

    const minutes = Math.floor(this.gameState.timeLeft / 60);
    const seconds = this.gameState.timeLeft % 60;
    timerElement.textContent = `${minutes
      .toString()
      .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

    if (this.gameState.timeLeft > 0) {
      this.gameState.timeLeft--;
    } else {
      this.gameTimeUp();
    }
  }

  // Handle time up
  async gameTimeUp() {
    this.gameState.gameActive = false;
    clearInterval(this.gameState.timer);
    clearInterval(this.gameState.winnerCheckInterval);

    this.showStatusMessage("Time's up! Game over!", "error");

    if (this.config.playerId && this.config.roomId) {
      try {
        const correctPieces = this.countCorrectPieces();
        const timeTaken = this.config.timeLimit;

        await this.saveGameData(correctPieces, timeTaken);

        // Show lose popup
        const losePopup = document.getElementById("losePopup");
        if (losePopup) {
          losePopup.style.display = "flex";
          this.generateSmokeAndBlood();

          if (this.gameState.round >= this.config.maxRounds) {
            this.startCountdown(5, "loserCountdown", () => {
              this.showFinishModal();
            });
          } else {
            this.startCountdown(10, "loserCountdown", () => {
              this.nextRound();
            });
          }
        }
      } catch (error) {
        console.error("Error handling time up:", error);
      }
    } else {
      // Single player mode
      setTimeout(() => {
        if (confirm("Time's up! Would you like to try again?")) {
          this.startNewGame();
        }
      }, 2000);
    }
  }

  // Utility functions
  countCorrectPieces() {
    const board = document.getElementById("board").getElementsByTagName("img");
    let correctCount = 0;

    for (let i = 0; i < board.length; i++) {
      const currentImg = board[i].src.split("/").pop().split(".")[0]; // Get filename without extension
      const expectedId = this.correctOrder[i];

      if (board[i].style.filter.includes("none") && currentImg === expectedId) {
        correctCount++;
      }
    }

    return correctCount;
  }

  // Show status message
  showStatusMessage(message, type = "info") {
    const statusEl = document.getElementById("statusMessage");
    if (!statusEl) return;

    statusEl.textContent = message;
    statusEl.className = `status-message show ${type}`;

    setTimeout(() => {
      statusEl.classList.remove("show");
    }, 3000);
  }

  // Update score display
  updateScore() {
    const scoreEl = document.getElementById("score");
    if (scoreEl) {
      scoreEl.textContent = this.gameState.score;
    }
  }

  // Initialize score display
  initializeScoreDisplay() {
    const scoreDisplay = document.getElementById("score");
    if (scoreDisplay && !scoreDisplay.innerText) {
      scoreDisplay.innerText = "0";
    }
  }

  // API calls
  async saveGameData(correctPieces, timeTaken) {
    try {
      const response = await fetch(`${this.config.apiBaseUrl}/saveGameResult`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": this.config.csrfToken,
        },
        body: JSON.stringify({
          player_id: this.config.playerId,
          room_id: this.config.roomId,
          correct_pieces: correctPieces,
          time_taken: timeTaken,
          round: this.gameState.round,
        }),
      });

      const data = await response.json();
      console.log("Game data saved:", data);
      return data;
    } catch (error) {
      console.error("Error saving game data:", error);
      throw error;
    }
  }

  // Check if other player won
  checkOtherPlayerWon() {
    return setInterval(async () => {
      if (!this.gameState.gameActive) return;

      try {
        const response = await fetch(
          `${this.config.apiBaseUrl}/getWinner/${this.config.roomId}/${this.gameState.round}`
        );

        if (!response.ok) throw new Error(`Server returned ${response.status}`);

        const data = await response.json();

        if (data.pemenang == 1 && data.id != this.config.playerId) {
          console.log("Another player won:", data.winner);

          // Stop game
          clearInterval(this.gameState.timer);
          clearInterval(this.gameState.winnerCheckInterval);
          this.gameState.gameActive = false;

          // Save current progress
          const correctPieces = this.countCorrectPieces();
          const timeTaken = this.config.timeLimit - this.gameState.timeLeft;

          await this.saveGameData(correctPieces, timeTaken);

          // Show lose popup
          const losePopup = document.getElementById("losePopup");
          if (losePopup) {
            losePopup.style.display = "flex";
            this.generateSmokeAndBlood();

            if (this.gameState.round >= this.config.maxRounds) {
              this.startCountdown(5, "loserCountdown", () => {
                this.showFinishModal();
              });
            } else {
              this.startCountdown(10, "loserCountdown", () => {
                this.nextRound();
              });
            }
          }
        }
      } catch (error) {
        console.error("Error checking if another player won:", error);
      }
    }, 2000);
  }

  // Start countdown with callback
  startCountdown(seconds, countdownId, callback) {
    const countdownElement = document.getElementById(countdownId);
    if (!countdownElement) {
      console.error(`Element with ID '${countdownId}' not found!`);
      return;
    }

    countdownElement.textContent = seconds;

    const countdownInterval = setInterval(() => {
      seconds--;
      countdownElement.textContent = seconds;

      if (seconds <= 0) {
        clearInterval(countdownInterval);
        this.closeModal();
        if (callback) callback();
      }
    }, 1000);
  }

  // Close modal
  closeModal() {
    const popup = document.getElementById("popup");
    const losePopup = document.getElementById("losePopup");

    if (popup) popup.style.display = "none";
    if (losePopup) losePopup.style.display = "none";
  }

  // Next round
  nextRound() {
    this.gameState.round++;
    const roundDisplay = document.getElementById("round");
    if (roundDisplay) {
      roundDisplay.innerText = this.gameState.round;
    }

    this.resetGame();
    this.startGameWithCountdown();
  }

  // Show finish modal
  async showFinishModal() {
    // Reset wins after game completion
    try {
      const resetResponse = await fetch(
        `${this.config.apiBaseUrl}/resetWins/${this.config.playerId}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": this.config.csrfToken,
          },
        }
      );

      const resetData = await resetResponse.json();
      console.log("Wins reset response:", resetData);

      const scoreDisplay = document.getElementById("score");
      if (scoreDisplay && resetData.success) {
        scoreDisplay.innerText = resetData.wins;
      }
    } catch (error) {
      console.error("Error resetting wins:", error);
    }

    const modal = document.getElementById("myModal");
    const btn = document.getElementById("btn-finish");

    if (modal) {
      modal.style.display = "flex";

      const currentScore = parseInt(
        document.getElementById("score")?.innerText || "0"
      );
      const isWinner = currentScore > 1;

      if (isWinner) {
        document.getElementById("description").innerText =
          "Silahkan Klaim Hadiah Anda";
        btn.innerText = "Klaim";

        const redirectWinner = () => {
          window.location.replace(
            `${this.config.apiBaseUrl}/showGalleries/${this.config.playerId}`
          );
        };

        setTimeout(redirectWinner, 10000);
        btn.onclick = redirectWinner;
      } else {
        document.getElementById("description").innerText =
          "Kembali Ke Halaman Utama";
        btn.innerText = "Kembali";

        const redirectLoser = () => {
          window.location.replace(`${this.config.apiBaseUrl}/room`);
        };

        setTimeout(redirectLoser, 10000);
        btn.onclick = redirectLoser;
      }
    }
  }

  // Reset game
  resetGame() {
    // Clear selections and highlights
    document.querySelectorAll(".puzzle-piece").forEach((piece) => {
      piece.classList.remove("hidden", "selected", "shake");
      piece.style.opacity = "1";
    });

    document.querySelectorAll(".board-slot").forEach((slot) => {
      slot.classList.remove("placed", "target-highlight");
      slot.style.filter = "grayscale(100%) opacity(0.5)";
    });

    // Reset game state
    this.gameState.selectedPiece = null;
    this.gameState.placedPieces = 0;
    this.gameState.gameActive = true;

    // Recreate pieces in random order
    this.createPieces();

    this.showStatusMessage("Game reset! Select a piece to start playing.");
  }

  // Start new game
  startNewGame() {
    clearInterval(this.gameState.timer);
    clearInterval(this.gameState.winnerCheckInterval);

    this.gameState.timeLeft = this.config.timeLimit;
    this.gameState.score = 0;
    this.gameState.placedPieces = 0;
    this.gameState.selectedPiece = null;
    this.gameState.gameActive = true;
    this.gameState.round = 1;

    this.updateScore();
    this.resetGame();
    this.startGameWithCountdown();

    const roundDisplay = document.getElementById("round");
    if (roundDisplay) {
      roundDisplay.innerText = this.gameState.round;
    }

    this.showStatusMessage("New game started! Good luck!");
  }

  // Generate confetti effect
  generateConfetti() {
    const container = document.getElementById("confetti-container");
    if (!container) return;

    // Create multiple confetti pieces
    for (let i = 0; i < 150; i++) {
      const confetti = document.createElement("div");
      confetti.classList.add("confetti-piece");

      // Randomize position and style
      confetti.style.left = Math.random() * 100 + "%";
      confetti.style.backgroundColor = this.getRandomColor();
      confetti.style.width = confetti.style.height =
        Math.random() * 8 + 4 + "px";
      confetti.style.animationDuration = Math.random() * 3 + 2 + "s";
      confetti.style.transform = `rotate(${Math.random() * 360}deg)`;

      // Add confetti to container
      container.appendChild(confetti);

      // Remove confetti after animation
      setTimeout(() => {
        confetti.remove();
      }, 5000);
    }
  }

  // Helper function for random colors
  getRandomColor() {
    const colors = [
      "#FFD700",
      "#7FFF00",
      "#8A2BE2",
      "#FF6347",
      "#00FA9A",
      "#00BFFF",
      "#FF69B4",
    ];
    return colors[Math.floor(Math.random() * colors.length)];
  }

  // Generate smoke and blood effect for losing condition
  generateSmokeAndBlood() {
    const container = document.getElementById("smoke-container");
    if (!container) return;

    // Create smoke particles
    for (let i = 0; i < 50; i++) {
      const smoke = document.createElement("div");
      smoke.classList.add("smoke-particle");

      smoke.style.left = Math.random() * 100 + "%";
      smoke.style.width = smoke.style.height = Math.random() * 20 + 10 + "px";
      smoke.style.animationDuration = Math.random() * 3 + 2 + "s";

      container.appendChild(smoke);

      setTimeout(() => {
        smoke.remove();
      }, 4000);
    }
  }
}

// Initialize game when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // Check if we're in a game room
  const roomId = document.getElementById("room-id")?.value;
  const playerId = document.getElementById("player-id")?.value;

  // Configure game instance
  const game = new PuzzleGame({
    rows: 2,
    columns: 2,
    timeLimit: 60,
    maxRounds: 3,
    playerId: playerId,
    roomId: roomId,
    useServerImages: true,
  });

  // Start game initialization
  game.init();

  // Expose game to window for debugging/testing
  window.game = game;
});
