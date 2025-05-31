<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Saver Platform</title>
    <!-- Add version parameter to force reload of CSS -->
    <link rel="stylesheet" href="styles.css?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Fixed Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <!-- Replace with your actual logo -->
            <img src="images/logo.jpg" alt="Food Saver Logo" class="logo-img" onerror="this.src='https://via.placeholder.com/40x40'; this.onerror=null;">
            <span>Food Saver</span>
        </div>
        
        <ul class="nav-links">
            <li><a href="#home" class="nav-item">Home</a></li>
            <li><a href="#about" class="nav-item">About</a></li>
            <li><a href="#services" class="nav-item">Services</a></li>
            <li><a href="#contact" class="nav-item">Contact</a></li>
        </ul>
        
        <div class="user-profile">
            <?php 
            if (isset($_SESSION['username'])) {
                echo "<div class='profile-container'>";
                echo "<img src='images/icon.jpg' alt='Profile' class='profile-img' onerror=\"this.src='https://via.placeholder.com/40x40'; this.onerror=null;\">";
                echo "<span>Welcome, " . htmlspecialchars($_SESSION['username']) . "</span>";
                echo "<a href='logout.php' class='logout-btn'><i class='fas fa-sign-out-alt'></i></a>";
                echo "</div>";
            } else {
                echo '<a href="login.php" class="login-btn">Login</a>';
            }
            ?>
        </div>
    </nav>

    <!-- Home Section with Image Slider -->
    <section id="home" class="section">
        <div class="image-slider">
            <div class="slider-container">
                <div class="slide active">
                    <!-- Replace with your actual images -->
                    <img src="images/slider1.jpg" alt="Food Waste Solution" onerror="this.src='https://via.placeholder.com/1200x600?text=Food+Waste+Solution'; this.onerror=null;">
                    <div class="slide-content">
                        <h1>Welcome to Food Saver Platform</h1>
                        <p>Reduce Waste • Feed People • Fuel Energy</p>
                        <a href="#services" class="cta-button">Explore Our Services</a>
                    </div>
                </div>
                <div class="slide">
                    <img src="images/slider2.jpg" alt="Community Support" onerror="this.src='https://via.placeholder.com/1200x600?text=Community+Support'; this.onerror=null;">
                    <div class="slide-content">
                        <h1>Support Your Community</h1>
                        <p>Connect with local organizations to donate surplus food</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="images/slider3.jpeg" alt="Renewable Energy" onerror="this.src='https://via.placeholder.com/1200x600?text=Renewable+Energy'; this.onerror=null;">
                    <div class="slide-content">
                        <h1>Convert Waste to Energy</h1>
                        <p>Turn food waste into valuable biogas resources</p>
                    </div>
                </div>
            </div>
            <div class="slider-controls">
                <span class="control-dot active" data-slide="0"></span>
                <span class="control-dot" data-slide="1"></span>
                <span class="control-dot" data-slide="2"></span>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <h2 class="section-title">About Food Saver</h2>
        <div class="about-container">
            <div class="about-text">
                <p>Food Saver Platform is a comprehensive solution designed to address the global challenge of food waste. Our platform connects hotels, restaurants, and food vendors with consumers, charitable organizations, and biogas producers to ensure that surplus food is put to good use.</p>
                <p>Founded in 2023, we've helped reduce over 1,000 tons of food waste and provided more than 250,000 meals to those in need. Our platform also facilitates the conversion of inedible food waste into renewable energy through biogas production.</p>
                <p>We believe that by creating a sustainable ecosystem for food redistribution, we can make a significant impact on both environmental conservation and social welfare.</p>
            </div>
            <div class="about-image">
                <img src="images/about-us.jpg" alt="Food Saver Mission" onerror="this.src='https://via.placeholder.com/600x400?text=Our+Mission'; this.onerror=null;">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section">
        <h2 class="section-title">Our Services</h2>
        <div class="options">
            <div class="option">
                <div class="option-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Discounted Food for Customers</h3>
                <p>Hotels and restaurants sell about-to-expire food at a discount to customers, reducing waste while offering affordable meals.</p>
                <a href="food.php" class="button">Explore Deals</a>
            </div>
            
            <div class="option">
                <div class="option-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Donate to Orphanages & Old Age Homes</h3>
                <p>Hotels and food vendors can donate surplus food to those in need, supporting community welfare and reducing waste.</p>
                <a href="donate.php" class="button">Donate Now</a>
            </div>

            <div class="option">
                <div class="option-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Sell Expired Vegetables to Biogas Plants</h3>
                <p>Vegetable sellers can convert food waste to energy, contributing to renewable energy production and sustainability.</p>
                <a href="sell_biogas.php" class="button">Sell to Biogas</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <h2 class="section-title">Contact Us</h2>
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>123 Green Street, Eco City, EC12345</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <p>support@foodsaver.com</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <p>+123 456 7890</p>
                </div>
                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="contact-form">
                <form action="process_form.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="images/logo.jpg" alt="Food Saver Logo" class="footer-logo-img" onerror="this.src='https://via.placeholder.com/40x40'; this.onerror=null;">
                <span>Food Saver</span>
            </div>
            <p>Making a difference by reducing food waste, supporting the community, and creating renewable energy.</p>
            <p>&copy; 2025 Food Saver Platform. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                window.scrollTo({
                    top: targetSection.offsetTop,
                    behavior: 'smooth'
                });
            });
        });

        // Image slider functionality
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.control-dot');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                const slideIndex = parseInt(this.getAttribute('data-slide'));
                showSlide(slideIndex);
            });
        });

        // Auto-advance slides every 5 seconds
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);

        // Highlight active nav item based on scroll position
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.section');
            const navItems = document.querySelectorAll('.nav-item');
            
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (pageYOffset >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });
            
            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === `#${current}`) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>