<?php
return array(
    'app' => array(
        'name' => 'MESUK',
        'management_system' => 'Management System',
        'version' => 'v1.0',
        'copyright' => 'All rights reserved'
    ),
    'navbar' => array(
        'users' => 'Users',
        'posts' => 'Posts',
        'active_users' => 'Active Users',
        'days' => 'Days',
        'profile' => 'Profile',
        'settings' => 'Settings',
        'logout' => 'Logout'
    ),
    'sidebar' => array(
        'dashboard' => 'Dashboard',
        'settings' => 'Settings',
        'general_settings' => 'General Settings',
        'appearance' => 'Appearance',
        'notifications' => 'Notifications',
        'user_management' => 'User Management',
        'import_user_data' => 'Import Users',
        'reports' => 'Reports',
        'sales_report' => 'Sales Report',
        'user_report' => 'User Report',
        'system_logs' => 'System Logs'
    ),
    'mobile' => array(
        'menu' => 'Menu',
        'alerts' => 'Alerts',
        'profile' => 'Profile',
        'notifications' => 'Notifications'
    ),
    'notifications' => array(
        'new_user' => 'New user registered',
        'new_report' => 'New report available',
        'system_updated' => 'System updated'
    ),

    'import_users' => array(
        'title' => 'Import Users',
        'internal_db' => 'Internal Database',
        'external_db' => 'External Database',
        'member_code' => 'Member Code',
        'fullname_th' => 'Full Name (Thai)',
        'fullname_en' => 'Full Name (English)',
        'phone' => 'Phone',
        'status' => 'Status',
        'import_selected' => 'Import Selected Users',
        'waiting' => 'Pending Import',
        'in_system' => 'Already in System',
        'search_member' => 'Search Member Code (Mcode)',
        'placeholder_search' => 'Type member code...',
        'select_users_alert' => 'Please select users to import',
        'confirm_import' => 'Do you want to import {count} selected users?',
        'yes_import' => 'Yes, import',
        'cancel' => 'Cancel',
        'import_success' => '{count} users imported successfully',
        'import_error' => 'Import error',
        'import_error_connection' => 'Connection error',
    ),
    
    'user_management' => array(
        'title' => 'User Management',
        'add_user' => 'Add User',
        'edit_user' => 'Edit User',
        'delete_user' => 'Delete User',
        'user_list' => 'User List',
        'search' => 'Search',
        'user_code' => 'User Code',
        'username' => 'Username',
        'password' => 'Password',
        'full_name' => 'Full Name',
        'phone' => 'Phone',
        'birthday' => 'Birthday',
        'facebook' => 'Facebook',
        'line_id' => 'Line ID',
        'image' => 'Image',
        'status' => 'Status',
        'role' => 'Role',
        'active' => 'Active',
        'suspended' => 'Suspended',
        'admin' => 'Admin',
        'agent' => 'Agent',
        'user' => 'User',
        'created_date' => 'Created Date',
        'actions' => 'Actions',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'change_status' => 'Change Status',
        
        // Stats
        'total_users' => 'Total Users',
        'active_users' => 'Active',
        'suspended_users' => 'Suspended',
        'admin_count' => 'Admin',
        'agent_count' => 'Agent',
        'user_count' => 'User',
        
        // Form
        'required_field' => 'Required Field',
        'user_code_placeholder' => 'User ID Code',
        'username_placeholder' => 'Username for login',
        'password_placeholder' => 'Password (minimum 3 characters)',
        'password_optional' => 'Leave blank if you don\'t want to change password',
        'upload_image' => 'Upload Image',
        'image_help' => 'Supports JPG, PNG, GIF (max 5MB)',
        'image_preview' => 'Image Preview',
        
        // Messages
        'add_success' => 'User added successfully',
        'update_success' => 'User updated successfully',
        'delete_success' => 'User deleted successfully',
        'status_change_success' => 'Status changed successfully',
        'user_not_found' => 'User not found',
        'cannot_delete_self' => 'Cannot delete your own account',
        'cannot_change_self_status' => 'Cannot change your own status',
        'access_denied' => 'Access Denied: Admin only',
        
        // Validation
        'code_required' => 'Please enter user code',
        'code_exists' => 'This user code already exists',
        'username_required' => 'Please enter username',
        'username_exists' => 'This username already exists',
        'password_required' => 'Please enter password',
        'password_min_length' => 'Password must be at least 3 characters',
        'image_upload_failed' => 'Image upload failed',
        'operation_failed' => 'Operation failed',
        
        // Confirmations
        'confirm_delete_title' => 'Confirm Delete?',
        'confirm_delete_text' => 'Do you want to delete user "{name}"? This action cannot be undone',
        'confirm_status_title' => 'Confirm Status Change?',
        'confirm_status_text' => 'Do you want to change this user\'s status?',
        'yes' => 'Yes',
        'no' => 'No',
        'confirm' => 'Confirm',
    ),

    'selection' => array(
        'all' => 'All',

    ),
);
