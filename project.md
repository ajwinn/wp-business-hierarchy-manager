# Goal

We're creating a WordPress-native hierarchy or Groups, Roles, and Relationships between Payroll Bureaus and Clients.

Custom Post Types: Uses WordPress's built-in content management for companies
User Meta: Leverages WordPress's user meta system for relationships
Capabilities System: Extends WordPress's permission system naturally
Post Meta: Uses WordPress's flexible meta system for settings and relationships
Hooks & Filters: Integrates with WordPress's action/filter system
Admin Integration: Works seamlessly with WordPress admin screens

## Scenario Specifics

Payroll Bureaus have Clients that send onboarding invitations to new-employees.

A bureau-company is the payroll bureau itself, eg. Payroll Professionals LLC.
A bureau-team-member is a user that belongs to the bureau, eg. David Sterrett is a team member of Payroll Professionals.
A bureau-team-member may have the "primary-role" which grants more permissions.
A client-company is the customer of a bureau-company, eg. Reformation Electric LLC is a client of Payroll Professionals.
A client-team-member is a user of that client-company, eg. Andrew Quick is a team member of Reformation Electric.
A new-employee is an unauthenticated session with a unique code in the URL that represents the invitation and onboarding for a specific new-employee.

### Bureau Company

a bureau-company has bureau-team-members (users).

a bureau-company can send invitations to client email addresses.

a bureau-company has settings (location, primary team-member).

a bureau-company has clients.

### Client Company

a client-company has client-team-members (users).

a client-company can send invitations to new-employee email addresses.

a client-company has settings (location, primary team-members).

a client-company has onboardings (the successful completion of an invitation that contains additional employee details provided by employee).

### Bureau Team Member

a bureau-team-member can CRUD their own bureau-company’s clients.

a bureau-team-member can update their own password.

a bureau-team-member cannot CRUD any other bureau-company’s clients.

a bureau-team-member with primary-role can CRUD their own bureau-company’s details.

a bureau-team-member with primary-role can CRUD their own bureau-company’s team-members.

any bureau-team-member can CRUD any of their bureau-company’s client-company details.

any bureau-team-member can CRUD any of their bureau-company’s client-company's invitations.

a bureau-team-member cannot CRUD a different bureau-company’s details.

a bureau-team-member cannot CRUD a different bureau-company’s team-members.

a bureau-team-member cannot CRUD a different bureau-company’s client-company details.

a bureau-team-member cannot CRUD a different bureau-company’s client-company's invitations.

### Client Team Member

a client-team-member cannot CRUD any bureaus.

a client team-member with primary-role can CRUD their own client-company’s details.

a client team-member with primary-role can CRUD their team-members.

a client team-member can send and CRUD invitations to new-employees.

a client team-member cannot CRUD any other client’s details or team-members.

a client team-member cannot CRUD any other bureau’s details or team-members.

## Architecture

### Custom Post Types (Companies as Posts)

// Register Bureau Company post type
function register_bureau_company_post_type() {
    register_post_type('bureau_company', array(
        'labels' =&gt; array(
            'name' =&gt; 'Bureau Companies',
            'singular_name' =&gt; 'Bureau Company'
        ),
        'public' =&gt; false,
        'show_ui' =&gt; true,
        'show_in_menu' =&gt; true,
        'capability_type' =&gt; 'bureau_company',
        'map_meta_cap' =&gt; true,
        'supports' =&gt; array('title', 'editor', 'custom-fields')
    ));
}

// Register Client Company post type  
function register_client_company_post_type() {
    register_post_type('client_company', array(
        'labels' =&gt; array(
            'name' =&gt; 'Client Companies',
            'singular_name' =&gt; 'Client Company'
        ),
        'public' =&gt; false,
        'show_ui' =&gt; true,
        'show_in_menu' =&gt; true,
        'capability_type' =&gt; 'client_company',
        'map_meta_cap' =&gt; true,
        'supports' =&gt; array('title', 'editor', 'custom-fields')
    ));
}

// Register supporting post types
function register_supporting_post_types() {
    // Invitations
    register_post_type('invitation', array(
        'capability_type' =&gt; 'invitation',
        'supports' =&gt; array('title', 'custom-fields')
    ));
    
    // Onboardings
    register_post_type('onboarding', array(
        'capability_type' =&gt; 'onboarding',
        'supports' =&gt; array('title', 'custom-fields')
    ));
}

### Custom User Roles

