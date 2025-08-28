-- backend/migrations/schema.sql
CREATE DATABASE IF NOT EXISTS final_vue;
USE volunteer_db;

CREATE TABLE volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    skills TEXT,
    availability ENUM('weekdays', 'weekends', 'both') DEFAULT 'weekdays',
    interests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    time TIME,
    location VARCHAR(200),
    required_skills TEXT,
    volunteer_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    volunteer_id INT,
    event_id INT,
    match_score INT,
    status ENUM('pending', 'confirmed', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (volunteer_id) REFERENCES volunteers(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- بيانات نموذجية
INSERT INTO volunteers (name, email, skills, availability, interests) VALUES
('أحمد محمد', 'ahmed@example.com', 'البرمجة, التصميم', 'both', 'التطوع في مجال التقنية والتعليم'),
('فاطمة عبدالله', 'fatima@example.com', 'التدريس, الترجمة', 'weekends', 'مساعدة الأطفال وتعليم اللغات'),
('خالد السعدي', 'khaled@example.com', 'القيادة, التنظيم', 'weekdays', 'تنظيم الفعاليات والتخطيط');

INSERT INTO events (title, description, date, time, location, required_skills, volunteer_count) VALUES
('فعالية تعليم البرمجة للأطفال', 'فعالية لتعليم أساسيات البرمجة للأطفال من سن 8 إلى 12 سنة', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '10:00:00', 'مركز المجتمع', 'البرمجة, التدريس', 5),
('يوم التنظيف البيئي', 'فعالية لتنظيف الحدائق العامة والمشاركة في الحفاظ على البيئة', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '08:00:00', 'الحديقة المركزية', 'العمل الجماعي, القيادة', 10),
('معرض التطوع', 'معرض للتعريف بفرص التطوع والأنشطة المجتمعية', DATE_ADD(CURDATE(), INTERVAL 21 DAY), '14:00:00', 'قاعة المؤتمرات', 'التواصل, التسويق', 8);