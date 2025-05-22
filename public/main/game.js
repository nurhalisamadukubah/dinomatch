var rows = 4;
var columns = 4;
var currTile;
var otherTile;
var round = 1;
var isWin = false;
var maxRounds = 3;
var gameActive = false; // check if the game is active
var timer;
var timeRemaining; // 3 minutes in seconds
var winnerCheckInterval;
var correctOrder = [
    "A1.png",
    "A2.png",
    "A3.png",
    "A4.png",
    "B1.png",
    "B2.png",
    "B3.png",
    "B4.png",
    "C1.png",
    "C2.png",
    "C3.png",
    "C4.png",
    "D1.png",
    "D2.png",
    "D3.png",
    "D4.png"
];

initGame();

// timer
document.addEventListener("DOMContentLoaded", function () {
    startTimer(); // Mulai timer secara otomatis
});
function startTimer() {
    clearInterval(timer);
    clearInterval(winnerCheckInterval); // Clear any existing winner check interval
    resetBoard(); // Reset the board to initial state
    gameActive = false; // Set game as inactive selama countdown

    // Tampilkan countdown sebelum mulai
    showCountdown(3, () => {
        timeRemaining = 60; // Reset the time to 60 detik
        gameActive = true; // Set game as active
        timer = setInterval(updateTimer, 1000); // Mulai timer permainan
        winnerCheckInterval = checkOtherPlayerWon(); // Start checking if other players won
    });
}

function showCountdown(seconds, callback) {
    let countdownElement = document.createElement("div");
    countdownElement.id = "countdown-overlay";
    countdownElement.style.position = "fixed";
    countdownElement.style.top = "50%";
    countdownElement.style.left = "50%";
    countdownElement.style.transform = "translate(-50%, -50%)";
    countdownElement.style.fontSize = "48px";
    countdownElement.style.color = "white";
    countdownElement.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
    countdownElement.style.padding = "20px";
    countdownElement.style.borderRadius = "10px";
    countdownElement.style.zIndex = "1000";
    document.body.appendChild(countdownElement);

    let countdownInterval = setInterval(() => {
        countdownElement.textContent = seconds;
        seconds--;

        if (seconds < 0) {
            clearInterval(countdownInterval);
            document.body.removeChild(countdownElement); // Hapus countdown
            callback(); // Panggil callback setelah countdown selesai
        }
    }, 1000);
}

// Perbaikan untuk updateTimer function
function updateTimer() {
    var minutes = Math.floor(timeRemaining / 60);
    var seconds = timeRemaining % 60;

    if (seconds < 10) {
        seconds = "0" + seconds;
    }

    document.getElementById("time").textContent = `${minutes}:${seconds}`;

    if (timeRemaining == 0) {
        clearInterval(timer);
        clearInterval(winnerCheckInterval); // Stop checking for winners
        gameActive = false;
        
        const correctPieces = countCorrectPieces();
        const timeTaken = 60 - timeRemaining;

        // Save game data but don't check for a winner - both players have lost due to timeout
        sendGameData(playerId, correctPieces, timeTaken, round, roomId)
            .then(async () => {
                // Display loss popup directly without checking for a winner
                document.getElementById("losePopup").style.display = "flex";
                // document.getElementById("loserDisplay").innerText = "Waktu habis! Tidak ada pemenang.";
                generateSmokeAndBlood();

                if (round >= maxRounds) {
                    // For round 3, show loser popup with countdown, then show finishModal
                    const finishCountdown = document.getElementById("loserCountdown");
                    let seconds = 5; // Show lose popup for 5 seconds before showing finishModal
                    
                    finishCountdown.textContent = seconds;
                    
                    const countdownInterval = setInterval(() => {
                        seconds--;
                        finishCountdown.textContent = seconds;
                        
                        if (seconds <= 0) {
                            clearInterval(countdownInterval);
                            closeModal(); // Close the lose popup
                            finishModal(roomId, round, playerId); // Show the finish modal
                        }
                    }, 1000);
                } else {
                    // For rounds 1 and 2, just show the loser popup with countdown
                    round++;
                    document.getElementById("round").innerText = round;
                    startCountdown(10, "loserCountdown");
                }
            })
            .catch((error) => {
                console.error("Error saving game data:", error);
            });

        return;
    }

    timeRemaining--;
}