function create_business_user_roles() {
    // Bureau roles
    add_role('bureau_primary', 'Bureau Primary Member', array(
        'read' =&gt; true,
        'edit_bureau_company' =&gt; true,
        'edit_bureau_companies' =&gt; true,
        'delete_bureau_company' =&gt; true,
        'edit_users' =&gt; true,
        'delete_users' =&gt; true,
        'create_users' =&gt; true,
        'edit_client_companies' =&gt; true,
        'edit_client_company' =&gt; true,
        'delete_client_companies' =&gt; true,
        'create_client_companies' =&gt; true,
        'send_invitations' =&gt; true
    ));
    
    add_role('bureau_member', 'Bureau Team Member', array(
        'read' =&gt; true,
        'edit_client_companies' =&gt; true,
        'edit_client_company' =&gt; true,
        'delete_client_companies' =&gt; true,
        'create_client_companies' =&gt; true,
        'send_invitations' =&gt; true
    ));
    
    // Client roles
    add_role('client_primary', 'Client Primary Member', array(
        'read' =&gt; true,
        'edit_client_company' =&gt; true,
        'edit_users' =&gt; true,
        'delete_users' =&gt; true,
        'create_users' =&gt; true,
        'send_invitations' =&gt; true
    ));
    
    add_role('client_member', 'Client Team Member', array(
        'read' =&gt; true
    ));
}

### User-Company Relationships (User Meta)

class Company_User_Manager {
    
    // Link user to company
    public function assign_user_to_company($user_id, $company_id, $company_type) {
        update_user_meta($user_id, '_company_id', $company_id);
        update_user_meta($user_id, '_company_type', $company_type); // 'bureau' or 'client'
    }
    
    // Get user's company
    public function get_user_company($user_id) {
        return array(
            'company_id' =&gt; get_user_meta($user_id, '_company_id', true),
            'company_type' =&gt; get_user_meta($user_id, '_company_type', true)
        );
    }
    
    // Get all users in a company
    public function get_company_users($company_id) {
        return get_users(array(
            'meta_key' =&gt; '_company_id',
            'meta_value' =&gt; $company_id
        ));
    }
    
    // Check if user belongs to company
    public function user_belongs_to_company($user_id, $company_id) {
        $user_company = get_user_meta($user_id, '_company_id', true);
        return $user_company == $company_id;
    }
}

### Permission System (Map Meta Capabilities)

function map_business_meta_caps($caps, $cap, $user_id, $args) {
    
    $company_manager = new Company_User_Manager();
    $user_company = $company_manager-&gt;get_user_company($user_id);
    
    switch($cap) {
        
        case 'edit_bureau_company':
        case 'delete_bureau_company':
            if (isset($args[0])) {
                $company_id = $args[0];
                // Only allow if user belongs to this bureau and has primary role
                if ($user_company['company_type'] === 'bureau' &amp;&amp; 
                    $user_company['company_id'] == $company_id &amp;&amp;
                    user_can($user_id, 'bureau_primary')) {
                    $caps = array('bureau_primary');
                } else {
                    $caps = array('do_not_allow');
                }
            }
            break;
            
        case 'edit_client_company':
        case 'delete_client_company':
            if (isset($args[0])) {
                $client_company_id = $args[0];
                
                // Get the bureau that owns this client
                $bureau_id = get_post_meta($client_company_id, '_bureau_company_id', true);
                
                if ($user_company['company_type'] === 'bureau' &amp;&amp; 
                    $user_company['company_id'] == $bureau_id) {
                    // Bureau member can edit their clients
                    $caps = array('bureau_member');
                } elseif ($user_company['company_type'] === 'client' &amp;&amp; 
                         $user_company['company_id'] == $client_company_id &amp;&amp;
                         user_can($user_id, 'client_primary')) {
                    // Client primary can edit their own company
                    $caps = array('client_primary');
                } else {
                    $caps = array('do_not_allow');
                }
            }
            break;
            
        case 'edit_users':
        case 'delete_users':
            if (isset($args[0])) {
                $target_user_id = $args[0];
                $target_company = $company_manager-&gt;get_user_company($target_user_id);
                
                // Can only manage users in same company if primary role
                if ($user_company['company_id'] == $target_company['company_id'] &amp;&amp;
                    (user_can($user_id, 'bureau_primary') || user_can($user_id, 'client_primary'))) {
                    $caps = array('edit_users');
                } else {
                    $caps = array('do_not_allow');
                }
            }
            break;
    }
    
    return $caps;
}
add_filter('map_meta_cap', 'map_business_meta_caps', 10, 4);


### Company Settings (Post Meta)

class Company_Settings_Manager {
    
    public function update_company_settings($company_id, $settings) {
        foreach($settings as $key =&gt; $value) {
            update_post_meta($company_id, "_company_{$key}", $value);
        }
    }
    
    public function get_company_settings($company_id) {
        return array(
            'location' =&gt; get_post_meta($company_id, '_company_location', true),
            'primary_member' =&gt; get_post_meta($company_id, '_company_primary_member', true)
        );
    }
    
    public function set_primary_member($company_id, $user_id) {
        update_post_meta($company_id, '_company_primary_member', $user_id);
    }
}

