CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Listings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    listing_name VARCHAR(100) NOT NULL,
    listing_description TEXT,
    listing_address VARCHAR(255) NOT NULL,
    listing_price INT NOT NULL,
    listing_available BOOLEAN NOT NULL DEFAULT TRUE,
    listing_image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Insert data into the Users table
INSERT INTO Users (username, email, password, created_at) VALUES
('john_doe', 'john.doe@example.com', '$2y$10$e6h/rD1r8.kSuA3wA6d4PeZ6Y39HcFF7In7EEvOBqA2Z5yG1J/q5G', '2024-08-01 10:00:00'),
('jane_smith', 'jane.smith@example.com', '$2y$10$mfSPnO0tpDfsTkZ4OBW2feKblGZiCSoPC6H.CpG3/1ohFSc9Omlji', '2024-08-02 11:30:00');

-- Insert data into the Listings table
INSERT INTO Listings (listing_name, listing_description, listing_address, listing_price, listing_available, listing_image_url, user_id, created_at) VALUES
('Charming Beach House', 'A beautiful house located near the beach with stunning ocean views.', '123 Ocean Drive, Miami, FL', 500000, TRUE, 'https://example.com/images/beach_house.jpg', 1, '2024-08-01 10:05:00'),
('Cozy Mountain Cabin', 'A cozy cabin in the mountains perfect for a relaxing getaway.', '456 Mountain Rd, Aspen, CO', 350000, TRUE, 'https://example.com/images/mountain_cabin.jpg', 2, '2024-08-02 11:35:00');