// Fungsi helper untuk menghitung potongan yang benar
function countCorrectPieces() {
    const board = document.getElementById("board").getElementsByTagName("img");
    let correctCount = 0;

    for (let i = 0; i < board.length; i++) {
        const currentImg = board[i].src.split("/").pop(); // Ambil nama file saja
        if (currentImg === correctOrder[i]) {
            correctCount++;
        }
    }

    return correctCount;
}

function dragStart(e) {
    if (!gameActive) return;
    currTile = this;

    if (e.type === "touchstart") {
        const touch = e.touches[0];
        this.initialX = touch.clientX - this.getBoundingClientRect().left;
        this.initialY = touch.clientY - this.getBoundingClientRect().top;
    }
}

function dragOver(e) {
    if (!gameActive) return;
    e.preventDefault();
}

function dragEnter(e) {
    if (!gameActive) return;
    e.preventDefault();
}

function dragLeave() {
    if (!gameActive) return;
}

function dragDrop() {
    if (!gameActive) return;
    otherTile = this;
}

function dragEnd() {
    if (!gameActive) return;

    if (!currTile || !otherTile) {
        console.log("Tile tidak terdefinisi");
        return;
    }

    if (currTile.src && currTile.style.filter.includes('none')) {
        return;
    }

    let currImg = currTile.src;
    let otherImg = otherTile.src;
    
    // Cek apakah piece diletakkan di posisi yang benar
    const boardIndex = Array.from(document.getElementById("board").getElementsByTagName("img")).indexOf(otherTile);
    const currentPieceImg = currImg.split("/").pop();
    
    if (boardIndex !== -1 && currentPieceImg === correctOrder[boardIndex]) {
        // Jika posisi benar
        otherTile.src = currImg;
        otherTile.style.filter = 'none';
        otherTile.style.opacity = '1';
        
        if (currTile.parentElement.id === 'pieces') {
            currTile.style.opacity = '0%';
        }
    } else {
        // Jika posisi salah, kembalikan piece
        if (currTile.parentElement.id === 'pieces') {
            // Kembalikan ke posisi awal
            otherTile.src = otherImg;
            currTile.style.display = 'block';
        }
    }

    // Reset tile
    currTile = null;
    otherTile = null;

    checkBoard();
}

// Fungsi untuk meng-handle touch move (mobile)
function touchMove(e) {
    if (!gameActive) return;
    e.preventDefault();

    const touch = e.touches[0];
    const offsetX = touch.clientX - this.initialX;
    const offsetY = touch.clientY - this.initialY;

    // Pindahkan tile sesuai dengan posisi touch
    this.style.position = "absolute";
    this.style.left = `${offsetX}px`;
    this.style.top = `${offsetY}px`;
}

// Fungsi untuk meng-handle touch end (mobile)
function touchEnd() {
    if (!gameActive) return;

    // Cari tile target berdasarkan posisi akhir touch
    const rect = this.getBoundingClientRect();
    const targetTile = document.elementFromPoint(
        rect.left + rect.width / 2,
        rect.top + rect.height / 2
    );

    if (targetTile && targetTile.classList.contains("board")) {
        otherTile = targetTile; // Simpan tile target
        dragEnd(); // Panggil fungsi dragEnd untuk menukar gambar
    }

    // Reset posisi tile
    this.style.position = "static";
}

function initGame() {
    createBoard();
    createPieces();
}

