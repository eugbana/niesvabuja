<?php
/**
 * Template Name: Register Page
 * Custom registration page template
 *
 * @package NIESV_Theme
 */

// Redirect if already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/property-dashboard/'));
    exit;
}

// Handle registration form submission
if (isset($_POST['register_submit'])) {
    $username = sanitize_text_field(wp_unslash($_POST['username'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    // Passwords stay slashed, matching how WordPress core hashes and checks them
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = sanitize_text_field(wp_unslash($_POST['first_name'] ?? ''));
    $last_name = sanitize_text_field(wp_unslash($_POST['last_name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $membership_no = sanitize_text_field(wp_unslash($_POST['membership_no'] ?? ''));
    $confirm_membership_no = sanitize_text_field(wp_unslash($_POST['confirm_membership_no'] ?? ''));

    $errors = array();

    if (!isset($_POST['niesv_register_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['niesv_register_nonce'])), 'niesv_register')) {
        $errors[] = 'Your session has expired. Please refresh the page and try again.';
    }

    // Honeypot: hidden from people, bots tend to fill it in
    if (!empty($_POST['website'])) {
        $errors[] = 'Registration could not be completed.';
    }

    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (username_exists($username)) {
        $errors[] = 'Username already exists.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!is_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (email_exists($email)) {
        $errors[] = 'Email already exists.';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    
    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Last name is required.';
    }

    // Validate membership number
    if (empty($membership_no)) {
        $errors[] = 'Membership number is required.';
    }

    if (empty($confirm_membership_no)) {
        $errors[] = 'Please confirm your membership number.';
    }

    if ($membership_no !== $confirm_membership_no) {
        $errors[] = 'Membership numbers do not match.';
    }

    // Verify membership number exists in member area
    $member_verification = null;
    if (!empty($membership_no) && $membership_no === $confirm_membership_no) {
        $member_query = get_posts(array(
            'post_type' => 'member_area',
            'meta_query' => array(
                array(
                    'key' => 'membership_no',
                    'value' => $membership_no,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        if (!empty($member_query)) {
            $member_verification = $member_query[0];
            $member_status = get_field('membership_status', $member_verification->ID);

            // Check if membership is active
            if ($member_status !== 'active') {
                $errors[] = 'This membership number is not active. Please contact NIESV Abuja Branch for assistance.';
            }

            // The email must match the one on the member record
            $member_email = get_field('email_address', $member_verification->ID);
            if (empty($member_email) || strtolower($member_email) !== strtolower($email)) {
                $errors[] = 'The email address does not match our records for this membership number.';
            }
        } else {
            $errors[] = 'Membership number not found in our records. Please verify your membership number or contact NIESV Abuja Branch.';
        }
    }

    // Each membership number can only be linked to one website account
    if (!empty($membership_no) && get_users(array(
        'meta_key'   => 'membership_no',
        'meta_value' => $membership_no,
        'number'     => 1,
        'fields'     => 'ID',
    ))) {
        $errors[] = 'An account already exists for this membership number. Please log in, or contact NIESV Abuja Branch if you did not create it.';
    }

    // If no errors, create user
    if (empty($errors)) {
        $user_id = wp_create_user($username, $password, $email);
        
        if (!is_wp_error($user_id)) {
            // Update user meta
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'display_name' => $first_name . ' ' . $last_name
            ));
            
            // Add phone number
            if (!empty($phone)) {
                update_user_meta($user_id, 'phone', $phone);
            }

            // Add membership number and verification status
            if (!empty($membership_no)) {
                update_user_meta($user_id, 'membership_no', $membership_no);

                // Set verification status based on member area lookup
                if ($member_verification) {
                    update_user_meta($user_id, 'membership_verified', true);
                    update_user_meta($user_id, 'member_area_id', $member_verification->ID);

                    // Copy additional member information
                    $member_category = get_field('category', $member_verification->ID);
                    $member_branch = get_field('branch', $member_verification->ID);
                    $member_initials = get_field('initials', $member_verification->ID);

                    if ($member_category) {
                        update_user_meta($user_id, 'member_category', $member_category);
                    }
                    if ($member_branch) {
                        update_user_meta($user_id, 'member_branch', $member_branch);
                    }
                    if ($member_initials) {
                        update_user_meta($user_id, 'member_initials', $member_initials);
                    }
                } else {
                    update_user_meta($user_id, 'membership_verified', false);
                }
            }

            // Auto login user
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            
            // Redirect to appropriate dashboard
            if ($member_verification) {
                wp_redirect(home_url('/member-dashboard/'));
            } else {
                wp_redirect(home_url('/property-dashboard/'));
            }
            exit;
        } else {
            $errors[] = $user_id->get_error_message();
        }
    }
}

get_header(); ?>

<main class="flex-1 bg-gray-50 py-12">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
                <p class="text-gray-600 mt-2">Join NIESV Abuja to manage your properties</p>
            </div>
            
            <!-- Error Messages -->
            <?php if (isset($errors) && !empty($errors)) : ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Registration Form -->
            <form method="post" action="">
                <?php wp_nonce_field('niesv_register', 'niesv_register_nonce'); ?>
                <div style="position:absolute;left:-10000px" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name *
                            </label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                   value="<?php echo isset($_POST['first_name']) ? esc_attr($_POST['first_name']) : ''; ?>">
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name *
                            </label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                   value="<?php echo isset($_POST['last_name']) ? esc_attr($_POST['last_name']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username *
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="+234 801 234 5678"
                               value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>">
                    </div>

                    <div>
                        <label for="membership_no" class="block text-sm font-medium text-gray-700 mb-2">
                            NIESV Membership Number *
                        </label>
                        <input type="text"
                               id="membership_no"
                               name="membership_no"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="e.g., M0001"
                               value="<?php echo isset($_POST['membership_no']) ? esc_attr($_POST['membership_no']) : ''; ?>">
                        <p class="mt-1 text-sm text-gray-500">Enter your NIESV membership number</p>
                    </div>

                    <div>
                        <label for="confirm_membership_no" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Membership Number *
                        </label>
                        <input type="text"
                               id="confirm_membership_no"
                               name="confirm_membership_no"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="Re-enter your membership number"
                               value="<?php echo isset($_POST['confirm_membership_no']) ? esc_attr($_POST['confirm_membership_no']) : ''; ?>">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="At least 6 characters">
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password *
                        </label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="Confirm your password">
                    </div>
                    
                    <button type="submit" 
                            name="register_submit"
                            class="w-full bg-[var(--primary-color)] text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition-colors font-medium">
                        Create Account
                    </button>
                </div>
            </form>
            
            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="<?php echo home_url('/login/'); ?>" class="text-[var(--primary-color)] hover:underline font-medium">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
