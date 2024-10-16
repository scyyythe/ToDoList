// add note
document.getElementById('addNote').addEventListener('click', function() {
    const form = document.getElementById('noteForm');
    const formData = new FormData(form);

    fetch('addNote.php', { 
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Note added successfully!');
            form.reset(); 
        } else {
            alert('Error adding note: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding note.');
    });
});