### Client-Bureau Relationships

class Client_Bureau_Manager {
    
    // Link client to bureau
    public function assign_client_to_bureau($client_company_id, $bureau_company_id) {
        update_post_meta($client_company_id, '_bureau_company_id', $bureau_company_id);
    }
    
    // Get bureau's clients
    public function get_bureau_clients($bureau_company_id) {
        return get_posts(array(
            'post_type' =&gt; 'client_company',
            'meta_key' =&gt; '_bureau_company_id',
            'meta_value' =&gt; $bureau_company_id,
            'posts_per_page' =&gt; -1
        ));
    }
    
    // Check if client belongs to bureau
    public function client_belongs_to_bureau($client_id, $bureau_id) {
        $client_bureau = get_post_meta($client_id, '_bureau_company_id', true);
        return $client_bureau == $bureau_id;
    }
}

### Invitation System

class Invitation_Manager {
    
    public function create_invitation($email, $company_id, $company_type, $role) {
        $invitation_id = wp_insert_post(array(
            'post_type' =&gt; 'invitation',
            'post_title' =&gt; "Invitation to {$email}",
            'post_status' =&gt; 'pending',
            'post_author' =&gt; get_current_user_id()
        ));
        
        if ($invitation_id) {
            update_post_meta($invitation_id, '_invitation_email', $email);
            update_post_meta($invitation_id, '_invitation_company_id', $company_id);
            update_post_meta($invitation_id, '_invitation_company_type', $company_type);
            update_post_meta($invitation_id, '_invitation_role', $role);
            update_post_meta($invitation_id, '_invitation_token', wp_generate_password(32, false));
            
            // Send invitation email
            $this-&gt;send_invitation_email($invitation_id);
        }
        
        return $invitation_id;
    }
    
    public function accept_invitation($token, $user_details) {
        $invitation = get_posts(array(
            'post_type' =&gt; 'invitation',
            'meta_key' =&gt; '_invitation_token',
            'meta_value' =&gt; $token,
            'post_status' =&gt; 'pending'
        ));
        
        if ($invitation) {
            $invitation = $invitation[0];
            
            // Create user
            $user_id = wp_create_user(
                $user_details['username'],
                $user_details['password'],
                get_post_meta($invitation-&gt;ID, '_invitation_email', true)
            );
            
            if (!is_wp_error($user_id)) {
                // Assign role and company
                $user = new WP_User($user_id);
                $role = get_post_meta($invitation-&gt;ID, '_invitation_role', true);
                $user-&gt;set_role($role);
                
                $company_manager = new Company_User_Manager();
                $company_manager-&gt;assign_user_to_company(
                    $user_id,
                    get_post_meta($invitation-&gt;ID, '_invitation_company_id', true),
                    get_post_meta($invitation-&gt;ID, '_invitation_company_type', true)
                );
                
                // Mark invitation as accepted
                wp_update_post(array(
                    'ID' =&gt; $invitation-&gt;ID,
                    'post_status' =&gt; 'accepted'
                ));
                
                // Create onboarding record if client
                if (get_post_meta($invitation-&gt;ID, '_invitation_company_type', true) === 'client') {
                    $this-&gt;create_onboarding_record($user_id, $user_details);
                }
            }
        }
    }
    
    private function create_onboarding_record($user_id, $details) {
        $onboarding_id = wp_insert_post(array(
            'post_type' =&gt; 'onboarding',
            'post_title' =&gt; "Onboarding for User {$user_id}",
            'post_status' =&gt; 'completed'
        ));
        
        if ($onboarding_id) {
            update_post_meta($onboarding_id, '_onboarding_user_id', $user_id);
            update_post_meta($onboarding_id, '_onboarding_details', $details);
        }
    }
}


### Admin Interface Filters

// Filter admin screens to show only relevant data
function filter_admin_queries($query) {
    if (!is_admin() || !$query-&gt;is_main_query()) {
        return;
    }
    
    $screen = get_current_screen();
    $current_user = wp_get_current_user();
    $company_manager = new Company_User_Manager();
    $user_company = $company_manager-&gt;get_user_company($current_user-&gt;ID);
    
    if ($screen-&gt;post_type === 'client_company') {
        if (in_array('bureau_member', $current_user-&gt;roles) || in_array('bureau_primary', $current_user-&gt;roles)) {
            // Show only clients belonging to this bureau
            $query-&gt;set('meta_key', '_bureau_company_id');
            $query-&gt;set('meta_value', $user_company['company_id']);
        } elseif (in_array('client_primary', $current_user-&gt;roles) || in_array('client_member', $current_user-&gt;roles)) {
            // Show only their own company
            $query-&gt;set('post__in', array($user_company['company_id']));
        }
    }
}
add_action('pre_get_posts', 'filter_admin_queries');