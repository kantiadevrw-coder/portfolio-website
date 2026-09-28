 <?php
// Main portfolio page
// Save as: index.php

require_once 'config/database.php';
$page_title = 'Professional Portfolio - Web Developer';

// Fetch projects from database
$projects_query = "SELECT * FROM projects WHERE status = 1 ORDER BY display_order ASC, created_at DESC";
$projects_result = mysqli_query($conn, $projects_query);
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <div class="hero-content" data-aos="fade-right">
            <h1>Hi, I'm <span class="highlight">John Doe</span></h1>
            <h2>I'm a <span class="typed-text"></span></h2>
            <p>Passionate about creating beautiful and functional websites that deliver exceptional user experiences.</p>
            <div class="hero-buttons">
                <a href="#contact" class="btn btn-primary">Hire Me</a>
                <a href="#projects" class="btn btn-secondary">View Work</a>
            </div>
        </div>
        <div class="hero-image" data-aos="fade-left">
            <img src="assets/images/profile.jpg" alt="Profile Image">
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>About Me</h2>
            <p>Get to know me better</p>
        </div>
        <div class="about-content">
            <div class="about-text" data-aos="fade-right">
                <h3>I'm a passionate web developer</h3>
                <p>With over 5 years of experience in web development, I've worked with various technologies and frameworks to build robust web applications. I love solving complex problems and creating intuitive user interfaces.</p>
                <p>My journey in web development started when I built my first website in college. Since then, I've been constantly learning and improving my skills to stay up-to-date with the latest technologies.</p>
                <div class="about-stats">
                    <div class="stat">
                        <span class="stat-number" data-target="50">0</span>
                        <span class="stat-label">Projects Completed</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-target="30">0</span>
                        <span class="stat-label">Happy Clients</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-target="5">0</span>
                        <span class="stat-label">Years Experience</span>
                    </div>
                </div>
            </div>
            <div class="about-image" data-aos="fade-left">
                <img src="assets/images/about-image.jpg" alt="About Me">
            </div>
        </div>
    </div>
</section>

<!-- Skills Section -->
<section id="skills" class="skills">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>My Skills</h2>
            <p>What I'm good at</p>
        </div>
        <div class="skills-grid">
            <div class="skill-card" data-aos="fade-up" data-aos-delay="100">
                <div class="skill-icon">
                    <i class="fab fa-html5"></i>
                </div>
                <h3>HTML5</h3>
                <div class="skill-progress">
                    <div class="progress-bar" style="width: 95%"></div>
                </div>
            </div>
            <div class="skill-card" data-aos="fade-up" data-aos-delay="200">
                <div class="skill-icon">
                    <i class="fab fa-css3-alt"></i>
                </div>
                <h3>CSS3</h3>
                <div class="skill-progress">
                    <div class="progress-bar" style="width: 90%"></div>
                </div>
            </div>
            <div class="skill-card" data-aos="fade-up" data-aos-delay="300">
                <div class="skill-icon">
                    <i class="fab fa-js"></i>
                </div>
                <h3>JavaScript</h3>
                <div class="skill-progress">
                    <div class="progress-bar" style="width: 85%"></div>
                </div>
            </div>
            <div class="skill-card" data-aos="fade-up" data-aos-delay="400">
                <div class="skill-icon">
                    <i class="fab fa-php"></i>
                </div>
                <h3>PHP</h3>
                <div class="skill-progress">
                    <div class="progress-bar" style="width: 88%"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>My Services</h2>
            <p>What I offer</p>
        </div>
        <div class="services-grid">
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>Web Development</h3>
                <p>Custom websites built with modern technologies and best practices.</p>
            </div>
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h3>UI/UX Design</h3>
                <p>Beautiful and intuitive designs that enhance user experience.</p>
            </div>
            <div class="service-card" data-aos="fade-up" data-aos-delay="300">
                <div class="service-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h3>Database Design</h3>
                <p>Efficient database structures for optimal performance.</p>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section id="projects" class="projects">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>My Projects</h2>
            <p>Some of my recent work</p>
        </div>
        <div class="projects-grid">
            <?php if ($projects_result && mysqli_num_rows($projects_result) > 0): ?>
                <?php while($project = mysqli_fetch_assoc($projects_result)): ?>
                    <div class="project-card" data-aos="fade-up">
                        <div class="project-image">
                            <img src="<?php echo htmlspecialchars($project['image_url'] ?: 'assets/images/project-placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        </div>
                        <div class="project-info">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($project['description'], 0, 100)) . '...'; ?></p>
                            <div class="project-tech">
                                <?php 
                                $techs = explode(',', $project['technologies']);
                                foreach($techs as $tech): 
                                ?>
                                    <span><?php echo htmlspecialchars(trim($tech)); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="project-links">
                                <?php if($project['project_url']): ?>
                                    <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank" class="project-link">Live Demo →</a>
                                <?php endif; ?>
                                <?php if($project['github_url']): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_url']); ?>" target="_blank" class="project-link">GitHub →</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No projects found. Check back soon!</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Contact Me</h2>
            <p>Get in touch with me</p>
        </div>
        <div class="contact-content">
            <div class="contact-info" data-aos="fade-right">
                <h3>Let's Talk</h3>
                <p>Have a project in mind? I'd love to hear about it!</p>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>kantiadev.rw@gmail.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Phone</h4>
                        <p>0738265090</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Location</h4>
                        <p>Kigali, Rwanda</p>
                    </div>
                </div>
            </div>
            <div class="contact-form-wrapper" data-aos="fade-left">
                <?php if(isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
                
                <form action="actions/contact_action.php" method="POST" id="contactForm" class="contact-form">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                    <div class="form-group">
                        <input type="text" name="name" id="name" maxlength="100" autocomplete="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" maxlength="100" autocomplete="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" id="subject" maxlength="200" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" id="message" maxlength="5000" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" name="submit_contact" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>