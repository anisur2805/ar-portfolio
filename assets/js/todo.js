document.addEventListener('DOMContentLoaded', () => {
    const todoForm = document.getElementById('todo-form');
    const todoInput = document.getElementById('todo-input');
    const todoList = document.getElementById('todo-list');
    const todoCount = document.getElementById('todo-count');
    const clearCompletedBtn = document.getElementById('clear-completed');

    let todos = JSON.parse(localStorage.getItem('anisur_portfolio_todos')) || [
        { id: 1, text: 'Architect scalable WP plugin', completed: true },
        { id: 2, text: 'Optimize Core Web Vitals', completed: false },
        { id: 3, text: 'Implement React-driven UI', completed: false }
    ];

    const saveTodos = () => {
        localStorage.setItem('anisur_portfolio_todos', JSON.stringify(todos));
        renderTodos();
    };

    const renderTodos = () => {
        todoList.innerHTML = '';
        const activeTodos = todos.filter(t => !t.completed);
        todoCount.textContent = activeTodos.length;

        todos.forEach(todo => {
            const li = document.createElement('li');
            li.className = `todo-item ${todo.completed ? 'completed' : ''}`;
            li.innerHTML = `
                <div class="todo-item-content">
                    <input type="checkbox" ${todo.completed ? 'checked' : ''} data-id="${todo.id}">
                    <span>${todo.text}</span>
                </div>
                <button class="delete-btn" data-id="${todo.id}">&times;</button>
            `;
            todoList.appendChild(li);
        });
    };

    todoForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = todoInput.value.trim();
        if (text) {
            todos.push({
                id: Date.now(),
                text,
                completed: false
            });
            todoInput.value = '';
            saveTodos();
        }
    });

    todoList.addEventListener('click', (e) => {
        if (e.target.type === 'checkbox') {
            const id = parseInt(e.target.dataset.id);
            todos = todos.map(t => t.id === id ? { ...t, completed: e.target.checked } : t);
            saveTodos();
        } else if (e.target.classList.contains('delete-btn')) {
            const id = parseInt(e.target.dataset.id);
            todos = todos.filter(t => t.id !== id);
            saveTodos();
        }
    });

    clearCompletedBtn.addEventListener('click', () => {
        todos = todos.filter(t => !t.completed);
        saveTodos();
    });

    renderTodos();
});