function createBoard() {
    const board = document.getElementById("board");
    board.innerHTML = ""; // Bersihkan board sebelum menambahkan gambar

    // Daftar gambar yang akan ditampilkan
    const images = [
        "/assets/images/A1.png", "/assets/images/A2.png", "/assets/images/A3.png", "/assets/images/A4.png",
        "/assets/images/B1.png", "/assets/images/B2.png", "/assets/images/B3.png", "/assets/images/B4.png",
        "/assets/images/C1.png", "/assets/images/C2.png", "/assets/images/C3.png", "/assets/images/C4.png",
        "/assets/images/D1.png", "/assets/images/D2.png", "/assets/images/D3.png", "/assets/images/D4.png"
    ];

    images.forEach((imgSrc) => {
        let tile = document.createElement("img");
        tile.src = imgSrc;
        tile.alt = imgSrc.split(".")[0]; // Nama gambar tanpa ekstensi
        
        setListener(tile);

        board.appendChild(tile);
    });
}

function createPieces() {
    const pieces = document.getElementById("pieces");
    pieces.innerHTML = ""; // Bersihkan pieces
    var key = [];
    for (i = 0; i < 16; i++) key[i] = i + 1;
    for (i = 16; i < 32; i++) key[i] = i - 16 + 1;
    var nonce = [];
    for (i = 0; i < 8; i++) nonce[i] = i + 1 + 100;
    var counter = [];
    for (i = 0; i < 8; i++) counter[i] = i + 1 + 100 + 8;

    var sigma = [0x61707865, 0x3120646e, 0x79622d36, 0x6b206574];

    var salsa20 = new Salsa20(key, nonce, counter, sigma);
    var hexOutputArray = salsa20.getHexStringArray(64);
    var imgArr = [
        "/assets/images/A1.png",
        "/assets/images/A2.png",
        "/assets/images/A3.png",
        "/assets/images/A4.png",
        "/assets/images/B1.png",
        "/assets/images/B2.png",
        "/assets/images/B3.png",
        "/assets/images/B4.png",
        "/assets/images/C1.png",
        "/assets/images/C2.png",
        "/assets/images/C3.png",
        "/assets/images/C4.png",
        "/assets/images/D1.png",
        "/assets/images/D2.png",
        "/assets/images/D3.png",
        "/assets/images/D4.png",
    ];

    let pieceCounter = 0;

    for (i = 0; i < hexOutputArray.length; i++) {
        let imgInd = parseInt(hexOutputArray[i], 16) % 16;
        if (imgArr[imgInd] !== false) {
            // Buat elemen img langsung
            let imgElement = document.createElement("img");
            imgElement.src = imgArr[imgInd];
            setListener(imgElement);

            // Tambahkan langsung ke container
            pieces.appendChild(imgElement);

            imgArr[imgInd] = false;
            pieceCounter++;
        }
        if (i == 15) break;
    }

    imgArr.forEach((img) => {
        if (img) {
            // Buat elemen img langsung
            let imgElement = document.createElement("img");
            imgElement.src = img;
            setListener(imgElement);

            // Tambahkan langsung ke container
            pieces.appendChild(imgElement);

            pieceCounter++;
        }
    });
}

function setListener(elem) {
    elem.addEventListener("dragstart", dragStart); // click on image to drag
    elem.addEventListener("dragover", dragOver); // drag an image
    elem.addEventListener("dragenter", dragEnter); // dragging an image into another one
    elem.addEventListener("dragleave", dragLeave); // dragging an image away from another one
    elem.addEventListener("drop", dragDrop); // drop an image onto another one
    elem.addEventListener("dragend", dragEnd); // after you completed dragDrop

    // Event listener untuk mobile
    elem.addEventListener("touchstart", dragStart, { passive: false });
    elem.addEventListener("touchmove", touchMove, { passive: false });
    elem.addEventListener("touchend", touchEnd);
}

function resetBoard() {
    createBoard(); // Clear and reinitialize the board
    createPieces(); // Clear and reinitialize the pieces
}

