<?php

/*
 * Modules of each programme, semester by semester, read from the programme brochures (Nov 2026).
 * Each entry: [level, programme title as on the website, [semester number => [module names]]].
 * Loaded into the programmes by:  php artisan programmes:import-modules
 *
 * Changes made while copying from the brochures (names otherwise as printed):
 *  - trailing full stops removed, long dashes written as "-", spacing fixed
 *  - spelling errors corrected: Morophology, Grammer, Stenography, "Research Methods and Ethic"
 *  - "Big Data Technologies" is printed twice in HND Data Science semester 3; listed once
 */

return [

    // ------------------------------------------------------------------ Diploma
    ['Diploma', 'Diploma in English', [
        1 => ['Fundamentals of English Language', 'Reading Skills & Vocabulary Development', 'Writing Skills', 'Speaking & Pronunciation Skills'],
        2 => ['Listening Skills & Communication', 'Functional & Professional English', 'Literature Appreciation', 'Final Project'],
    ]],
    ['Diploma', 'Diploma in Information Technology', [
        1 => ['Introduction to IT', 'Microsoft Office Package', 'Computer Hardware', 'Computer Networking', 'Internet Email and Web Engineering'],
        2 => ['Database Management System', 'Web Development Fundamentals', 'Social Media and Networking', 'Software Engineering', 'Final Project'],
    ]],
    ['Diploma', 'Diploma in Digital Marketing', [
        1 => ['Introduction to Digital Marketing', 'Search Engine Optimization (SEO)', 'Social Media Marketing (SMM)', 'Search Engine Marketing (SEM / Google Ads)'],
        2 => ['Content Marketing', 'Email Marketing', 'Web Analytics & Reporting', 'Digital Marketing Project / Practical'],
    ]],
    ['Diploma', 'Diploma in Artificial Intelligence', [
        1 => ['Introduction to Artificial Intelligence', 'Python for AI & Data Science', 'Mathematics for AI (Linear Algebra & Probability)', 'Machine Learning Basics'],
        2 => ['Data Handling & Visualization (Pandas/Matplotlib)', 'Deep Learning Foundations (Neural Networks)', 'AI in Real World (Projects & Applications)', 'Final Project (AI-based project)'],
    ]],
    ['Diploma', 'Diploma in Cyber Security', [
        1 => ['Introduction to Cyber Security', 'Network Security', 'OS & Application Security', 'Ethical Hacking'],
        2 => ['Digital Forensics', 'Security Management', 'Final Project'],
    ]],
    ['Diploma', 'Diploma in UI & UX and Full Stack Development', [
        1 => ['Fundamentals of Design & UI/UX', 'Typography & Color Theory', 'Graphic Design Tools for UI', 'Frontend Web Development'],
        2 => ['Backend Development', 'Databases', 'Web Hosting, Deployment & SEO Basics', 'Final Project'],
    ]],
    ['Diploma', 'Diploma in Mobile Development', [
        1 => ['Foundational Programming', 'Android Application Development', 'iOS Application Development', 'Cross-Platform Mobile Development'],
        2 => ['UI / UX Design for Mobile Apps', 'Database Management', 'Mobile Networking & APIs', 'Final Project'],
    ]],

    // ------------------------------------------------------------------ HND
    ['HND', 'HND in Business Management', [
        1 => ['Principles of Management', 'Business Communication', 'Marketing Management', 'Financial Accounting and Analysis', 'Human Resource Management'],
        2 => ['Business Mathematics', 'Information Technology for Business Management', 'Business Law and Ethics', 'Entrepreneurship and Small Business Management', 'Operations and Supply Chain Management'],
        3 => ['Strategic Management and Economics', 'Business Economics', 'Management Information Systems', 'Project Management'],
        4 => ['Financial Accounting', 'Micro Economics', 'Leadership and Organizational Behavior', 'Global Business Environment', 'Final Project'],
    ]],
    ['HND', 'HND in Data Science', [
        1 => ['Fundamentals of Programming (Python/R)', 'Mathematics for Data Science (Linear Algebra, Statistics)', 'Data Collection and Cleaning', 'Data Visualization and Reporting'],
        2 => ['Exploratory Data Analysis (EDA)', 'Supervised & Unsupervised Learning', 'Introduction to SQL and Databases', 'Deep Learning and Neural Networks & Natural Language Processing (NLP)'],
        3 => ['Time Series Analysis', 'Big Data Technologies (Hadoop, Spark)', 'Cloud Computing for Data Science (AWS, Azure, GCP)'],
        4 => ['Data Science Ethics and Governance', 'Model Deployment and MLOps', 'Case Studies in Data Science', 'Final Project'],
    ]],
    ['HND', 'HND in Artificial Intelligence', [
        1 => ['Professional Practice in the Digital Economy', 'Innovation and Digital Transformation', 'Cyber Security', 'Programming'],
        2 => ['Big Data & Visualization', 'Cloud Fundamentals', 'Software Development Lifecycles', 'Fundamentals of Artificial Intelligence (AI) & Intelligent Systems'],
        3 => ['Business Intelligence', 'Internet of Things', 'Emerging Technologies', 'Risk Analysis and Systems Testing'],
        4 => ['Application Development', 'Application Program Interfaces', 'Digital Sustainability', 'Final Project'],
    ]],
    ['HND', 'HND in Information Technology', [
        1 => ['Computer Systems Fundamentals', 'Structure Programming (Python/Java/C#)', 'Database Design and Development', 'Web Design and Development (HTML, CSS, JavaScript)', 'Artificial Intelligence'],
        2 => ['Networking and Internet Technologies', 'Operating Systems and Server Management', 'Information Systems and Analysis', 'Mathematics for Computing', 'Software Engineering Principles'],
        3 => ['Cyber Security and Ethical Practices', 'Cloud Computing Fundamentals', 'Data Analytics and Visualization', 'Mobile Application Development'],
        4 => ['IT Project Management', 'Professional Practice in Computing', 'E-Commerce', 'Final Project'],
    ]],
    ['HND', 'HND in Cyber Security', [
        1 => ['Programming', 'Networking', 'Professional Practice', 'Database Design & Development'],
        2 => ['Security', 'Data Analytics', 'Cyber Security', 'Website Design and Development'],
        3 => ['Computing Research Project', 'Business Process Support', 'Applied Cryptography in the Cloud', 'Forensics'],
        4 => ['Information Security Management', 'Network Management', 'System Analysis and Design', 'Final Project'],
    ]],
    ['HND', 'HND in Computer Science', [
        1 => ['Programming Fundamentals', 'Computer Systems & Architecture', 'Database Systems', 'Networking Fundamentals'],
        2 => ['Web Development', 'Mathematics for Computing', 'Operating Systems', 'Professional Skills & IT Ethics'],
        3 => ['Object-Oriented Programming', 'Software Engineering & Project Management', 'Computer Networks & Security'],
        4 => ['Data Structures & Algorithms', 'IT Project / Capstone Project', 'Electives (varies)', 'Final Project'],
    ]],
    ['HND', 'HND in English', [
        1 => ['English Poetry', 'English Fiction', 'Communicative Grammar - I', 'Listening & Speaking Skills - I', 'Introduction to Language & Literature', 'IT Essentials for English Use'],
        2 => ['English Drama', 'Informative Writing', 'Phonetics & Phonology', 'Listening & Speaking Skills - II', 'Morphology', 'Communicative Grammar - II'],
        3 => ['Sri Lankan Literature in English', 'Advanced Communicative Grammar - I', 'Advanced Listening & Speaking Skills', 'Syntax', 'Advanced Writing', 'Managerial Skills'],
        4 => ['World Literature in English', 'English Translation', 'Internet English & Varieties of English', 'Practical Project', 'Internship'],
    ]],

    // ------------------------------------------------------------------ Degree
    ['Degree', 'Bachelor of Human Resources Management', [
        1 => ['Introduction to Human Resource Management', 'Principles of Management', 'Business Economics', 'Organizational Behaviour'],
        2 => ['Business Communication Skills', 'Fundamentals of Accounting', 'Information Technology for Business'],
        3 => ['Human Resource Planning & Development', 'Recruitment & Selection', 'Training & Development', 'Performance Management'],
        4 => ['Employment Law & Industrial Relations', 'Research Methods for Business', 'Management Information Systems'],
        5 => ['Strategic Human Resource Management', 'Talent Management & Leadership', 'Compensation & Reward Management', 'Employee Relations & Labour Law'],
        6 => ['Change Management & Organizational Development', 'International Human Resource Management', 'Final Project / Internship'],
    ]],
    ['Degree', 'Bachelor of Business Management', [
        1 => ['Principles of Management', 'Business Communication', 'Microeconomics', 'Financial Accounting - I'],
        2 => ['Business Mathematics & Statistics', 'Principles of Marketing', 'Business Environment', 'Information Technology for Business'],
        3 => ['Organizational Behaviour', 'Human Resource Management', 'Financial Accounting - II', 'Cost & Management Accounting'],
        4 => ['Business Law', 'Marketing Management', 'Operations Management', 'Research Methods for Business'],
        5 => ['Strategic Management', 'Financial Management', 'Entrepreneurship & Small Business Management', 'International Business Management'],
        6 => ['Project Management', 'Business Ethics & Corporate Governance', 'Management Information Systems', 'Final Year Project'],
    ]],
    ['Degree', 'Bachelor of English Language & Linguistics', [
        1 => ['Introduction to English Language Studies', 'Fundamentals of Linguistics', 'Grammar & Usage in English', 'Academic Reading & Writing'],
        2 => ['Phonetics & Phonology', 'Morphology & Syntax', 'Listening & Speaking Skills', 'Study Skills & Academic Communication'],
        3 => ['Semantics & Pragmatics', 'Discourse Analysis', 'English Literature - Introduction', 'Research Skills in Linguistics'],
        4 => ['Sociolinguistics', 'Psycholinguistics', 'English Language Teaching - Basics', 'Professional Communication'],
        5 => ['Applied Linguistics', 'English for Specific Purposes (ESP)', 'Language Assessment & Evaluation', 'Translation Studies'],
        6 => ['Stylistics', 'Corpus Linguistics (Introductory)', 'Research Methodology', 'Final Year Project / Internship'],
    ]],
    ['Degree', 'BSc in Information Technology', [
        1 => ['Introduction to Information Technology', 'Computer Hardware and Software Systems', 'Programming Fundamentals (Python / C)', 'Mathematics for Computing', 'Database Management Systems (DBMS)'],
        2 => ['Web Design and Development (HTML, CSS, JS)', 'Computer Networks - Basics', 'Communication Skills and Academic Writing', 'Operating Systems Concepts', 'IT Project I (Mini Project)'],
        3 => ['Object-Oriented Programming (Java / C++)', 'Data Structures and Algorithms', 'System Analysis and Design', 'Database Administration and SQL', 'Network Security and Cryptography'],
        4 => ['Software Engineering Principles', 'Web Application Development (PHP / Node.js)', 'Human-Computer Interaction (HCI)', 'Cloud Computing Fundamentals', 'IT Project II (Web or Database-Based)', 'Data Analytics and Business Intelligence'],
        5 => ['Mobile Application Development (Android / iOS)', 'Internet of Things (IoT)', 'Artificial Intelligence and Machine Learning Basics', 'Cybersecurity and Ethical Hacking', 'Cloud Architecture and Virtualization'],
        6 => ['IT Project Management', 'Emerging Technologies (Blockchain, Big Data, AR/VR)', 'Professional Practice and Ethics', 'Final Year Research / Industry Project'],
    ]],
    ['Degree', 'BSc in Cyber Security & Digital Forensics', [
        1 => ['Web Technologies', 'Mechanics', 'Communication Skills', 'Introduction to Computer Science & Programming in Python', 'Management Thoughts and Applications',
              'Introduction to Computer Science & Programming in Python Lab', 'Web Technologies Lab', 'Mechanics Lab'],
        2 => ['Number Theory', 'Environmental Studies', 'Cyberspace & the Law', 'Open Elective', 'Object-oriented Programming', 'Object-oriented Programming Lab', 'Linux Environment Lab'],
        3 => ['Introduction to Cyber Security', 'Computer Networks', 'Operating Systems', 'Data Structures', 'Probability & Statistics', 'Disaster Management',
              'Introduction to Cyber Security Lab', 'Operating Systems Lab'],
        4 => ['Database Management Systems', 'Analysis & Design of Algorithms', 'Network Security & Steganography', 'Cryptography', 'Artificial Intelligence',
              'Database Management Systems Lab', 'Analysis & Design of Algorithms Lab', 'Network Security & Steganography Lab'],
        5 => ['Foundation of Computer Systems', 'Cyber Forensics', 'Programming in MATLAB', 'Programming in MATLAB - Lab', 'Cyber Forensics Lab', 'Probability & Statistics Lab'],
        6 => ['Intrusion Detection & Prevention Systems', 'Blockchains', 'Software Engineering', 'Computer Organization & Architecture', 'Computer Organization & Architecture Lab',
              'Blockchains Lab', 'Final Year Project / Internship'],
    ]],
    ['Degree', 'BSc in Data Science', [
        1 => ['Introduction to Programming (Python)', 'Computer & IT Fundamentals', 'Mathematics for Data Science', 'Statistics Fundamentals'],
        2 => ['Data Structures & Algorithms', 'Probability & Statistical Analysis', 'Python for Data Analysis', 'Data Visualization Basics'],
        3 => ['Database Management Systems (SQL / NoSQL)', 'Data Wrangling & Preprocessing', 'Exploratory Data Analysis (EDA)', 'Data Visualization Tools (Matplotlib / Seaborn / Power BI / Tableau)'],
        4 => ['Machine Learning Fundamentals', 'Supervised & Unsupervised Learning', 'Data Mining Techniques'],
        5 => ['Advanced Machine Learning', 'Artificial Intelligence Fundamentals', 'Deep Learning Basics', 'Cloud Computing for Data Science (AWS / Azure / Google Cloud)'],
        6 => ['Big Data Concepts', 'Data Ethics, Privacy & Security', 'Final Project / Internship'],
    ]],
    ['Degree', 'BSc in Artificial Intelligence', [
        1 => ['Introduction to Computing', 'Programming Fundamentals (Python, C++)', 'Mathematics for AI (Linear Algebra, Calculus)', 'Statistics and Probability'],
        2 => ['Computer Systems and Architecture', 'Data Structures and Algorithms', 'Communication and Academic Writing', 'Principles of Artificial Intelligence'],
        3 => ['Object-Oriented Programming (Java / Python)', 'Database Management Systems (SQL, NoSQL)', 'Operating Systems', 'Machine Learning Fundamentals'],
        4 => ['Data Analytics and Visualization', 'Computer Networks and Internet Technologies', 'Human-Computer Interaction', 'Research Methods and Ethics'],
        5 => ['Deep Learning and Neural Networks', 'Natural Language Processing (NLP)', 'Computer Vision', 'Cloud Computing and Big Data', 'Intelligent Robotics'],
        6 => ['Internet of Things (IoT) and Edge AI', 'AI in Cybersecurity', 'Mini Project / Industrial Training'],
    ]],
    ['Degree', 'BSc in Computer Science', [
        1 => ['Introduction to Computer Science', 'Programming Fundamentals (Python / C)', 'Mathematics for Computing', 'Digital Logic and Computer Architecture'],
        2 => ['Database Fundamentals', 'Data Structures and Algorithms', 'Communication Skills and Technical Writing', 'Operating Systems Basics'],
        3 => ['Object-Oriented Programming (Java / C++)', 'Database Management Systems', 'Computer Networks', 'Software Engineering Principles'],
        4 => ['Web Technologies and Web Development', 'Data Analytics and Visualization', 'System Analysis and Design', 'Research Methods and Ethics', 'Internet of Things (IoT)'],
        5 => ['Artificial Intelligence and Machine Learning', 'Mobile Application Development', 'Cloud Computing', 'Cybersecurity and Ethical Hacking'],
        6 => ['Human-Computer Interaction (HCI)', 'Advanced Algorithms and Complexity', 'Final Project / Internship'],
    ]],

];
