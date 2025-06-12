
-- Membuat database
CREATE DATABASE IF NOT EXISTS meeting_db;
USE meeting_db;

-- Membuat tabel untuk menyimpan data meeting
CREATE TABLE IF NOT EXISTS meetings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    status ENUM('To Do', 'In Progress', 'Done') NOT NULL,
    team VARCHAR(100) DEFAULT 'unassigned'
);

-- Menambahkan data awal
INSERT INTO meetings (name, date, status, team) VALUES
('Pbl', '2024-12-05', 'To Do', 'Kelo 6'),
('Presentasi', '2024-12-14', 'In Progress', 'unassigned'),
('Darurat', '2024-12-04', 'Done', 'unassigned');
