let todo = {
    tasks: JSON.parse(localStorage.getItem("tasks")) || [],
    add(title, date="") {
        todo.tasks.push(
            {
                title: title,
                date: date,
                visible: true,
            }
        );
        localStorage.setItem("tasks", JSON.stringify(this.tasks));
        this.draw()
    },
    delete(idx) {
        this.tasks.splice(idx, 1);
        localStorage.setItem("tasks", JSON.stringify(this.tasks));
        this.draw();
    },
    edit(idx, title=null, date=null) {
        if (title !== null) this.tasks[idx].title = title;
        if (date !== null) this.tasks[idx].date = date;
        localStorage.setItem("tasks", JSON.stringify(this.tasks));
        this.draw();
    },
    draw() {
        let list = document.querySelector(".list");
        list.replaceChildren();
        let content = '';
        for (let [idx, task] of this.tasks.entries())
        {
            if (!task.visible) continue;
            content += `<li class="element" data-index="${idx}"><input type="checkbox" class="checkbox"/><p class="title">${task.title}</p><p class="date">${task.date}</p><button class="bin"></button></li>`;
        }
        list.innerHTML = content;
    },
}

todo.draw();

check_title = (title) => {
    if (title && title.length > 2 && title.length < 256)
        return true;
    else
        return false;
};

check_date = (date_val) => {
    if (!date_val)
        return "";
    let date = new Date(date_val);
    let today = new Date();
    let date_str = ('0'+date.getDate()).slice(-2)+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+date.getFullYear().toString();
    let in_future = date.getFullYear() > today.getFullYear();
    if (!in_future && date.getFullYear() === today.getFullYear())
    {
        in_future = date.getMonth() > today.getMonth();
        if (!in_future && date.getMonth() === today.getMonth())
        {
            in_future = date.getDate() > today.getDate();
        }
    }
    if (in_future)
        return date_str;
    else
        return "";
};

save_button = document.getElementById('save-button');
title_picker = document.getElementById('title-picker');
date_picker = document.getElementById('date-picker');

save_button.onclick = () => {
    let title = title_picker.value;
    let date_val = date_picker.value;
    if (check_title(title))
        todo.add(title, check_date(date_val));
};

search_bar = document.getElementById('search-bar');

search_bar.oninput = () => {
    let query = search_bar.value.toLowerCase();
    if (query.length < 2)
    {
        for (let task of todo.tasks)
        {
            task.visible = true;
            task.title = task.title.replace("<mark>", "");
            task.title = task.title.replace("</mark>", "");
        }
        todo.draw();
        return;
    }
    for (let task of todo.tasks)
    {
        task.title = task.title.replace("<mark>", "");
        task.title = task.title.replace("</mark>", "");
        if (!task.title.includes(query))
            task.visible = false;
        else
        {
            task.visible = true;
            task.title = task.title.replace(query, "<mark>$&</mark>");
        }
    }
    todo.draw();
}

let list = document.querySelector(".list");

list.addEventListener("click", (event) => {
    if (event.target.closest(".bin")) {
        let li = event.target.closest("li");
        todo.delete(Number(li.dataset.index));
    }
});

let dateToInput = (date) => {
    if (!date) return "";
    let [day, month, year] = date.split("-");
    return `${year}-${month}-${day}`;
}

let editing = null;

list.addEventListener("click", (event) => {
    if (!list.contains(event.target)) return;
    if (event.target.matches(".checkbox")) return;
    let li = event.target.closest(".element");
    if (!li) return;
    if (editing?.li === li) return;
    let title = li.querySelector(".title");
    let date = li.querySelector(".date");
    let originalTitle = title.textContent.trim();
    let originalDate = date.textContent.trim();
    title.innerHTML = `<input class="edit-input" value="${originalTitle}"/>`;
    date.innerHTML = `<input type="date" class="edit-input" value="${dateToInput(originalDate)}"/>`;
    let titleInput = title.querySelector(".edit-input");
    let dateInput = date.querySelector(".edit-input");
    editing = {
        li,
        idx: Number(li.dataset.index),
        titleInput,
        dateInput,
        originalTitle,
        originalDate
    };
    titleInput.focus();
    titleInput.select();
});

list.addEventListener("focusout", (event) => {
    if (!editing) return;
    let relatedTarget = event.relatedTarget;
    if (editing.li.contains(relatedTarget))
        return;
    let {
        idx,
        titleInput,
        dateInput,
        originalTitle,
        originalDate
    } = editing;
    let title = titleInput.value.trim();
    let date = dateInput.value;
    if (!check_title(title))
        title = originalTitle;
    if (date === dateToInput(originalDate))
        date = originalDate;
    else
    {
        if (date)
        {
            date = check_date(date);
            if (!date)
                date = originalDate;
        }
    }
    editing = null;
    todo.edit(idx, title, date);
});
