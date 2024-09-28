document.getElementById('addTaskButton').addEventListener('click', function() {
    const taskInput = document.getElementById('taskInput');
    const task = taskInput.value;

    if (task.trim() === '') return;

    fetch('add_task.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `task=${encodeURIComponent(task)}`
    }).then(response => response.json()).then(data => {
        if (data.success) {
            const taskList = document.getElementById('taskList');
            const newTask = document.createElement('li');
            newTask.classList.add('fade-in');
            newTask.innerHTML = `${task} <button class="deleteTaskButton">Delete</button>`;
            taskList.appendChild(newTask);
            taskInput.value = '';
        }
    });
});

document.getElementById('taskList').addEventListener('click', function(e) {
    if (e.target.classList.contains('deleteTaskButton')) {
        const taskItem = e.target.parentElement;
        const taskId = taskItem.getAttribute('data-id');

        fetch('delete_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${taskId}`
        }).then(response => response.json()).then(data => {
            if (data.success) {
                taskItem.classList.add('fade-out');
                setTimeout(() => {
                    taskItem.remove();
                }, 300);
            }
        });
    }
});
