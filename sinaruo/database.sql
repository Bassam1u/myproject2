-- قاعدة بيانات لتحويل الأموال
CREATE DATABASE IF NOT EXISTS critical_scenarios;
USE critical_scenarios;

-- جدول الحسابات البنكية
CREATE TABLE accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_number VARCHAR(20) UNIQUE NOT NULL,
    account_name VARCHAR(100) NOT NULL,
    balance DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول التحويلات
CREATE TABLE transfers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    from_account VARCHAR(20) NOT NULL,
    to_account VARCHAR(20) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'rolled_back') DEFAULT 'pending',
    transaction_id VARCHAR(50) UNIQUE,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- جدول سجلات النظام
CREATE TABLE system_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    log_type ENUM('info', 'warning', 'error', 'critical') NOT NULL,
    message TEXT NOT NULL,
    user_id INT,
    transaction_id VARCHAR(50),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول نقاط الاستعادة
CREATE TABLE rollback_points (
    id INT PRIMARY KEY AUTO_INCREMENT,
    point_name VARCHAR(100) NOT NULL,
    data_snapshot JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إدخال بيانات تجريبية
INSERT INTO accounts (account_number, account_name, balance) VALUES
('ACC001', 'أحمد محمد', 5000.00),
('ACC002', 'سارة خالد', 3000.00),
('ACC003', 'علي حسن', 7500.00),
('ACC004', 'فاطمة عبدالله', 2000.00);

-- إضافة مستخدم للنظام
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إضافة مستخدم افتراضي (كلمة المرور: admin123)
INSERT INTO users (username, password, email, role) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 'admin@system.com', 'admin');
