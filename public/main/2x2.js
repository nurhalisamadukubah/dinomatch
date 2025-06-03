// Laravel Puzzle Game Implementation with Modal Logic Integration
class PuzzleGame {
    constructor(config = {}) {
        // Game configuration
        this.config = {
            rows: config.rows || 2,
            columns: config.columns || 2,
            timeLimit: config.timeLimit || 60, // seconds
            maxRounds: config.maxRounds || 3,
            // playerId: config.playerId || null,
            // roomId: config.roomId || null,
            apiBaseUrl: config.apiBaseUrl || "http://127.0.0.1:8000",
            csrfToken:
                config.csrfToken ||
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
            ...config,
        };

        // Combined game state
        this.gameState = {
            // Existing game state
            selectedPiece: null,
            placedPieces: 0,
            timeLeft: this.config.timeLimit,
            gameActive: false,
            score: 0,
            round: 1,
            isWin: false,
            timer: null,
            winnerCheckInterval: null,

            // New modal logic state
            currentRound: 1,
            wins: 0,
            losses: 0,
            gameHistory: [],
            activeModal: null,
            countdownInterval: null,
            matchWinnerCheckInterval: null,
        };

        // Puzzle configuration
        this.puzzlePieces = [
            {
                id: "A1",
                src: this.config.useServerImages
                    ? "/assets/images/2x2/A1.png"
                    : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzRFQ0RDNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QTE8L3RleHQ+PC9zdmc+",
            },
            {
                id: "A2",
                src: this.config.useServerImages
                    ? "/assets/images/2x2/A2.png"
                    : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI0ZGNkI2QiI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QTI8L3RleHQ+PC9zdmc+",
            },
            {
                id: "B1",
                src: this.config.useServerImages
                    ? "/assets/images/2x2/B1.png"
                    : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzQ1QjdEMSI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjE8L3RleHQ+PC9zdmc+",
            },
            {
                id: "B2",
                src: this.config.useServerImages
                    ? "/assets/images/2x2/B2.png"
                    : "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzk2Q0VCNCI+PC9yZWN0Pjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QjI8L3RleHQ+PC9zdmc+",
            },
        ];
        this.correctOrder = ["A1", "A2", "B1", "B2"];
    }

    // Initialize the game
    init() {
        this.createBoard();
        this.createPieces();
        this.initializeScoreDisplay();

        // Initialize new game logic
        this.initGameLogic();

        // Auto-start with countdown
        this.startGameWithCountdown();
    }

    // Initialize new game logic
    initGameLogic() {
        this.updateDisplay();
        this.bindEventListeners();
    }

