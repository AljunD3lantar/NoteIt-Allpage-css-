document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('note-modal');
    const modalOverlay = document.getElementById('note-modal-overlay');
    const closeModalBtn = document.getElementById('close-modal');
    const saveNoteBtn = document.getElementById('save-note');
    const deleteNoteBtn = document.getElementById('delete-note');
    const noteTitleInput = document.getElementById('modal-note-title');
    const noteContentInput = document.getElementById('modal-note-content');
    const addNoteBtn = document.querySelector('.add-note');
    const boxesContainer = document.querySelector('.boxes');

    let editingNote = null;

    addNoteBtn.addEventListener('click', function() {
        editingNote = null;
        noteTitleInput.value = '';
        noteContentInput.value = '';
        deleteNoteBtn.style.display = 'none';
        openModal();
    });

    boxesContainer.addEventListener('click', function(e) {
        const box = e.target.closest('.box');
        if (box) {
            editingNote = box;
            noteTitleInput.value = box.querySelector('h1').textContent;
            noteContentInput.value = box.querySelector('p').textContent;
            deleteNoteBtn.style.display = 'inline-block';
            openModal();
        }
    });

    saveNoteBtn.addEventListener('click', function() {
        const title = noteTitleInput.value.trim();
        const content = noteContentInput.value.trim();
        if (!title) {
            noteTitleInput.focus();
            return;
        }
        if (editingNote) {
            editingNote.querySelector('h1').textContent = title;
            editingNote.querySelector('p').textContent = content;
        } else {
            const newBox = document.createElement('div');
            newBox.className = 'box';
            newBox.innerHTML = `<h1>${title}</h1><p>${content}</p><h5>${new Date().toLocaleDateString()}</h5>`;
            boxesContainer.prepend(newBox);
        }
        closeModal();
    });

    deleteNoteBtn.addEventListener('click', function() {
        if (editingNote) {
            editingNote.remove();
            closeModal();
        }
    });

    closeModalBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    function openModal() {
        modal.style.display = 'block';
        modalOverlay.style.display = 'block';
    }
    function closeModal() {
        modal.style.display = 'none';
        modalOverlay.style.display = 'none';
    }
});
