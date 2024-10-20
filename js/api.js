document.addEventListener('DOMContentLoaded', function() {

    //update to completed
    document.querySelectorAll('.mark-complete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const noteId = this.dataset.noteId; 

            fetch('include/note-function.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ note_id: noteId })
            })
            .then(response => response.json())
            .then(data => {
                
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    

    // delete all completed notes
    document.getElementById('delete-completed-btn').addEventListener('click', function() {
                
            fetch('include/note-function.php', { 
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'delete' })
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
            
                if (data.success) {
                    window.location.reload();

                }
            })
            .catch(error => console.error('Error:', error));
        
    });


    //delete a specific note
    document.querySelectorAll('.delete-note-btn').forEach(button => {
        button.addEventListener('click', function() {
            const noteId = this.dataset.noteId; 
    
                fetch('include/note-function.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ note_id: noteId, action: 'delete' }) 
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            
        });
    });
});