// Modify sendGameData to clear the winner check interval when the game ends
async function sendGameData(playerId, correctPieces, timeTaken, round, roomId) {
    try {
        console.log("Sending game data:", { player_id: playerId, room_id: roomId, correct_pieces: correctPieces, time_taken: timeTaken, round: round });
        
        const response = await fetch("http://127.0.0.1:8000/saveGameResult", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                player_id: playerId,
                room_id: roomId,
                correct_pieces: correctPieces,
                time_taken: timeTaken,
                round: round,
            }),
        });

        if (!response.ok) {
            throw new Error(`Server returned ${response.status}`);
        }

        const data = await response.json();
        console.log("Game data saved:", data);
        return data;
    } catch (error) {
        console.error("Error saving game data:", error);
        throw error;
    }
}

async function checkWinner(roomId, round, playerId) {
    try {
        // Add timestamp to prevent caching
        const timestamp = new Date().getTime();
        const response = await fetch(`http://127.0.0.1:8000/getWinner/${roomId}/${round}?t=${timestamp}`);
        
        if (!response.ok) {
            throw new Error(`Server returned ${response.status}`);
        }
        
        const data = await response.json();
        console.log("Winner check response:", data);

        if (data.message === "Menunggu pemain lain") {
            console.log("Still waiting for other players...");
            return null;
        }

        const scoreDisplay = document.getElementById("score");
        const initialScore = scoreDisplay ? parseInt(scoreDisplay.innerText || "0") : 0;

        if (data.winner) {
            console.log("Winner found:", data.winner, "ID:", data.id);
            
            // If this player is the winner, update the user level
            if (data.id == playerId) {
                console.log("Current player is the winner");
                
                // Call API to update user level
                try {
                    const updateResponse = await fetch(`http://127.0.0.1:8000/updateLevel/${playerId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const updateData = await updateResponse.json();
                    console.log("Level update response:", updateData);
                    
                    // Update score display with data from server
                    if (scoreDisplay && updateData.success) {
                        scoreDisplay.innerText = updateData.wins;
                    }
                    
                    return { 
                        isWinner: true, 
                        totalWin: updateData.success ? updateData.wins : initialScore + 1,
                        level: updateData.success ? updateData.level : null
                    };
                } catch (updateError) {
                    console.error("Error updating level:", updateError);
                    // Fallback to client-side score increment if API fails
                    if (scoreDisplay) {
                        scoreDisplay.innerText = initialScore + 1;
                    }
                    return { isWinner: true, totalWin: initialScore + 1 };
                }
            } else {
                console.log("Current player is not the winner");
                if (scoreDisplay) {
                    scoreDisplay.innerText = initialScore;
                }
                return { isWinner: false, totalWin: initialScore };
            }
        }

        return { isWinner: false, totalWin: initialScore };
    } catch (error) {
        console.error("Error checking winner:", error);
        throw error;
    }
}

async function showNextRoundPopup(roomId, round, playerId) {
    try {
        const correctPieces = countCorrectPieces();
        const timeTaken = 25 - timeRemaining;

        await sendGameData(playerId, correctPieces, timeTaken, round, roomId);

        let winnerResult = null;
        let attempts = 0;
        const maxAttempts = 5;

        while (attempts < maxAttempts) {
            winnerResult = await checkWinner(roomId, round, playerId);
            if (winnerResult !== null) break;

            await new Promise((resolve) => setTimeout(resolve, 1000));
            attempts++;
        }

        if (!winnerResult) {
            console.error("Timeout checking winner");
            return;
        }

        if (winnerResult.isWinner) {
            document.getElementById("popup").style.display = "flex";
            generateConfetti();
            startCountdown(10, "winnerCountdown");
        } else {
            document.getElementById("losePopup").style.display = "flex";
            generateSmokeAndBlood();
            startCountdown(10, "loserCountdown");
        }

        const roundDisplay = document.getElementById("round");
        if (roundDisplay) {
            roundDisplay.innerText = round;
        }
    } catch (error) {
        console.error("Error in showNextRoundPopup:", error);
    }
}

// Add function to initialize score display
function initializeScoreDisplay() {
    const scoreDisplay = document.getElementById("score");
    if (scoreDisplay && !scoreDisplay.innerText) {
        scoreDisplay.innerText = "0";
    }
}

// Call this when game starts
document.addEventListener("DOMContentLoaded", function () {
    initializeScoreDisplay();
});

// Modified checkBoard function to use async/await
async function checkBoard() {
    const board = document.getElementById("board").getElementsByTagName('img');
    let correctCount = 0;
    let totalPieces = correctOrder.length;
    let allPiecesPlaced = true;

    for (let i = 0; i < board.length; i++) {
        const currentImg = board[i].src.split("/").pop();
        const correctImg = correctOrder[i];
        
        if (!board[i].style.filter.includes('none')) {
            allPiecesPlaced = false;
            break;
        }
        
        if (currentImg === correctImg) {
            correctCount++;
        }
    }

    if (correctCount === totalPieces && allPiecesPlaced) {
        clearInterval(timer);
        clearInterval(winnerCheckInterval); // Stop checking for winners
        gameActive = false;
        const timeTaken = 60 - timeRemaining;

        try {
            // First, save game result
            await sendGameData(playerId, correctCount, timeTaken, round, roomId);
            
            // Then explicitly update the user level through the API
            const updateResponse = await fetch(`http://127.0.0.1:8000/updateLevel/${playerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const updateData = await updateResponse.json();
            console.log("Level update response:", updateData);
            
            // Update score display with data from server
            const scoreDisplay = document.getElementById("score");
            if (scoreDisplay && updateData.success) {
                const scoreDisplay = document.getElementById("score");
                if (scoreDisplay) {
                    const currentScore = parseInt(scoreDisplay.innerText || "0");
                    scoreDisplay.innerText = currentScore + 1;
                }
                console.log(`Score updated to ${updateData.wins}, level is now ${updateData.level}`);
            }

            const popup = document.getElementById("popup");
            if (popup) {
                popup.style.display = "flex";
                generateConfetti();
                
                // Always show the winner popup first with countdown
                if (round < maxRounds) {
                    // For rounds 1 and 2, just show the winner popup with countdown
                    startCountdown(10, "winnerCountdown");
                    round++;
                    const roundDisplay = document.getElementById("round");
                    if (roundDisplay) {
                        roundDisplay.innerText = round;
                    }
                } else {
                    // For round 3, show winner popup with countdown, then show finishModal
                    const finishCountdown = document.getElementById("winnerCountdown");
                    let seconds = 5; // Show win popup for 5 seconds before showing finishModal
                    
                    finishCountdown.textContent = seconds;
                    
                    const countdownInterval = setInterval(() => {
                        seconds--;
                        finishCountdown.textContent = seconds;
                        
                        if (seconds <= 0) {
                            clearInterval(countdownInterval);
                            closeModal(); // Close the win popup
                            finishModal(roomId, round, playerId); // Show the finish modal
                        }
                    }, 1000);
                }
            }

            return true;
        } catch (error) {
            console.error("Error in checkBoard:", error);
            return false;
        }
    }
    
    return false;
}

async function finishModal(roomId, round, playerId) {
    let timeoutId;
    let winnerResult;
    
    // Get the current score
    const scoreDisplay = document.getElementById("score");
    const currentScore = scoreDisplay ? parseInt(scoreDisplay.innerText || "0") : 0;
    
    // Check winner only if we have score points (indicating wins)
    winnerResult = { 
        isWinner: currentScore > 1,
        totalWin: currentScore
    };
    
    // Call the resetWins function after the game is complete
    try {
        const resetResponse = await fetch(`http://127.0.0.1:8000/resetWins/${playerId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const resetData = await resetResponse.json();
        console.log("Wins reset response:", resetData);
        
        // Update the score display with reset value (should be 0)
        if (scoreDisplay && resetData.success) {
            scoreDisplay.innerText = resetData.wins;
        }
    } catch (error) {
        console.error("Error resetting wins:", error);
    }
    
    const modal = document.getElementById("myModal");
    const btn = document.getElementById("btn-finish");
    
    // Always show modal at the end
    modal.style.display = "flex";
    
    if (winnerResult.isWinner) {
        document.getElementById("description").innerText = "Silahkan Klaim Hadiah Anda";
        btn.innerText = "Klaim";
        
        const redirectWinner = () => {
            window.location.replace(`http://127.0.0.1:8000/showGalleries/${playerId}`);
        };
        
        timeoutId = setTimeout(redirectWinner, 10000);
        btn.onclick = () => {
            clearTimeout(timeoutId);
            redirectWinner();
        };
    } else {
        document.getElementById("description").innerText = "Kembali Ke Halaman Utama";
        btn.innerText = "Kembali";
        
        const redirectLoser = () => {
            window.location.replace("http://127.0.0.1:8000/room");
        };
        
        timeoutId = setTimeout(redirectLoser, 10000);
        btn.onclick = () => {
            clearTimeout(timeoutId);
            redirectLoser();
        };
    }
    
    let countdown = 5;
    const countdownElement = document.createElement("div");
    countdownElement.style.marginTop = "10px";
    modal.querySelector("#description").appendChild(countdownElement);
    
    const countdownInterval = setInterval(() => {
        countdown--;
        countdownElement.textContent = `Redirect otomatis dalam ${countdown} detik...`;
        if(countdown <= 0) clearInterval(countdownInterval);
    }, 1000);
}

function startCountdown(seconds, countdownId) {
    let countdownElement = document.getElementById(countdownId);
    if (!countdownElement) {
        console.error(`Element dengan ID '${countdownId}' tidak ditemukan!`);
        return;
    }

    countdownElement.textContent = seconds; // Setel teks countdown

    let countdownInterval = setInterval(() => {
        seconds--; // Kurangi waktu countdown
        countdownElement.textContent = seconds; // Perbarui teks countdown

        if (seconds <= 0) {
            clearInterval(countdownInterval); // Hentikan countdown
            closeModal(); // Tutup pop-up
            startTimer(); // Mulai ronde berikutnya
        }
    }, 1000); // Perbarui setiap 1 detik
}

function closeModal() {
    document.getElementById("popup").style.display = "none"; // Sembunyikan pop-up
    document.getElementById("losePopup").style.display = "none"; // Sembunyikan pop-up
}

// Tutup modal jika klik di luar area modal atau tombol close
window.onclick = function (event) {
    let modal = document.getElementById("myModal");
    if (event.target == modal || event.target.classList.contains("close")) {
        closeModal();
    }
};

function checkAllPlayersFinished(roomId, round) {
    return fetch(
        `http://127.0.0.1:8000/checkAllPlayersFinished/${roomId}/${round}`
    )
        .then((response) => response.json())
        .then((data) => {
            return data.all_finished; // Mengembalikan true atau false
        })
        .catch((error) => {
            console.error("Error:", error);
            return false;
        });
}

function generateConfetti() {
    const confettiContainer = document.getElementById("confetti-container");
    confettiContainer.innerHTML = ""; // Bersihkan confetti sebelumnya

    const colors = [
        "#FF6B6B",
        "#4ECDC4",
        "#45B7D1",
        "#96CEB4",
        "#FFEEAD",
        "#FF9999",
        "#F7CAC9",
        "#92A8D1",
        "#88B04B",
        "#FFD700",
    ];
    for (let i = 0; i < 20; i++) {
        const confetti = document.createElement("div");
        confetti.className = "confetti";
        confetti.style.left = `${Math.random() * 100}%`;
        confetti.style.animationDelay = `${Math.random()}s`;
        confetti.style.backgroundColor =
            colors[Math.floor(Math.random() * colors.length)];
        confettiContainer.appendChild(confetti);
    }
}

// Fungsi untuk efek asap dan darah
function generateSmokeAndBlood() {
    const smokeContainer = document.getElementById("smoke-container");
    const bloodContainer = document.getElementById("blood-container");
    smokeContainer.innerHTML = "";
    bloodContainer.innerHTML = "";

    // Generate asap
    for (let i = 0; i < 15; i++) {
        const smoke = document.createElement("div");
        smoke.className = "smoke-particle";
        smoke.style.left = `${Math.random() * 100}%`;
        smoke.style.setProperty("--random", Math.random());
        smoke.style.animationDelay = `${Math.random() * 2}s`;
        smokeContainer.appendChild(smoke);
    }

    // Generate darah
    for (let i = 0; i < 10; i++) {
        const blood = document.createElement("div");
        blood.className = "blood-drop";
        blood.style.left = `${Math.random() * 100}%`;
        blood.style.animationDelay = `${Math.random()}s`;
        bloodContainer.appendChild(blood);
    }
}

// Tutup popup kalah
document
    .getElementById("losePopup")
    .addEventListener("click", function (event) {
        if (event.target === this || event.target.id === "closeLosePopup") {
            this.style.display = "none";
        }
    });

// Tutup popup kalah
document.getElementById("popup").addEventListener("click", function (event) {
    if (event.target === this || event.target.id === "closePopup") {
        this.style.display = "none";
    }
});

function checkOtherPlayerWon() {
    // Check every 2 seconds if any player has already won this round
    return setInterval(async () => {
        if (!gameActive) return; // Don't check if game isn't active
        
        try {
            const response = await fetch(`http://127.0.0.1:8000/getWinner/${roomId}/${round}`);
            
            if (!response.ok) {
                throw new Error(`Server returned ${response.status}`);
            }
            
            const data = await response.json();
            console.log("Winner check response:", data); // Log the response for debugging
            
            // If there's a winner and it's not the current player, show the loss popup
            if (data.pemenang == 1 && data.id != playerId) {
                console.log("Another player won:", data.winner);
                
                // Stop all timers and game activity
                clearInterval(timer);
                clearInterval(winnerCheckInterval);
                gameActive = false;
                
                // Save current player's progress
                const correctPieces = countCorrectPieces();
                const timeTaken = 60 - timeRemaining;
                
                try {
                    await sendGameData(playerId, correctPieces, timeTaken, round, roomId);
                    console.log("Game data saved for losing player");
                } catch (error) {
                    console.error("Error saving losing player data:", error);
                }
                
                // Display loser popup
                const losePopup = document.getElementById("losePopup");
                if (losePopup) {
                    losePopup.style.display = "flex";
                    generateSmokeAndBlood();
                    
                    // Update winner display
                    // const loserDisplay = document.getElementById("loserDisplay");
                    // if (loserDisplay) {
                    //     loserDisplay.innerText = `Pemenang: ${data.winner}`;
                    // }
                    
                    if (round >= maxRounds) {
                        // For round 3, show loser popup with countdown, then show finishModal
                        const finishCountdown = document.getElementById("loserCountdown");
                        let seconds = 5;
                        
                        finishCountdown.textContent = seconds;
                        
                        const countdownInterval = setInterval(() => {
                            seconds--;
                            finishCountdown.textContent = seconds;
                            
                            if (seconds <= 0) {
                                clearInterval(countdownInterval);
                                closeModal();
                                finishModal(roomId, round, playerId);
                            }
                        }, 1000);
                    } else {
                        // For rounds 1 and 2, just show the loser popup with countdown
                        round++;
                        document.getElementById("round").innerText = round;
                        startCountdown(10, "loserCountdown");
                    }
                } else {
                    console.error("losePopup element not found!");
                }
            } else if (data.message === "Menunggu pemain lain") {
                console.log("Still waiting for other players...");
            }
        } catch (error) {
            console.error("Error checking if another player won:", error);
        }
    }, 2000); // Check every 2 seconds (reduced from 3 seconds for faster detection)
}