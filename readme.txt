=== Business Hierarchy Manager ===
Contributors: yourname
Tags: business, hierarchy, payroll, bureaus, clients, permissions, user management
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive WordPress plugin for managing business hierarchies with payroll bureaus, client companies, team members, and permissions.

== Description ==

Business Hierarchy Manager creates a WordPress-native hierarchy system for managing relationships between Payroll Bureaus and their Clients, with comprehensive user role management and invitation workflows.

= Key Features =

* **WordPress-Native Architecture**: Leverages WordPress's built-in systems (Custom Post Types, User Meta, Capabilities, Hooks & Filters)
* **Multi-Tenant Hierarchy**: Manage bureau companies, client companies, and their team members
* **Granular Permissions**: Role-based access control with primary member privileges
* **Admin Integration**: Seamless integration with WordPress admin interface
* **Custom Post Types**: Bureau Companies, Client Companies, and Invitations
* **User Role Management**: Bureau Primary, Bureau Member, Client Primary, Client Member roles

= Current Status =

**Core Infrastructure Complete:**
* Plugin initialization and activation system
* Custom post types registration
* User role creation and capability mapping
* Database table creation
* Admin menu structure with submenus
* Administrator access to all custom post types

**In Development:**
* User-company relationship management
* Frontend dashboards for bureau and client users
* Invitation system and onboarding workflow
* Advanced permission enforcement

= Use Cases =

* Payroll bureaus managing multiple client companies
* Client companies managing their team members
* Invitation-based onboarding for new employees
* Role-based access control for business hierarchies
* Multi-tenant business relationship management

== Installation ==

1. Upload plugin files to `/wp-content/plugins/business-hierarchy-manager/`
2. Activate the plugin through WordPress admin
3. Navigate to "Business Hierarchy" in admin menu
4. Create your first bureau company

= Requirements =

* WordPress 5.0 or higher
* PHP 7.4 or higher
* MySQL 5.6 or higher

== Frequently Asked Questions ==

= What is a Bureau Company? =

A Bureau Company is a payroll bureau that manages multiple client companies. Examples include Payroll Professionals LLC or other payroll service providers.

= What is a Client Company? =

A Client Company is a business that uses the services of a Bureau Company for payroll management. Examples include small businesses, contractors, or organizations.

= What are the different user roles? =

* **Bureau Primary Member**: Full bureau management capabilities
* **Bureau Team Member**: Limited bureau and client management
* **Client Primary Member**: Full client company management
* **Client Team Member**: Basic client company access

= Can users belong to multiple companies? =

The current implementation supports users belonging to one company at a time, with plans for multi-company support in future versions.

= Is this plugin compatible with multisite? =

The plugin is designed for single-site installations. Multisite compatibility is planned for future versions.

== Screenshots ==

1. Admin menu structure with Business Hierarchy submenus
2. Bureau Company creation interface
3. Client Company management screen
4. User role assignment interface (coming soon)
5. Frontend dashboard for bureau users (coming soon)

== Changelog ==

= 1.0.0 =
* Initial release with core infrastructure
* Custom post types for Bureau Companies, Client Companies, and Invitations
* User role creation and capability mapping
* Admin menu integration
* Database table creation for user-company relationships
* Memory-optimized activation process

== Upgrade Notice ==

= 1.0.0 =
Initial release. This is the first stable version with core infrastructure complete.

== Development Roadmap ==

**Phase 1: Core User Management**
* User-company relationship management
* Role assignment and validation
* Primary member designation system

**Phase 2: Permission System**
* Granular permission checking
* Cross-company access restrictions
* Role-based capability enforcement

**Phase 3: Frontend Dashboard**
* Bureau dashboard interface
* Client dashboard interface
* User management screens

**Phase 4: Invitation System**
* Invitation creation and sending
* Email notifications
* Onboarding workflow

**Phase 5: Advanced Features**
* Bulk operations
* Data import/export
* Reporting and analytics

== Technical Details ==

= Architecture =

The plugin uses WordPress-native architecture:
* Custom Post Types for content management
* User Meta for relationship storage
* Capabilities for permission control
* Hooks & Filters for extensibility

= Database Schema =

```sql
-- User-Company Relationships
wp_business_hierarchy_user_companies (
    id, user_id, company_id, company_type, 
    role, is_primary, created_at
)
```

= File Structure =

```
business-hierarchy-manager/
├── business-hierarchy-manager.php    # Main plugin file
├── includes/                         # Core plugin classes
├── admin/                           # Admin-specific functionality
├── public/                          # Frontend functionality
├── core/                            # Business logic classes
├── database/                        # Database management
├── assets/                          # CSS, JS, images
├── templates/                       # Template files
├── languages/                       # Translation files
└── uninstall.php                   # Cleanup on uninstall
```

== Support ==

For support and documentation, please refer to the plugin's documentation or create an issue in the project repository.

== Contributing ==

This plugin is designed with extensibility in mind. Key extension points:
* Custom hooks for business logic
* Filterable capability checks
* Extensible user role system
* Pluggable invitation workflows