    // Bind event listeners for modals
    bindEventListeners() {
        // Close modal on outside click
        window.addEventListener("click", (event) => {
            if (event.target.classList.contains("modal")) {
                this.closeModal();
            }
        });

        // Handle ESC key
        window.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && this.gameState.activeModal) {
                this.closeModal();
            }
        });
    }

    // Player wins current round
    playerWin() {
        if (this.gameState.currentRound > this.gameState.maxRounds) {
            alert(
                "Game is already finished! Please reset to start a new game."
            );
            return;
        }

        this.gameState.wins++;
        this.gameState.score +=1;
        this.gameState.gameHistory.push("win");
        this.handleRoundResult("win");
        this.handleWinnerLogic();
        
    }

    // Player loses current round
    playerLose() {
        if (this.gameState.currentRound > this.gameState.maxRounds) {
            alert(
                "Game is already finished! Please reset to start a new game."
            );
            return;
        }

        this.gameState.losses++;
        this.gameState.gameHistory.push("lose");
        this.handleRoundResult("lose");
    }

    handleRoundResult(result) {
        const round = this.gameState.currentRound;
        const history = this.gameState.gameHistory;

        console.log(`Round ${round} result: ${result}`, history);

        // FIXED: Ensure game is completely stopped
        this.gameState.gameActive = false;
        this.clearAllIntervals();

        // Round 1 logic
        if (round === 1) {
            if (result === "win") {
                this.showModal("winRound1Modal", "countdown1");
            } else {
                this.showModal("loseRound1Modal", "countdown2");
            }
        }
        // Round 2 logic
        else if (round === 2) {
            if (history[0] === "win" && result === "lose") {
                this.showModal("loseRound2Modal", "countdown4");
            } else if (history[0] === "win" && result === "win") {
                // Player wins 2 rounds in a row = wins match
                if (roomId && !this.gameState.matchWinnerCheckInterval) {
                    this.gameState.matchWinnerCheckInterval = this.checkMatchWinner();
                }
                this.showWinMatch();
                return;
            } else if (history[0] === "lose" && result === "win") {
                this.showModal("winRound2Modal", "countdown3");
            } else if (history[0] === "lose" && result === "lose") {
                // Player loses 2 rounds in a row = loses match
                if (roomId && !this.gameState.matchWinnerCheckInterval) {
                    this.gameState.matchWinnerCheckInterval = this.checkMatchWinner();
                }
                this.showLoseMatch();
                return;
            }
        }
        // Round 3 logic
        else if (round === 3) {
            // Final round, determine winner based on total wins
            const totalWins = this.gameState.wins;

            // Start match winner check
            if (roomId && !this.gameState.matchWinnerCheckInterval) {
                this.gameState.matchWinnerCheckInterval = this.checkMatchWinner();
            }

            if (totalWins >= 2) {
                this.showWinMatch();
            } else {
                this.showLoseMatch();
            }
            return;
        }

        // Move to next round only if not at final round
        if (round < this.config.maxRounds) {
            this.gameState.currentRound++;
            this.updateDisplay();
        }
    }

    // Show win match modal
    showWinMatch() {
        this.showModal("winMatchModal");
        this.gameState.currentRound = this.gameState.maxRounds + 1; // Mark game as finished
        this.updateDisplay();
    }

    // Show lose match modal
    showLoseMatch() {
        this.showModal("loseMatchModal");
        this.gameState.currentRound = this.gameState.maxRounds + 1; // Mark game as finished
        this.updateDisplay();
    }

    // Show modal with countdown
    showModal(modalId, countdownId = null) {
        console.log(`Showing modal: ${modalId}, countdown: ${countdownId}`); // Debug log

        this.closeModal();

        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "flex";
            this.gameState.activeModal = modalId;

            // Start countdown if specified
            if (countdownId) {
                this.startCountdown(countdownId, 5);
            }
        } else {
            console.error(`Modal with ID '${modalId}' not found!`);
        }
    }

    // Close current modal
    closeModal() {
        if (this.gameState.activeModal) {
            const modal = document.getElementById(this.gameState.activeModal);
            if (modal) {
                modal.style.display = "none";
            }
            this.gameState.activeModal = null;
        }

        // Clear any active countdown
        if (this.gameState.countdownInterval) {
            clearInterval(this.gameState.countdownInterval);
            this.gameState.countdownInterval = null;
        }
    }

    // Start countdown timer
    startCountdown(countdownId, seconds) {
        console.log(
            `Starting countdown: ${countdownId} for ${seconds} seconds`
        );

        const countdownElement = document.getElementById(countdownId);
        if (!countdownElement) {
            console.error(`Countdown element '${countdownId}' not found!`);
            // Fallback: tutup modal setelah delay dan lanjut ke round berikutnya
            setTimeout(() => {
                this.closeModal();
                this.proceedToNextRound();
            }, seconds * 1000);
            return;
        }

        let timeLeft = seconds;
        countdownElement.textContent = timeLeft;

        // Clear existing countdown if any
        if (this.gameState.countdownInterval) {
            clearInterval(this.gameState.countdownInterval);
        }

        this.gameState.countdownInterval = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(this.gameState.countdownInterval);
                this.gameState.countdownInterval = null;
                this.closeModal();
                this.proceedToNextRound();
            }
        }, 1000);
    }

    // Claim reward (for winner modal)
    claimReward() {
        alert("🎉 Congratulations! You have claimed your reward! 🎉");
        this.closeModal();
        // You can add more reward logic here
    }

    // Reset game to initial state
    resetGame() {
        // Clear all intervals
        if (this.gameState.timer) {
            clearInterval(this.gameState.timer);
            this.gameState.timer = null;
        }
        if (this.gameState.winnerCheckInterval) {
            clearInterval(this.gameState.winnerCheckInterval);
            this.gameState.winnerCheckInterval = null;
        }
        if (this.gameState.matchWinnerCheckInterval) {
            clearInterval(this.gameState.matchWinnerCheckInterval);
            this.gameState.matchWinnerCheckInterval = null;
        }
        if (this.gameState.countdownInterval) {
            clearInterval(this.gameState.countdownInterval);
            this.gameState.countdownInterval = null;
        }

        // Reset new game logic state
        this.gameState.currentRound = 1;
        this.gameState.wins = 0;
        this.gameState.losses = 0;
        this.gameState.gameHistory = [];

        // Clear selections and highlights
        document.querySelectorAll(".puzzle-piece").forEach((piece) => {
            piece.classList.remove("hidden", "selected", "shake");
            piece.style.opacity = "1";
        });
        document.querySelectorAll(".board-slot").forEach((slot) => {
            slot.classList.remove("placed", "target-highlight");
            slot.style.filter = "grayscale(100%) opacity(0.5)";
        });

        // Reset existing game state
        this.gameState.selectedPiece = null;
        this.gameState.placedPieces = 0;
        this.gameState.gameActive = true;
        this.gameState.timeLeft = this.config.timeLimit;
        this.gameState.score = 0;

        // Recreate pieces in random order
        this.createPieces();
        this.updateScore();
        this.updateDisplay();
    }

    // Update display elements
    updateDisplay() {
        const currentRoundElement = document.getElementById("round");
        const winCountElement = document.getElementById("score");
        const lossCountElement = document.getElementById("lossCount");

        if (currentRoundElement) {
            if (this.gameState.currentRound > this.gameState.maxRounds) {
                currentRoundElement.textContent = this.gameState.currentRound;
            } else {
                currentRoundElement.textContent = this.gameState.currentRound;
            }
        }

        if (winCountElement) {
            winCountElement.textContent = this.gameState.wins;
        }

        if (lossCountElement) {
            lossCountElement.textContent = this.gameState.losses;
        }
    }

    proceedToNextRound() {
        console.log(
            `Proceeding to next round. Current: ${this.gameState.currentRound}, Max: ${this.config.maxRounds}`
        );

        // Clear all intervals before proceeding
        this.clearAllIntervals();

        // Check if there are more rounds
        if (this.gameState.currentRound <= this.config.maxRounds) {
            // Reset puzzle for next round
            this.resetPuzzleForNextRound();
            // Start game with countdown
            this.startGameWithCountdown();
        }
    }

    resetPuzzleForNextRound() {
        // Clear all intervals
        this.clearAllIntervals();

        // Clear visual elements
        document.querySelectorAll(".puzzle-piece").forEach((piece) => {
            piece.classList.remove("hidden", "selected", "shake");
            piece.style.opacity = "1";
        });
        document.querySelectorAll(".board-slot").forEach((slot) => {
            slot.classList.remove("placed", "target-highlight");
            slot.style.filter = "grayscale(100%) opacity(0.5)";
        });

        // Reset puzzle state (don't reset round/wins/losses)
        this.gameState.selectedPiece = null;
        this.gameState.placedPieces = 0;
        this.gameState.gameActive = false; // Will be set true when countdown finishes
        this.gameState.timeLeft = this.config.timeLimit;

        // Recreate pieces in random order
        this.createPieces();
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

        // Konfigurasi Salsa20
        const key = Array.from({ length: 16 }, (_, i) => i + 1); // 16-byte key
        const nonce = Array.from({ length: 8 }, (_, i) => i + 1 + 100); // 8-byte nonce
        const counter = Array.from({ length: 8 }, (_, i) => i + 1 + 100 + 8); // 8-byte counter
        const sigma = [0x61707865, 0x3120646e, 0x79622d36, 0x6b206574]; // magic constant

        const salsa20 = new Salsa20(key, nonce, counter, sigma);
        const hexOutputArray = salsa20.getHexStringArray(64); // Generate 64 hex values

        // Daftar semua potongan puzzle
        const shuffledPieces = [];

        // Salin daftar asli
        const availablePieces = [...this.puzzlePieces];

        // Gunakan output Salsa20 untuk memilih potongan secara acak
        while (availablePieces.length > 0) {
            const hexValue = parseInt(hexOutputArray.shift(), 16); // Ambil hex value
            const index = hexValue % availablePieces.length; // Dapatkan indeks acak
            shuffledPieces.push(availablePieces.splice(index, 1)[0]);

            // Reset hexOutputArray jika habis
            if (hexOutputArray.length === 0) {
                hexOutputArray.push(...salsa20.getHexStringArray(64)); // Regenerate
            }
        }

        // Tambahkan potongan ke DOM
        shuffledPieces.forEach((piece) => {
            const img = document.createElement("img");
            img.src = piece.src;
            img.dataset.pieceId = piece.id;
            img.classList.add("puzzle-piece");
            img.draggable = true;

            // Event listeners
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
            e.target.initialX =
                touch.clientX - e.target.getBoundingClientRect().left;
            e.target.initialY =
                touch.clientY - e.target.getBoundingClientRect().top;
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
            this.placePiece(
                this.gameState.otherTile,
                this.gameState.currentTile
            );
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
        // this.gameState.score += 10;
        this.updateScore();
        this.gameState.selectedPiece = null;
        // Check if puzzle is complete
        if (this.gameState.placedPieces === this.puzzlePieces.length) {
            this.completePuzzle();
        }
    }

    // Show wrong placement
    showWrongPlacement() {
        this.gameState.selectedPiece.classList.add("shake");
        setTimeout(() => {
            if (this.gameState.selectedPiece) {
                this.gameState.selectedPiece.classList.remove("shake");
            }
        }, 500);
    }

    // Complete puzzle
    async completePuzzle() {
        // FIXED: Immediately set game as inactive to prevent any further actions
        this.gameState.gameActive = false;
        
        // Clear all intervals immediately to stop other checks
        this.clearAllIntervals();

        // Calculate bonus points
        const timeBonus = Math.floor(this.gameState.timeLeft / 10) * 5;
        // this.gameState.score += timeBonus;
        this.updateScore();

        const correctPieces = this.countCorrectPieces();
        const timeTaken = this.config.timeLimit - this.gameState.timeLeft;

        // Save game data first
        try {
            await this.saveGameData(correctPieces, timeTaken);
            console.log("Game data saved for winning player");
        } catch (error) {
            console.error("Error saving game data:", error);
        }

        // Trigger win with shorter delay
        setTimeout(() => {
            this.playerWin();
        }, 1000);
    }

    // Handle winner logic for multiplayer
    async handleWinnerLogic() {
        try {
            // Update user level
            const updateResponse = await fetch(
                `${this.config.apiBaseUrl}/updateLevel/${playerId}`,
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
            // if (updateData.success) {
            //     const scoreDisplay = document.getElementById("score");
            //     if (scoreDisplay) {
            //         scoreDisplay.innerText = updateData.wins;
            //     }
            // }
        } catch (error) {
            console.error("Error handling winner logic:", error);
        }
    }

    startCountdownForNextRound(seconds, countdownId, callback) {
        const countdownElement = document.getElementById(countdownId);
        if (!countdownElement) {
            console.error(`Element with ID '${countdownId}' not found!`);
            // Fallback: langsung jalankan callback
            if (callback) {
                setTimeout(callback, seconds * 1000);
            }
            return;
        }

        countdownElement.textContent = seconds;
        const countdownInterval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                if (callback) callback();
            }
        }, 1000);
    }

    // Start game with countdown
    startGameWithCountdown() {
        this.clearAllIntervals(); // Bersihkan interval lama
        this.showCountdown(3, () => {
            this.gameState.timeLeft = this.config.timeLimit;
            this.gameState.gameActive = true;
            this.startTimer();
            console.log(`Starting winner check for round ${this.gameState.currentRound}`);
            this.gameState.winnerCheckInterval = this.checkOtherPlayerWon(); // Interval baru
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
        // FIXED: Immediately set game as inactive
        this.gameState.gameActive = false;
        
        // Clear all intervals immediately
        this.clearAllIntervals();

        // Save current progress
        const correctPieces = this.countCorrectPieces();
        const timeTaken = this.config.timeLimit;

        try {
            await this.saveGameData(correctPieces, timeTaken);
            console.log("Game data saved for time up");
        } catch (error) {
            console.error("Error saving game data for time up:", error);
        }

        // Trigger lose with shorter delay
        setTimeout(() => {
            this.playerLose();
        }, 1000);
    }

    clearAllIntervals() {
        console.log("Clearing all intervals...");
        
        if (this.gameState.timer) {
            clearInterval(this.gameState.timer);
            this.gameState.timer = null;
            console.log("Timer cleared");
        }
        if (this.gameState.winnerCheckInterval) {
            clearInterval(this.gameState.winnerCheckInterval);
            this.gameState.winnerCheckInterval = null;
            console.log("Winner check interval cleared");
        }
        if (this.gameState.matchWinnerCheckInterval) {
            clearInterval(this.gameState.matchWinnerCheckInterval);
            this.gameState.matchWinnerCheckInterval = null;
            console.log("Match winner check interval cleared");
        }
        if (this.gameState.countdownInterval) {
            clearInterval(this.gameState.countdownInterval);
            this.gameState.countdownInterval = null;
            console.log("Countdown interval cleared");
        }
    }

    // Utility functions
    countCorrectPieces() {
        const board = document
            .getElementById("board")
            .getElementsByTagName("img");
        let correctCount = 0;
        for (let i = 0; i < board.length; i++) {
            const currentImg = board[i].src.split("/").pop().split(".")[0]; // Get filename without extension
            const expectedId = this.correctOrder[i];
            if (
                board[i].style.filter.includes("none") &&
                currentImg === expectedId
            ) {
                correctCount++;
            }
        }
        return correctCount;
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
            const response = await fetch(
                `${this.config.apiBaseUrl}/saveGameResult`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.config.csrfToken,
                    },
                    body: JSON.stringify({
                        user_id: playerId,
                        room_id: roomId,
                        correct_pieces: correctPieces,
                        time_taken: timeTaken,
                        round: this.gameState.currentRound,
                    }),
                }
            );

            // Jika respons bukan JSON, lemparkan error
            if (
                !response.headers
                    .get("content-type")
                    ?.includes("application/json")
            ) {
                throw new Error("Server returned non-JSON response");
            }

            const data = await response.json();
            console.log("Game data saved:", data);
            return data;
        } catch (error) {
            console.error("Error saving game data:", error);
            throw error;
        }
    }

    async saveMatchResult(isWinner, matchType = "win") {
        try {
            const response = await fetch(
                `${this.config.apiBaseUrl}/saveMatchResult`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.config.csrfToken,
                    },
                    body: JSON.stringify({
                        user_id: playerId,
                        room_id: roomId,
                        is_winner: isWinner,
                        match_type: matchType,
                        round: this.gameState.currentRound,
                        total_wins: this.gameState.wins,
                        total_losses: this.gameState.losses,
                    }),
                }
            );

            if (
                !response.headers
                    .get("content-type")
                    ?.includes("application/json")
            ) {
                throw new Error("Server returned non-JSON response");
            }

            const data = await response.json();
            console.log("Match result saved:", data);
            return data;
        } catch (error) {
            console.error("Error saving match result:", error);
            // Jangan tampilkan error message, biarkan game tetap berjalan
            return null;
        }
    }

    checkMatchWinner() {
        return setInterval(async () => {
            if (this.gameState.currentRound > this.config.maxRounds) {
                // Game sudah selesai, hentikan pengecekan
                if (this.gameState.matchWinnerCheckInterval) {
                    clearInterval(this.gameState.matchWinnerCheckInterval);
                    this.gameState.matchWinnerCheckInterval = null;
                }
                return;
            }

            try {
                const response = await fetch(
                    `${this.config.apiBaseUrl}/getMatchWinner/${roomId}`
                );
                if (!response.ok)
                    throw new Error(`Server returned ${response.status}`);

                const data = await response.json();
                console.log("Match winner check:", data); // Debug log

                // Jika match sudah selesai dan ada pemenang yang bukan player ini
                if (
                    data.match_finished &&
                    data.winner_id &&
                    data.winner_id != playerId
                ) {
                    console.log(
                        "Match finished, winner:",
                        data.winner_id,
                        "current player:",
                        playerId
                    );

                    // Stop semua game activity
                    this.gameState.gameActive = false;
                    clearInterval(this.gameState.timer);
                    clearInterval(this.gameState.winnerCheckInterval);
                    clearInterval(this.gameState.matchWinnerCheckInterval);
                    this.gameState.matchWinnerCheckInterval = null;

                    // Save current progress sebagai kalah
                    const correctPieces = this.countCorrectPieces();
                    const timeTaken = this.config.timeLimit - this.gameState.timeLeft;
                    await this.saveGameData(correctPieces, timeTaken);

                    // Update game history jika belum ada
                    if (
                        this.gameState.gameHistory.length <
                        this.gameState.currentRound
                    ) {
                        this.gameState.losses++;
                        this.gameState.gameHistory.push("lose");
                    }
                    setTimeout(() => {
                        this.showLoseMatch();
                    }, 1500);
                }
            } catch (error) {
                console.error("Error checking match winner:", error);
                // Jangan hentikan interval, coba lagi nanti
            }
        }, 2000); // Check setiap 2 detik
    }

    // Check if other player won
    checkOtherPlayerWon() {
        return setInterval(async () => {
            // Skip if game is not active or round is finished
            if (!this.gameState.gameActive) {
                console.log("Game not active, skipping winner check");
                return;
            }

            // Skip if we've already processed this round
            if (this.gameState.currentRound > this.config.maxRounds) {
                console.log("Game finished, clearing winner check interval");
                if (this.gameState.winnerCheckInterval) {
                    clearInterval(this.gameState.winnerCheckInterval);
                    this.gameState.winnerCheckInterval = null;
                }
                return;
            }

            try {
                const response = await fetch(`${this.config.apiBaseUrl}/getWinner/${roomId}/${this.gameState.currentRound}`);
                if (!response.ok) throw new Error(`Server returned ${response.status}`);
                
                const data = await response.json();
                
                console.log(`Checking winner for round ${this.gameState.currentRound}:`, data);
                
                // FIXED: More specific checking - ensure we only react once per round
                if (data.pemenang == 1 && data.id != playerId && this.gameState.gameActive) {
                    console.log(`Player ${data.winner} won round ${this.gameState.currentRound}, current player ${playerId} loses`);
                    
                    // FIXED: Immediately stop the game to prevent continued play
                    this.gameState.gameActive = false;
                    
                    // Clear all intervals immediately
                    this.clearAllIntervals();

                    // Save current progress as loss
                    const correctPieces = this.countCorrectPieces();
                    const timeTaken = this.config.timeLimit - this.gameState.timeLeft;
                    
                    try {
                        await this.saveGameData(correctPieces, timeTaken);
                        console.log("Game data saved for losing player");
                    } catch (error) {
                        console.error("Error saving game data for losing player:", error);
                    }
                    setTimeout(() => {
                        this.playerLose();
                    }, 1000);
                }
            } catch (error) {
                console.error("Error checking if another player won:", error);
                // Don't stop the interval on error, just continue checking
            }
        }, 1500); // Check every 1.5 seconds for more responsive detection
    }

    // Next round
    nextRound() {
        // this.gameState.round++;
        const roundDisplay = document.getElementById("round");
        if (roundDisplay) {
            roundDisplay.innerText = this.gameState.currentRound;
        }
        this.resetPuzzleForNextRound();
        this.startGameWithCountdown();
    }

    // Show finish modal
    async showFinishModal() {
        // Reset wins after game completion
        try {
            const resetResponse = await fetch(
                `${this.config.apiBaseUrl}/resetWins/${playerId}`,
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
                        `${this.config.apiBaseUrl}/showGalleries/${playerId}`
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
    window.gameLogic = {
        playerWin: () => game.playerWin(),
        playerLose: () => game.playerLose(),
        showModal: (modalId, countdownId) =>
            game.showModal(modalId, countdownId),
        closeModal: () => game.closeModal(),
        claimReward: () => game.claimReward(),
        resetGame: () => game.resetGame(),
    };
});
