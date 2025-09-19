<?php
session_start();
require_once '../Backend/Database.php';

$db = new Database();
$conn = $db->getConnection();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Handle Create
if (isset($_POST['add_note'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $conn->prepare('INSERT INTO notes (username, title, content, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$username, $title, $content]);
}

// Handle Update
if (isset($_POST['update_note'])) {
    $id = $_POST['note_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $conn->prepare('UPDATE notes SET title = ?, content = ? WHERE id = ? AND username = ?');
    $stmt->execute([$title, $content, $id, $username]);
}

// Handle Delete
if (isset($_POST['delete_note'])) {
    $id = $_POST['note_id'];
    $stmt = $conn->prepare('DELETE FROM notes WHERE id = ? AND username = ?');
    $stmt->execute([$id, $username]);
}

// Fetch notes
$notes = [];
$stmt = $conn->prepare('SELECT id, title, content, created_at FROM notes WHERE username = ? ORDER BY created_at DESC');
$stmt->execute([$username]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Frontend/Allpage.css">
    <title>User Dashboard</title>
</head>
<body>
    <div class="admin-wrapper">
        <div class="sidebar">
            <div class="logo">
                <p>Note<span>It!</span></p>
            </div>
                <ul>
                    <li><a href="#"><img src="../Frontend/images/notes.png" alt="notes">All Notes</a></li>
                    <li><a href="#"><img src="../Frontend/images/favorites.png" alt="favorites">Favorites</a></li>
                    <li><a href="#"><img src="../Frontend/images/archive.png" alt="archive">Archive</a></li>
                    <li><a href="../AuthLogic/logout.php"><img src="../Frontend/images/logout.png" alt="logout">Logout</a></li>
                </ul>
                    <div class="username">
                     <img src="../Frontend/images/Aljun.jpg" alt="profile"><p>Hi <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?><br>Welcome Back.</p>
                </div>
        </div>

        <!--Main-->
        <div class="main-content">
            <div class="header-container">
                <div class="header">
                    <p>All Notes</p>
                </div>
                <div class="searchbtn">
                    <input type="text" class="search" placeholder="search">
                    <button class="add-note">+</button>
                    <p>Add Notes</p>
                </div>
            </div>

            <!--NOTEPAGE-->
            <div class="notepage">
                <div class="boxes">
                    <!-- Notes will be rendered by JS -->
                </div>
            </div>            
        </div>    
    </div>

    <!-- Note Modal and Overlay -->
    <div id="note-modal-overlay" style="display:none;"></div>
    <div id="note-modal" style="display:none;">
        <div class="modal-content">
            <button id="close-modal" class="modal-close">&times;</button>
            <h2 id="modal-title">Add/Edit Note</h2>
            <form method="POST" class="crud-form" id="note-form">
                <input type="hidden" name="note_id" id="modal-note-id">
                <input type="text" name="title" id="modal-note-title" placeholder="Title" maxlength="50" required style="width:100%;margin-bottom:8px;">
                <textarea name="content" id="modal-note-content" placeholder="Write your note here..." rows="6" required style="width:100%;margin-bottom:8px;"></textarea>
                <div style="display:flex;gap:8px;">
                    <button type="submit" name="add_note" id="modal-add-btn" class="btn" style="flex:1;background:#2196f3;color:#fff;">Add</button>
                    <button type="submit" name="update_note" id="modal-update-btn" class="btn" style="flex:1;background:#4caf50;color:#fff;display:none;">Update</button>
                    <button type="submit" name="delete_note" id="modal-delete-btn" class="btn" style="flex:1;background:#f44336;color:#fff;display:none;" onclick="return confirm('Delete this note?');">Delete</button>
                </div>
            </form>
        </div>
    </div>
</script>
</script>
<script>
// Get DOM elements
const addNoteBtn = document.querySelector('.add-note');
const modalOverlay = document.getElementById('note-modal-overlay');
const modal = document.getElementById('note-modal');
const closeModalBtn = document.getElementById('close-modal');
const noteForm = document.getElementById('note-form');
const modalTitle = document.getElementById('modal-title');
const modalAddBtn = document.getElementById('modal-add-btn');
const modalUpdateBtn = document.getElementById('modal-update-btn');
const modalDeleteBtn = document.getElementById('modal-delete-btn');
const modalNoteId = document.getElementById('modal-note-id');
const modalNoteTitle = document.getElementById('modal-note-title');
const modalNoteContent = document.getElementById('modal-note-content');
const boxesContainer = document.querySelector('.boxes');

// Notes from PHP
const notes = <?php echo json_encode($notes); ?>;

// Show modal for adding note
addNoteBtn.addEventListener('click', function() {
    openModal();
});

// Close modal
closeModalBtn.addEventListener('click', function() {
    closeModal();
});
modalOverlay.addEventListener('click', closeModal);

function openModal(note = null) {
    modalOverlay.style.display = 'block';
    modal.style.display = 'block';
    modal.style.pointerEvents = 'auto';
    if (note) {
        modalTitle.textContent = 'Edit Note';
        modalNoteId.value = note.id;
        modalNoteTitle.value = note.title;
        modalNoteContent.value = note.content;
        modalAddBtn.style.display = 'none';
        modalUpdateBtn.style.display = 'inline-block';
        modalDeleteBtn.style.display = 'inline-block';
    } else {
        modalTitle.textContent = 'Add Note';
        modalNoteId.value = '';
        modalNoteTitle.value = '';
        modalNoteContent.value = '';
        modalAddBtn.style.display = 'inline-block';
        modalUpdateBtn.style.display = 'none';
        modalDeleteBtn.style.display = 'none';
    }
}

function closeModal() {
    modalOverlay.style.display = 'none';
    modal.style.display = 'none';
    modal.style.pointerEvents = 'none';
    noteForm.reset();
}

// Render notes
function renderNotes() {
    boxesContainer.innerHTML = '';
    if (notes.length === 0) {
        boxesContainer.innerHTML = '<p style="text-align:center;color:#aaa;">No notes yet.</p>';
        return;
    }
    notes.forEach(note => {
        const box = document.createElement('div');
        box.className = 'box';
        box.innerHTML = `
            <h1>${note.title}</h1>
            <p>${note.content}</p>
            <div class="dot"></div>
            <h5>${new Date(note.created_at).toLocaleString()}</h5>
        `;
        box.style.cursor = 'pointer';
        box.onclick = () => openModal(note);
        boxesContainer.appendChild(box);
    });
}

renderNotes();

// Optional: Prevent form resubmission on modal close
window.onpageshow = function(event) {
    if (event.persisted) {
        noteForm.reset();
    }
};

</script>
</body>
</html>