<?php
/**
 * Template Name: Login Page
 * Custom login page template
 *
 * @package NIESV_Theme
 */

// Redirect if already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/property-dashboard/'));
    exit;
}

// Handle login form submission
if (isset($_POST['login_submit'])) {
    $nonce = isset($_POST['niesv_login_nonce']) ? sanitize_text_field(wp_unslash($_POST['niesv_login_nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'niesv_login')) {
        $login_error = 'Your session has expired. Please try again.';
    }
}

if (isset($_POST['login_submit']) && empty($login_error)) {
    $username = sanitize_text_field(wp_unslash($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;
    
    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => $remember
    );
    
    $user = wp_signon($creds, is_ssl());
    
    if (is_wp_error($user)) {
        $login_error = $user->get_error_message();
    } else {
        wp_redirect(home_url('/property-dashboard/'));
        exit;
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Sign In</h1>
                <p class="text-gray-600 mt-2">Access your property management dashboard</p>
            </div>
            
            <!-- Error Message -->
            <?php if (isset($login_error)) : ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="post" action="">
                <?php wp_nonce_field('niesv_login', 'niesv_login_nonce'); ?>
                <div class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username or Email
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="Enter your username or email"
                               value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                               placeholder="Enter your password">
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="h-4 w-4 text-[var(--primary-color)] focus:ring-[var(--primary-color)] border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>
                        
                        <a href="<?php echo wp_lostpassword_url(); ?>" class="text-sm text-[var(--primary-color)] hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    
                    <button type="submit" 
                            name="login_submit"
                            class="w-full bg-[var(--primary-color)] text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition-colors font-medium">
                        Sign In
                    </button>
                </div>
            </form>
            
            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="<?php echo home_url('/register/'); ?>" class="text-[var(--primary-color)] hover:underline font-medium">
                        Sign up here
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-blue-800">Member Benefits</h3>
                </div>
                <p class="text-sm text-blue-700">
                    Access your property management dashboard, create and manage property listings, and connect with potential buyers and renters.
                </p>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
