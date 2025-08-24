<?php
// Business data array - simulates database
$businesses = [
    [
        'id' => 1,
        'name' => 'Snowdonia Adventure Tours',
        'category' => 'Tourism',
        'description' => 'Guided mountain tours and outdoor adventures in Snowdonia National Park.',
        'phone' => '01286 123456',
        'email' => 'info@snowdoniaadventure.co.uk',
        'website' => 'www.snowdoniaadventure.co.uk',
        'location' => 'Llanberis, Gwynedd'
    ],
    [
        'id' => 2,
        'name' => 'Conwy Castle Cafe',
        'category' => 'Hospitality',
        'description' => 'Traditional Welsh cafe serving homemade cakes and local specialties.',
        'phone' => '01492 234567',
        'email' => 'hello@conwycastle.cafe',
        'website' => 'www.conwycastlecafe.co.uk',
        'location' => 'Conwy, North Wales'
    ],
    [
        'id' => 3,
        'name' => 'Anglesey Web Solutions',
        'category' => 'Technology',
        'description' => 'Local web development and digital marketing services for Welsh businesses.',
        'phone' => '01248 345678',
        'email' => 'contact@angleseywebsolutions.com',
        'website' => 'www.angleseywebsolutions.com',
        'location' => 'Menai Bridge, Anglesey'
    ],
    [
        'id' => 4,
        'name' => 'Llandudno Vintage Shop',
        'category' => 'Retail',
        'description' => 'Unique vintage clothing and antiques in the heart of Llandudno.',
        'phone' => '01492 456789',
        'email' => 'info@llandudnovintage.co.uk',
        'website' => 'www.llandudnovintage.co.uk',
        'location' => 'Llandudno, Conwy'
    ],
    [
        'id' => 5,
        'name' => 'Bangor Mountain Bikes',
        'category' => 'Retail',
        'description' => 'Bike sales, repairs and mountain bike rentals for all skill levels.',
        'phone' => '01248 567890',
        'email' => 'shop@bangormountainbikes.com',
        'website' => 'www.bangormountainbikes.com',
        'location' => 'Bangor, Gwynedd'
    ],
    [
        'id' => 6,
        'name' => 'Pwllheli Fresh Fish',
        'category' => 'Food',
        'description' => 'Daily fresh catch from local boats, wholesale and retail.',
        'phone' => '01758 678901',
        'email' => 'orders@pwllhelifreshfish.co.uk',
        'website' => 'www.pwllhelifreshfish.co.uk',
        'location' => 'Pwllheli, Gwynedd'
    ]
];

// Get all unique categories for filter dropdown
$categories = array_unique(array_column($businesses, 'category'));
sort($categories);

// Handle search functionality
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Filter businesses based on search criteria
$filteredBusinesses = $businesses;

if (!empty($searchTerm)) {
    $filteredBusinesses = array_filter($filteredBusinesses, function($business) use ($searchTerm) {
        return stripos($business['name'], $searchTerm) !== false || 
               stripos($business['description'], $searchTerm) !== false ||
               stripos($business['location'], $searchTerm) !== false;
    });
}

if (!empty($selectedCategory)) {
    $filteredBusinesses = array_filter($filteredBusinesses, function($business) use ($selectedCategory) {
        return $business['category'] === $selectedCategory;
    });
}

// Handle submit business form
if ($_POST && isset($_POST['submit_business'])) {
    $businessName = trim($_POST['business_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $location = trim($_POST['location'] ?? '');

    if (empty($businessName) || empty($category) || empty($description) || empty($phone) || empty($email) || empty($location)) {
        $contactMessage = '<div class="alert error">Please fill in all required fields.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contactMessage = '<div class="alert error">Please enter a valid email address.</div>';
    } else {
        // Simulate adding the business to the database (or array in this case)
        $newBusiness = [
            'id' => count($businesses) + 1,
            'name' => $businessName,
            'category' => $category,
            'description' => $description,
            'phone' => $phone,
            'email' => $email,
            'website' => $website,
            'location' => $location
        ];
        $businesses[] = $newBusiness;

        $contactMessage = '<div class="alert success">Thank you for submitting your business! It has been added to the directory.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>North Wales Business Directory</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>North Wales Business Directory</h1>
            <p>Discover amazing local businesses across Gwynedd, Conwy & Anglesey</p>
        </div>
    </header>

    <section class="search-section">
        <div class="container">
            <form class="search-form" method="GET">
                <input type="text" name="search" placeholder="Search businesses..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $selectedCategory === $category ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
    </section>

    <main class="container">
        <div class="results-info">
            <p><?php echo count($filteredBusinesses); ?> businesses found
            <?php if($searchTerm || $selectedCategory): ?>
                - <a href="index.php">Clear filters</a>
            <?php endif; ?>
            </p>
        </div>

        <div class="business-grid">
            <?php if(empty($filteredBusinesses)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <h3>No businesses found</h3>
                    <p>Try adjusting your search criteria</p>
                </div>
            <?php else: ?>
                <?php foreach($filteredBusinesses as $business): ?>
                <div class="business-card">
                    <h3 class="business-name"><?php echo htmlspecialchars($business['name']); ?></h3>
                    <span class="business-category"><?php echo htmlspecialchars($business['category']); ?></span>
                    <p class="business-description"><?php echo htmlspecialchars($business['description']); ?></p>
                    
                    <div class="business-details">
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($business['location']); ?></p>
                        <p><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($business['phone']); ?>"><?php echo htmlspecialchars($business['phone']); ?></a></p>
                        <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($business['email']); ?>"><?php echo htmlspecialchars($business['email']); ?></a></p>
                        <p><strong>Website:</strong> <a href="https://<?php echo htmlspecialchars($business['website']); ?>" target="_blank"><?php echo htmlspecialchars($business['website']); ?></a></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form class="submit-business-form" method="POST">
        <?php if (isset($contactMessage)) echo $contactMessage; ?>
        <h2>Submit a Business</h2>
        <div class="form-group">
            <label for="business_name">Business Name *</label>
            <input type="text" id="business_name" name="business_name" required>
        </div>
        <div class="form-group">
            <label for="category">Category *</label>
            <input type="text" id="category" name="category" required>
        </div>
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="url" id="website" name="website">
        </div>
        <div class="form-group">
            <label for="location">Location *</label>
            <input type="text" id="location" name="location" required>
        </div>
        <button type="submit" name="submit_business">Submit Business</button>
</form>
    </main>

    <footer>
        <div class="container">
            <p>&copy; North Wales Business Directory</p>
        </div>
    </footer>
</body>
</html>
