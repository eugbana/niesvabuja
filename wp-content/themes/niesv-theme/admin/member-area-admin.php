<?php
/**
 * Member Area Admin Interface
 * Custom admin page for managing member area records
 *
 * @package NIESV_Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class NIESV_Member_Area_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_niesv_search_members', array($this, 'ajax_search_members'));
        add_action('wp_ajax_niesv_delete_member', array($this, 'ajax_delete_member'));
        add_action('wp_ajax_niesv_bulk_action_members', array($this, 'ajax_bulk_action_members'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=member_area',
            'Member Management',
            'Member Management',
            'manage_options',
            'member-area-management',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'member-area-management') !== false) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style('wp-jquery-ui-dialog');
            
            wp_enqueue_script(
                'niesv-member-admin',
                get_template_directory_uri() . '/assets/js/member-admin.js',
                array('jquery', 'jquery-ui-dialog'),
                '1.0.0',
                true
            );
            
            wp_localize_script('niesv-member-admin', 'niesvMemberAdmin', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('niesv_member_admin_nonce'),
                'confirmDelete' => __('Are you sure you want to delete this member?', 'niesv-theme'),
                'confirmBulkDelete' => __('Are you sure you want to delete the selected members?', 'niesv-theme'),
            ));
            
            wp_enqueue_style(
                'niesv-member-admin',
                get_template_directory_uri() . '/assets/css/member-admin.css',
                array(),
                '1.0.0'
            );
        }
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        // Handle form submissions
        if (isset($_POST['action']) && $_POST['action'] === 'add_member') {
            $this->handle_add_member();
        }
        
        // Get members with pagination
        $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
        $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        $category_filter = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        
        $members_query = $this->get_members_query($paged, $search, $status_filter, $category_filter);
        $members = $members_query->posts;
        $total_pages = $members_query->max_num_pages;
        
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Member Area Management</h1>
            <a href="#" class="page-title-action" id="add-new-member">Add New Member</a>
            <hr class="wp-header-end">
            
            <!-- Search and Filters -->
            <div class="tablenav top">
                <div class="alignleft actions">
                    <form method="get" id="member-search-form">
                        <input type="hidden" name="post_type" value="member_area">
                        <input type="hidden" name="page" value="member-area-management">
                        
                        <input type="search" name="search" value="<?php echo esc_attr($search); ?>" placeholder="Search members..." class="search-input">
                        
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="active" <?php selected($status_filter, 'active'); ?>>Active</option>
                            <option value="inactive" <?php selected($status_filter, 'inactive'); ?>>Inactive</option>
                            <option value="suspended" <?php selected($status_filter, 'suspended'); ?>>Suspended</option>
                            <option value="pending" <?php selected($status_filter, 'pending'); ?>>Pending</option>
                        </select>
                        
                        <select name="category">
                            <option value="">All Categories</option>
                            <option value="fellow" <?php selected($category_filter, 'fellow'); ?>>Fellow (FNIVS)</option>
                            <option value="associate" <?php selected($category_filter, 'associate'); ?>>Associate (ANIVS)</option>
                            <option value="graduate" <?php selected($category_filter, 'graduate'); ?>>Graduate (GNIVS)</option>
                            <option value="student" <?php selected($category_filter, 'student'); ?>>Student Member</option>
                            <option value="corporate" <?php selected($category_filter, 'corporate'); ?>>Corporate Member</option>
                        </select>
                        
                        <input type="submit" class="button" value="Filter">
                        <?php if ($search || $status_filter || $category_filter): ?>
                            <a href="<?php echo admin_url('edit.php?post_type=member_area&page=member-area-management'); ?>" class="button">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <div class="alignleft actions">
                    <select name="bulk-action" id="bulk-action-selector-top">
                        <option value="-1">Bulk Actions</option>
                        <option value="delete">Delete</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="suspend">Suspend</option>
                    </select>
                    <input type="submit" id="doaction" class="button action" value="Apply">
                </div>
                
                <div class="tablenav-pages">
                    <?php if ($total_pages > 1): ?>
                        <?php
                        $page_links = paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $paged,
                            'type' => 'array'
                        ));
                        
                        if ($page_links) {
                            echo '<span class="pagination-links">' . implode('', $page_links) . '</span>';
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Members Table -->
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <td class="manage-column column-cb check-column">
                            <input type="checkbox" id="cb-select-all-1">
                        </td>
                        <th class="manage-column column-membership-no">Membership No</th>
                        <th class="manage-column column-name">Name</th>
                        <th class="manage-column column-email">Email</th>
                        <th class="manage-column column-category">Category</th>
                        <th class="manage-column column-branch">Branch</th>
                        <th class="manage-column column-status">Status</th>
                        <th class="manage-column column-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <?php
                            $membership_no = get_field('membership_no', $member->ID);
                            $full_name = get_field('full_name', $member->ID);
                            $initials = get_field('initials', $member->ID);
                            $email = get_field('email_address', $member->ID);
                            $category = get_field('category', $member->ID);
                            $branch = get_field('branch', $member->ID);
                            $status = get_field('membership_status', $member->ID);
                            
                            $display_name = $full_name;
                            if ($initials) {
                                $display_name = $initials . ' ' . $full_name;
                            }
                            ?>
                            <tr data-member-id="<?php echo $member->ID; ?>">
                                <th class="check-column">
                                    <input type="checkbox" name="member[]" value="<?php echo $member->ID; ?>">
                                </th>
                                <td class="column-membership-no">
                                    <strong><?php echo esc_html($membership_no); ?></strong>
                                </td>
                                <td class="column-name">
                                    <strong>
                                        <a href="<?php echo get_edit_post_link($member->ID); ?>">
                                            <?php echo esc_html($display_name); ?>
                                        </a>
                                    </strong>
                                    <div class="row-actions">
                                        <span class="edit">
                                            <a href="<?php echo get_edit_post_link($member->ID); ?>">Edit</a> |
                                        </span>
                                        <span class="trash">
                                            <a href="#" class="delete-member" data-member-id="<?php echo $member->ID; ?>">Delete</a>
                                        </span>
                                    </div>
                                </td>
                                <td class="column-email"><?php echo esc_html($email); ?></td>
                                <td class="column-category">
                                    <?php
                                    $category_labels = array(
                                        'fellow' => 'Fellow (FNIVS)',
                                        'associate' => 'Associate (ANIVS)',
                                        'graduate' => 'Graduate (GNIVS)',
                                        'student' => 'Student Member',
                                        'corporate' => 'Corporate Member',
                                    );
                                    echo esc_html($category_labels[$category] ?? ($category ? ucfirst($category) : 'N/A'));
                                    ?>
                                </td>
                                <td class="column-branch"><?php echo esc_html($branch ? ucfirst(str_replace('_', ' ', $branch)) : 'N/A'); ?></td>
                                <td class="column-status">
                                    <span class="status-badge status-<?php echo esc_attr($status ?: 'unknown'); ?>">
                                        <?php echo esc_html($status ? ucfirst($status) : 'Unknown'); ?>
                                    </span>
                                </td>
                                <td class="column-actions">
                                    <a href="<?php echo get_edit_post_link($member->ID); ?>" class="button button-small">Edit</a>
                                    <a href="#" class="button button-small delete-member" data-member-id="<?php echo $member->ID; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-items">No members found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Bottom pagination -->
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <?php if ($total_pages > 1): ?>
                        <?php
                        $page_links = paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $paged,
                            'type' => 'array'
                        ));
                        
                        if ($page_links) {
                            echo '<span class="pagination-links">' . implode('', $page_links) . '</span>';
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Add Member Modal -->
        <div id="add-member-modal" title="Add New Member" style="display: none;">
            <form id="add-member-form" method="post">
                <?php wp_nonce_field('niesv_add_member', 'niesv_add_member_nonce'); ?>
                <input type="hidden" name="action" value="add_member">
                
                <table class="form-table">
                    <tr>
                        <th><label for="membership_no">Membership Number *</label></th>
                        <td><input type="text" name="membership_no" id="membership_no" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="initials">Initials</label></th>
                        <td><input type="text" name="initials" id="initials" class="regular-text" placeholder="e.g., J.D."></td>
                    </tr>
                    <tr>
                        <th><label for="full_name">Full Name *</label></th>
                        <td><input type="text" name="full_name" id="full_name" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="email_address">Email Address *</label></th>
                        <td><input type="email" name="email_address" id="email_address" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="telephone">Telephone</label></th>
                        <td><input type="text" name="telephone" id="telephone" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="category">Category *</label></th>
                        <td>
                            <select name="category" id="category" required>
                                <option value="">Select Category</option>
                                <option value="fellow">Fellow (FNIVS)</option>
                                <option value="associate">Associate (ANIVS)</option>
                                <option value="graduate">Graduate (GNIVS)</option>
                                <option value="student">Student Member</option>
                                <option value="corporate">Corporate Member</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="branch">Branch *</label></th>
                        <td>
                            <select name="branch" id="branch" required>
                                <option value="">Select Branch</option>
                                <option value="abuja">Abuja</option>
                                <option value="lagos">Lagos</option>
                                <option value="kano">Kano</option>
                                <option value="port_harcourt">Port Harcourt</option>
                                <option value="enugu">Enugu</option>
                                <option value="ibadan">Ibadan</option>
                                <option value="kaduna">Kaduna</option>
                                <option value="benin">Benin</option>
                                <option value="jos">Jos</option>
                                <option value="maiduguri">Maiduguri</option>
                                <option value="other">Other</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="membership_status">Status</label></th>
                        <td>
                            <select name="membership_status" id="membership_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="pending">Pending</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="date_joined">Date Joined</label></th>
                        <td><input type="date" name="date_joined" id="date_joined" class="regular-text"></td>
                    </tr>
                </table>
            </form>
        </div>
        
        <?php
    }

    /**
     * Get members query with filters
     */
    private function get_members_query($paged = 1, $search = '', $status_filter = '', $category_filter = '') {
        $args = array(
            'post_type' => 'member_area',
            'post_status' => 'publish',
            'posts_per_page' => 20,
            'paged' => $paged,
            'orderby' => 'title',
            'order' => 'ASC',
        );

        // Add search
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'membership_no',
                    'value' => $search,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'full_name',
                    'value' => $search,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'email_address',
                    'value' => $search,
                    'compare' => 'LIKE'
                ),
            );
        }

        // Add filters
        $meta_query = array();

        if (!empty($status_filter)) {
            $meta_query[] = array(
                'key' => 'membership_status',
                'value' => $status_filter,
                'compare' => '='
            );
        }

        if (!empty($category_filter)) {
            $meta_query[] = array(
                'key' => 'category',
                'value' => $category_filter,
                'compare' => '='
            );
        }

        if (!empty($meta_query)) {
            if (isset($args['meta_query'])) {
                $args['meta_query'] = array(
                    'relation' => 'AND',
                    $args['meta_query'],
                    $meta_query
                );
            } else {
                $args['meta_query'] = $meta_query;
            }
        }

        return new WP_Query($args);
    }

    /**
     * Handle add member form submission
     */
    private function handle_add_member() {
        if (!wp_verify_nonce($_POST['niesv_add_member_nonce'] ?? '', 'niesv_add_member')) {
            wp_die('Security check failed');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $membership_no = sanitize_text_field($_POST['membership_no']);
        $full_name = sanitize_text_field($_POST['full_name']);

        // Check if membership number already exists
        $existing = get_posts(array(
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

        if (!empty($existing)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>A member with this membership number already exists.</p></div>';
            });
            return;
        }

        // Create member post
        $post_id = wp_insert_post(array(
            'post_title' => $full_name,
            'post_type' => 'member_area',
            'post_status' => 'publish',
        ));

        if (is_wp_error($post_id)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Failed to create member record.</p></div>';
            });
            return;
        }

        // Save custom fields
        $fields = array(
            'membership_no', 'initials', 'full_name', 'email_address',
            'telephone', 'category', 'branch', 'membership_status', 'date_joined'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_field($field, sanitize_text_field($_POST[$field]), $post_id);
            }
        }

        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>Member added successfully.</p></div>';
        });
    }

    /**
     * AJAX search members
     */
    public function ajax_search_members() {
        check_ajax_referer('niesv_member_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $search = sanitize_text_field($_POST['search']);
        $members_query = $this->get_members_query(1, $search);

        $results = array();
        foreach ($members_query->posts as $member) {
            $results[] = array(
                'id' => $member->ID,
                'membership_no' => get_field('membership_no', $member->ID),
                'full_name' => get_field('full_name', $member->ID),
                'email' => get_field('email_address', $member->ID),
            );
        }

        wp_send_json_success($results);
    }

    /**
     * AJAX delete member
     */
    public function ajax_delete_member() {
        check_ajax_referer('niesv_member_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $member_id = intval($_POST['member_id']);

        if (wp_delete_post($member_id, true)) {
            wp_send_json_success('Member deleted successfully');
        } else {
            wp_send_json_error('Failed to delete member');
        }
    }

    /**
     * AJAX bulk actions
     */
    public function ajax_bulk_action_members() {
        check_ajax_referer('niesv_member_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $action = sanitize_text_field($_POST['bulk_action']);
        $member_ids = array_map('intval', $_POST['member_ids']);

        $success_count = 0;

        foreach ($member_ids as $member_id) {
            switch ($action) {
                case 'delete':
                    if (wp_delete_post($member_id, true)) {
                        $success_count++;
                    }
                    break;

                case 'activate':
                    update_field('membership_status', 'active', $member_id);
                    $success_count++;
                    break;

                case 'deactivate':
                    update_field('membership_status', 'inactive', $member_id);
                    $success_count++;
                    break;

                case 'suspend':
                    update_field('membership_status', 'suspended', $member_id);
                    $success_count++;
                    break;
            }
        }

        wp_send_json_success(array(
            'message' => sprintf('%d members processed successfully', $success_count),
            'count' => $success_count
        ));
    }
}

// Initialize the admin class
new NIESV_Member_Area_Admin();
