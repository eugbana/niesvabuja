<?php
/**
 * Member Area Import/Export Functionality
 * Handles CSV import and export for member data
 *
 * @package NIESV_Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class NIESV_Member_Import_Export {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_niesv_export_members', array($this, 'export_members'));
        add_action('wp_ajax_niesv_import_members', array($this, 'import_members'));
        add_action('admin_post_niesv_download_sample_csv', array($this, 'download_sample_csv'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=member_area',
            'Import/Export Members',
            'Import/Export',
            'manage_options',
            'member-import-export',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Import/Export Members</h1>
            
            <div class="export-import-container">
                <!-- Export Section -->
                <div class="export-section">
                    <h2>Export Members</h2>
                    <p>Export all member data to a CSV file for backup or external use.</p>
                    
                    <form method="post" id="export-form">
                        <?php wp_nonce_field('niesv_export_members', 'export_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">Export Options</th>
                                <td>
                                    <fieldset>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="membership_no" checked disabled>
                                            Membership Number (Required)
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="initials" checked>
                                            Initials
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="full_name" checked disabled>
                                            Full Name (Required)
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="email_address" checked disabled>
                                            Email Address (Required)
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="telephone" checked>
                                            Telephone
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="category" checked>
                                            Category
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="branch" checked>
                                            Branch
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="membership_status" checked>
                                            Status
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="export_fields[]" value="date_joined" checked>
                                            Date Joined
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Filter by Status</th>
                                <td>
                                    <select name="export_status">
                                        <option value="">All Statuses</option>
                                        <option value="active">Active Only</option>
                                        <option value="inactive">Inactive Only</option>
                                        <option value="suspended">Suspended Only</option>
                                        <option value="pending">Pending Only</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Filter by Category</th>
                                <td>
                                    <select name="export_category">
                                        <option value="">All Categories</option>
                                        <option value="fellow">Fellow (FNIVS)</option>
                                        <option value="associate">Associate (ANIVS)</option>
                                        <option value="graduate">Graduate (GNIVS)</option>
                                        <option value="student">Student Member</option>
                                        <option value="corporate">Corporate Member</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="button" id="export-members" class="button button-primary">
                                Export Members to CSV
                            </button>
                        </p>
                    </form>
                </div>
                
                <hr>
                
                <!-- Import Section -->
                <div class="import-section">
                    <h2>Import Members</h2>
                    <p>Import member data from a CSV file. <strong>Note:</strong> This will add new members or update existing ones based on membership number.</p>
                    
                    <div class="import-instructions">
                        <h3>CSV Format Requirements:</h3>
                        <ul>
                            <li>First row must contain column headers</li>
                            <li>Required columns: <code>membership_no</code>, <code>full_name</code>, <code>email_address</code></li>
                            <li>Optional columns: <code>initials</code>, <code>telephone</code>, <code>category</code>, <code>branch</code>, <code>membership_status</code>, <code>date_joined</code></li>
                            <li>Date format should be YYYY-MM-DD</li>
                            <li>Category values: fellow, associate, graduate, student, corporate</li>
                            <li>Status values: active, inactive, suspended, pending</li>
                        </ul>
                        
                        <p>
                            <a href="<?php echo admin_url('admin-post.php?action=niesv_download_sample_csv'); ?>" class="button">
                                Download Sample CSV Template
                            </a>
                        </p>
                    </div>
                    
                    <form method="post" enctype="multipart/form-data" id="import-form">
                        <?php wp_nonce_field('niesv_import_members', 'import_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">CSV File</th>
                                <td>
                                    <input type="file" name="import_file" accept=".csv" required>
                                    <p class="description">Select a CSV file to import. Maximum file size: <?php echo wp_max_upload_size() / (1024 * 1024); ?>MB</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Import Options</th>
                                <td>
                                    <fieldset>
                                        <label>
                                            <input type="checkbox" name="update_existing" value="1" checked>
                                            Update existing members (based on membership number)
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="skip_duplicates" value="1">
                                            Skip duplicate membership numbers (don't update)
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="validate_email" value="1" checked>
                                            Validate email addresses
                                        </label><br>
                                        <label>
                                            <input type="checkbox" name="send_notifications" value="1">
                                            Send email notifications to new members
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" id="import-members" class="button button-primary">
                                Import Members from CSV
                            </button>
                        </p>
                    </form>
                    
                    <!-- Import Progress -->
                    <div id="import-progress" style="display: none;">
                        <h3>Import Progress</h3>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%;"></div>
                        </div>
                        <div id="import-status">Preparing import...</div>
                        <div id="import-results"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .export-import-container {
            max-width: 800px;
        }
        
        .export-section, .import-section {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .import-instructions {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #0073aa;
            margin: 20px 0;
        }
        
        .import-instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .import-instructions code {
            background: #f1f1f1;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: #0073aa;
            transition: width 0.3s ease;
        }
        
        #import-status {
            font-weight: bold;
            margin: 10px 0;
        }
        
        #import-results {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 3px;
            display: none;
        }
        
        .import-success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        
        .import-error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        
        .import-warning {
            color: #856404;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Export functionality
            $('#export-members').on('click', function(e) {
                e.preventDefault();
                
                var formData = $('#export-form').serialize();
                var $button = $(this);
                
                $button.prop('disabled', true).text('Exporting...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData + '&action=niesv_export_members',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        var filename = xhr.getResponseHeader('Content-Disposition');
                        if (filename) {
                            filename = filename.split('filename=')[1].replace(/"/g, '');
                        } else {
                            filename = 'members_export_' + new Date().toISOString().slice(0,10) + '.csv';
                        }
                        
                        var blob = new Blob([data], { type: 'text/csv' });
                        var url = window.URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    },
                    error: function() {
                        alert('Export failed. Please try again.');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('Export Members to CSV');
                    }
                });
            });
            
            // Import functionality
            $('#import-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                formData.append('action', 'niesv_import_members');
                
                $('#import-progress').show();
                $('#import-members').prop('disabled', true).text('Importing...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#import-status').text('Import completed successfully!');
                            $('#import-results').html(response.data.message).addClass('import-success').show();
                            $('.progress-fill').css('width', '100%');
                        } else {
                            $('#import-status').text('Import failed');
                            $('#import-results').html(response.data).addClass('import-error').show();
                        }
                    },
                    error: function() {
                        $('#import-status').text('Import failed');
                        $('#import-results').html('An error occurred during import.').addClass('import-error').show();
                    },
                    complete: function() {
                        $('#import-members').prop('disabled', false).text('Import Members from CSV');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Export members to CSV
     */
    public function export_members() {
        check_ajax_referer('niesv_export_members', 'export_nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $export_fields = isset($_POST['export_fields']) ? $_POST['export_fields'] : array();
        $status_filter = sanitize_text_field($_POST['export_status'] ?? '');
        $category_filter = sanitize_text_field($_POST['export_category'] ?? '');

        // Always include required fields
        $required_fields = array('membership_no', 'full_name', 'email_address');
        $export_fields = array_merge($required_fields, $export_fields);
        $export_fields = array_unique($export_fields);

        // Build query
        $args = array(
            'post_type' => 'member_area',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        );

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
            $args['meta_query'] = $meta_query;
        }

        $members = get_posts($args);

        // Prepare CSV data
        $csv_data = array();

        // Header row
        $headers = array();
        $field_labels = array(
            'membership_no' => 'MembershipNo',
            'initials' => 'Initials',
            'full_name' => 'FullName',
            'email_address' => 'EmailAddress',
            'telephone' => 'Telephone',
            'category' => 'Category',
            'branch' => 'Branch',
            'membership_status' => 'Status',
            'date_joined' => 'Date Joined',
        );

        foreach ($export_fields as $field) {
            $headers[] = $field_labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
        }
        $csv_data[] = $headers;

        // Data rows
        foreach ($members as $member) {
            $row = array();
            foreach ($export_fields as $field) {
                $value = get_field($field, $member->ID);

                // Format specific fields
                if ($field === 'category') {
                    $category_labels = array(
                        'fellow' => 'Fellow (FNIVS)',
                        'associate' => 'Associate (ANIVS)',
                        'graduate' => 'Graduate (GNIVS)',
                        'student' => 'Student Member',
                        'corporate' => 'Corporate Member',
                    );
                    $value = $category_labels[$value] ?? ucfirst($value);
                } elseif ($field === 'branch') {
                    $value = ucfirst(str_replace('_', ' ', $value));
                } elseif ($field === 'membership_status') {
                    $value = ucfirst($value);
                }

                $row[] = $value;
            }
            $csv_data[] = $row;
        }

        // Generate CSV
        $filename = 'members_export_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        foreach ($csv_data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Import members from CSV
     */
    public function import_members() {
        check_ajax_referer('niesv_import_members', 'import_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error('No file uploaded or upload error occurred');
        }

        $file = $_FILES['import_file'];

        // Validate file type
        $file_info = pathinfo($file['name']);
        if (strtolower($file_info['extension']) !== 'csv') {
            wp_send_json_error('Please upload a CSV file');
        }

        // Read CSV file
        $csv_data = array();
        if (($handle = fopen($file['tmp_name'], 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $csv_data[] = $data;
            }
            fclose($handle);
        } else {
            wp_send_json_error('Could not read CSV file');
        }

        if (empty($csv_data)) {
            wp_send_json_error('CSV file is empty');
        }

        // Get headers and normalize them
        $original_headers = array_map('trim', $csv_data[0]);
        $headers = array_map('strtolower', $original_headers);

        // Create a mapping of possible column name variations
        $column_mapping = array(
            'membership_no' => array('membershipno', 'membership_no', 'membership_number', 'membership number', 'member_no', 'member_number'),
            'full_name' => array('fullname', 'full_name', 'name', 'full name', 'member_name'),
            'email_address' => array('emailaddress', 'email_address', 'email', 'email address'),
            'initials' => array('initials', 'initial'),
            'telephone' => array('telephone', 'phone', 'phone_number', 'phone number'),
            'category' => array('category', 'membership_category', 'member_category', 'type'),
            'branch' => array('branch', 'location'),
            'membership_status' => array('membership_status', 'status', 'member_status'),
            'date_joined' => array('date_joined', 'join_date', 'date joined', 'registration_date')
        );

        // Find the actual column indices for our required fields
        $field_indices = array();
        $required_fields = array('membership_no', 'full_name', 'email_address');

        foreach ($column_mapping as $field => $variations) {
            $found_index = false;
            foreach ($variations as $variation) {
                $index = array_search($variation, $headers);
                if ($index !== false) {
                    $field_indices[$field] = $index;
                    $found_index = true;
                    break;
                }
            }

            // Check if this is a required field and wasn't found
            if (!$found_index && in_array($field, $required_fields)) {
                $variations_list = implode(', ', $column_mapping[$field]);
                wp_send_json_error("Missing required column for {$field}. Expected one of: {$variations_list}. Found columns: " . implode(', ', $original_headers));
            }
        }

        // Process import options
        $update_existing = isset($_POST['update_existing']) && $_POST['update_existing'] === '1';
        $skip_duplicates = isset($_POST['skip_duplicates']) && $_POST['skip_duplicates'] === '1';
        $validate_email = isset($_POST['validate_email']) && $_POST['validate_email'] === '1';
        $send_notifications = isset($_POST['send_notifications']) && $_POST['send_notifications'] === '1';

        // Process data
        $results = array(
            'total' => count($csv_data) - 1, // Exclude header row
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => array(),
        );

        // Remove header row
        array_shift($csv_data);

        foreach ($csv_data as $row_index => $row) {
            $row_number = $row_index + 2; // +2 because we removed header and arrays are 0-indexed

            // Map row data to fields using the field indices we found
            $member_data = array();
            foreach ($field_indices as $field => $index) {
                $member_data[$field] = isset($row[$index]) ? trim($row[$index]) : '';
            }

            // Validate required fields
            $validation_errors = array();
            foreach ($required_fields as $required) {
                if (empty($member_data[$required])) {
                    $validation_errors[] = "Missing {$required}";
                }
            }

            // Validate email if option is enabled
            if ($validate_email && !empty($member_data['email_address']) && !is_email($member_data['email_address'])) {
                $validation_errors[] = "Invalid email address";
            }

            if (!empty($validation_errors)) {
                $results['errors'][] = "Row {$row_number}: " . implode(', ', $validation_errors);
                $results['skipped']++;
                continue;
            }

            // Check if member already exists
            $existing_member = get_posts(array(
                'post_type' => 'member_area',
                'meta_query' => array(
                    array(
                        'key' => 'membership_no',
                        'value' => $member_data['membership_no'],
                        'compare' => '='
                    )
                ),
                'posts_per_page' => 1
            ));

            $member_exists = !empty($existing_member);

            if ($member_exists && $skip_duplicates) {
                $results['skipped']++;
                continue;
            }

            if ($member_exists && !$update_existing) {
                $results['errors'][] = "Row {$row_number}: Member with membership number {$member_data['membership_no']} already exists";
                $results['skipped']++;
                continue;
            }

            // Create or update member
            if ($member_exists) {
                $post_id = $existing_member[0]->ID;
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $member_data['full_name'],
                ));
                $results['updated']++;
            } else {
                $post_id = wp_insert_post(array(
                    'post_title' => $member_data['full_name'],
                    'post_type' => 'member_area',
                    'post_status' => 'publish',
                ));

                if (is_wp_error($post_id)) {
                    $results['errors'][] = "Row {$row_number}: Failed to create member record";
                    $results['skipped']++;
                    continue;
                }

                $results['imported']++;
            }

            // Update custom fields
            $field_mapping = array(
                'membership_no' => 'membership_no',
                'initials' => 'initials',
                'full_name' => 'full_name',
                'email_address' => 'email_address',
                'telephone' => 'telephone',
                'category' => 'category',
                'branch' => 'branch',
                'membership_status' => 'membership_status',
                'date_joined' => 'date_joined',
            );

            foreach ($field_mapping as $csv_field => $acf_field) {
                if (isset($member_data[$csv_field]) && !empty($member_data[$csv_field])) {
                    $value = $member_data[$csv_field];

                    // Validate and normalize specific fields
                    if ($csv_field === 'category') {
                        $valid_categories = array('fellow', 'associate', 'graduate', 'student', 'corporate');
                        $value = strtolower($value);
                        if (!in_array($value, $valid_categories)) {
                            $results['errors'][] = "Row {$row_number}: Invalid category '{$member_data[$csv_field]}'";
                            continue;
                        }
                    } elseif ($csv_field === 'membership_status') {
                        $valid_statuses = array('active', 'inactive', 'suspended', 'pending');
                        $value = strtolower($value);
                        if (!in_array($value, $valid_statuses)) {
                            $value = 'active'; // Default to active if invalid
                        }
                    } elseif ($csv_field === 'date_joined') {
                        // Validate date format
                        $date = DateTime::createFromFormat('Y-m-d', $value);
                        if (!$date || $date->format('Y-m-d') !== $value) {
                            $results['errors'][] = "Row {$row_number}: Invalid date format for date_joined (use YYYY-MM-DD)";
                            continue;
                        }
                    }

                    update_field($acf_field, $value, $post_id);
                }
            }

            // Send notification email if enabled and this is a new member
            if ($send_notifications && !$member_exists && !empty($member_data['email_address'])) {
                $this->send_welcome_email($member_data['email_address'], $member_data['full_name'], $member_data['membership_no']);
            }
        }

        // Prepare response message
        $message = "<strong>Import Summary:</strong><br>";
        $message .= "Total rows processed: {$results['total']}<br>";
        $message .= "New members imported: {$results['imported']}<br>";
        $message .= "Existing members updated: {$results['updated']}<br>";
        $message .= "Rows skipped: {$results['skipped']}<br>";

        if (!empty($results['errors'])) {
            $message .= "<br><strong>Errors:</strong><br>";
            $message .= implode('<br>', array_slice($results['errors'], 0, 10)); // Show first 10 errors
            if (count($results['errors']) > 10) {
                $message .= "<br>... and " . (count($results['errors']) - 10) . " more errors.";
            }
        }

        wp_send_json_success(array('message' => $message, 'results' => $results));
    }

    /**
     * Download sample CSV template
     */
    public function download_sample_csv() {
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $sample_data = array(
            array(
                'MembershipNo',
                'Initials',
                'FullName',
                'EmailAddress',
                'Telephone',
                'Category',
                'Branch'
            ),
            array(
                'NIESV001',
                'J.D.',
                'John Doe',
                'john.doe@example.com',
                '+234 801 234 5678',
                'associate',
                'abuja'
            ),
            array(
                'NIESV002',
                'M.S.',
                'Mary Smith',
                'mary.smith@example.com',
                '+234 802 345 6789',
                'fellow',
                'lagos'
            ),
        );

        $filename = 'member_import_template.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        foreach ($sample_data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Send welcome email to new member
     */
    private function send_welcome_email($email, $name, $membership_no) {
        $subject = 'Welcome to NIESV Abuja Branch';

        $message = "Dear {$name},\n\n";
        $message .= "Welcome to the Nigerian Institution of Estate Surveyors and Valuers (NIESV) Abuja Branch!\n\n";
        $message .= "Your membership details:\n";
        $message .= "Membership Number: {$membership_no}\n";
        $message .= "Name: {$name}\n";
        $message .= "Email: {$email}\n\n";
        $message .= "You can now access our member portal and property management system.\n\n";
        $message .= "If you have any questions, please don't hesitate to contact us.\n\n";
        $message .= "Best regards,\n";
        $message .= "NIESV Abuja Branch";

        $headers = array('Content-Type: text/plain; charset=UTF-8');

        wp_mail($email, $subject, $message, $headers);
    }
}

// Initialize the import/export class
new NIESV_Member_Import_Export();
