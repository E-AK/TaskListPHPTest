-- Таблица versions --
create table if not exists `versions` (
    `id` int(10) unsigned not null auto_increment,
    `name` varchar(255) not null,
    `created` timestamp default current_timestamp,
    primary key (id)
);

-- Создание таблицы "persons"
CREATE TABLE IF NOT EXISTS persons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы "task_statuses"
CREATE TABLE IF NOT EXISTS task_statuses (
id INT AUTO_INCREMENT PRIMARY KEY,
status VARCHAR(255) DEFAULT 'Unready'
);

-- Создание таблицы "tasks"
CREATE TABLE IF NOT EXISTS tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
description TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
status_id INT,
FOREIGN KEY (user_id) REFERENCES persons(id),
FOREIGN KEY (status_id) REFERENCES task_statuses(id)
);

insert into task_statuses (status) values ('Unready');

insert into task_statuses (status) values ('Ready');